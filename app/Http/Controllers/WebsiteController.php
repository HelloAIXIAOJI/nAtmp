<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WebsiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's websites.
     */
    public function index()
    {
        $websites = Auth::user()->websites()->with('domains')->latest()->get();
        return view('websites.index', compact('websites'));
    }

    /**
     * Show the form for creating a new website.
     */
    public function create()
    {
        return view('websites.create');
    }

    /**
     * Store a newly created website.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $website = Website::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'active',
        ]);

        // Generate and write nginx conf file
        $this->generateNginxConf($website);
        
        // Create website files directory
        $filesPath = $website->files_path;
        if (!is_dir($filesPath)) {
            mkdir($filesPath, 0755, true);
        }

        return redirect()->route('websites.show', $website)
            ->with('success', '网站创建成功！网站ID: ' . $website->id);
    }

    /**
     * Display the specified website.
     */
    public function show(Website $website)
    {
        $this->authorize('view', $website);
        $website->load('domains');
        return view('websites.show', compact('website'));
    }

    /**
     * Show the form for editing the specified website.
     */
    public function edit(Website $website)
    {
        $this->authorize('update', $website);
        return view('websites.edit', compact('website'));
    }

    /**
     * Update the specified website.
     */
    public function update(Request $request, Website $website)
    {
        $this->authorize('update', $website);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $website->update($request->only(['name', 'description', 'status']));

        // Regenerate nginx conf file
        $this->generateNginxConf($website);

        return redirect()->route('websites.show', $website)
            ->with('success', '网站更新成功！');
    }

    /**
     * Remove the specified website.
     */
    public function destroy(Website $website)
    {
        $this->authorize('delete', $website);
        
        $confPath = $website->conf_path;
        $filesPath = $website->files_path;
        
        // Delete website
        $website->delete();

        // Delete conf file
        if (file_exists($confPath)) {
            unlink($confPath);
        }
        
        // Delete website files directory
        if (is_dir($filesPath)) {
            $this->deleteDirectory($filesPath);
        }
        
        // Reload nginx
        exec('nginx -s reload 2>&1', $output, $returnCode);

        return redirect()->route('websites.index')
            ->with('success', '网站已删除！');
    }

    /**
     * Generate nginx configuration file content.
     */
    private function generateNginxConf(Website $website)
    {
        $domains = $website->domains;
        
        if ($domains->isEmpty()) {
            $serverName = "localhost";
        } else {
            $serverName = $domains->pluck('domain')->implode(' ');
        }

        $conf = <<<CONF
server {
    listen 80;
    server_name {$serverName};
    
    root {$website->files_path};
    index index.php index.html index.htm;
    
    access_log /var/log/nginx/{$website->id}_access.log;
    error_log /var/log/nginx/{$website->id}_error.log;
    
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
CONF;

        // Write to /www/vhost/{website_id}.conf
        $confPath = $website->conf_path;
        $confDir = dirname($confPath);
        
        // Ensure directory exists
        if (!is_dir($confDir)) {
            mkdir($confDir, 0755, true);
        }
        
        // Write config file
        file_put_contents($confPath, $conf);
        
        // Reload nginx
        exec('nginx -s reload 2>&1', $output, $returnCode);
        
        return $conf;
    }
    
    /**
     * Recursively delete a directory.
     */
    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        
        rmdir($dir);
    }
}
