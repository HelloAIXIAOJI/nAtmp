@extends('layouts.app')

@section('title', '文件管理 - ' . $website->name)

@section('content')
<div class="flex-between mb-3">
    <h1>文件管理 - {{ $website->name }}</h1>
    <a href="{{ route('websites.show', $website) }}" class="btn btn-secondary">返回网站</a>
</div>

<div class="card">
    <h2>上传文件</h2>
    <form action="{{ route('files.upload', $website) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="files">选择文件（可多选）</label>
            <input type="file" id="files" name="files[]" class="form-control" multiple required>
            <small class="text-muted">单个文件最大 10MB</small>
        </div>
        <button type="submit" class="btn btn-success">上传</button>
    </form>
</div>

<div class="card">
    <h2>文件列表</h2>
    <p class="text-muted">文件路径：<code>{{ $website->files_path }}</code></p>
    
    @if(count($files) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>文件名</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($files as $file)
                <tr>
                    <td>{{ basename($file) }}</td>
                    <td>
                        <div class="flex">
                            <a href="{{ route('files.download', [$website, basename($file)]) }}" class="btn" style="padding: 0.25rem 0.75rem;">下载</a>
                            <form action="{{ route('files.destroy', [$website, basename($file)]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.75rem;" onclick="return confirm('确定删除此文件？')">删除</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">还没有上传文件</p>
    @endif
</div>

<div class="card">
    <h3>说明</h3>
    <ul style="margin-left: 1.5rem; margin-top: 1rem;">
        <li>在开发环境中，文件存储在 Laravel 的 storage 目录</li>
        <li>在生产环境中，文件会存储到 {{ $website->files_path }}</li>
        <li>支持 PHP、HTML、CSS、JS、图片等各种文件类型</li>
        <li>上传的文件可以通过域名访问</li>
    </ul>
</div>
@endsection
