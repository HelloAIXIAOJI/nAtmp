<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display file manager for the website.
     */
    public function index(Website $website)
    {
        $this->authorize('view', $website);
        
        $path = $website->files_path;
        $files = [];
        
        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            $files = array_map(function($file) use ($path) {
                return $path . '/' . $file;
            }, $files);
        }

        return view('files.index', compact('website', 'files'));
    }

    /**
     * Upload files to the website.
     */
    public function upload(Request $request, Website $website)
    {
        $this->authorize('update', $website);

        $validator = Validator::make($request->all(), [
            'files' => 'required',
            'files.*' => 'file|max:10240', // Max 10MB per file
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $path = $website->files_path;
        
        // 确保目录存在
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        
        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $filename = $file->getClientOriginalName();
            $file->move($path, $filename);
            $uploadedFiles[] = $filename;
        }

        return redirect()->route('files.index', $website)
            ->with('success', '文件上传成功！上传了 ' . count($uploadedFiles) . ' 个文件');
    }

    /**
     * Download a file.
     */
    public function download(Website $website, $filename)
    {
        $this->authorize('view', $website);

        $path = 'websites/' . $website->id . '/' . $filename;

        if (!Storage::exists($path)) {
            abort(404, '文件不存在');
        }

        return Storage::download($path);
    }

    /**
     * Delete a file.
     */
    public function destroy(Website $website, $filename)
    {
        $this->authorize('update', $website);

        $path = $website->files_path . '/' . $filename;

        if (!file_exists($path)) {
            abort(404, '文件不存在');
        }

        unlink($path);

        return redirect()->route('files.index', $website)
            ->with('success', '文件已删除！');
    }
}
