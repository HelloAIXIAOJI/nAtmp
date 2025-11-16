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
        
        // In production, list files from {$website->files_path}
        // For now, we'll use local storage simulation
        $path = 'websites/' . $website->id;
        
        $files = [];
        if (Storage::exists($path)) {
            $files = Storage::files($path);
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

        $path = 'websites/' . $website->id;
        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $filename = $file->getClientOriginalName();
            $file->storeAs($path, $filename);
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

        $path = 'websites/' . $website->id . '/' . $filename;

        if (!Storage::exists($path)) {
            abort(404, '文件不存在');
        }

        Storage::delete($path);

        return redirect()->route('files.index', $website)
            ->with('success', '文件已删除！');
    }
}
