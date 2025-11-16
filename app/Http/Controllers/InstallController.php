<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InstallController extends Controller
{
    /**
     * Show the installation page.
     */
    public function index()
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect()->route('dashboard')->with('info', '系统已安装');
        }

        return view('install.index');
    }

    /**
     * Process the installation.
     */
    public function install(Request $request)
    {
        if ($this->isInstalled()) {
            return redirect()->route('dashboard')->with('info', '系统已安装');
        }

        try {
            // Run migrations
            Artisan::call('migrate', ['--force' => true]);

            // Mark as installed
            SystemSetting::set('installed', '1');
            SystemSetting::set('installed_at', now()->toDateTimeString());

            return redirect()->route('register')
                ->with('success', '系统安装成功！请注册您的账户');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '安装失败：' . $e->getMessage());
        }
    }

    /**
     * Check if system is installed.
     */
    private function isInstalled()
    {
        try {
            if (!Schema::hasTable('system_settings')) {
                return false;
            }
            return SystemSetting::get('installed') === '1';
        } catch (\Exception $e) {
            return false;
        }
    }
}
