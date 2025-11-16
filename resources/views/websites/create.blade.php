@extends('layouts.app')

@section('title', '创建网站 - Nginx虚拟主机管理系统')

@section('content')
<h1 style="margin-bottom: 2rem;">创建新网站</h1>

<div class="card">
    <form action="{{ route('websites.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">网站名称 *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        
        <div class="form-group">
            <label for="description">描述</label>
            <textarea id="description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
        </div>
        
        <div class="flex">
            <button type="submit" class="btn btn-success">创建网站</button>
            <a href="{{ route('websites.index') }}" class="btn btn-secondary">取消</a>
        </div>
    </form>
</div>

<div class="card">
    <h3>说明</h3>
    <ul style="margin-left: 1.5rem; margin-top: 1rem;">
        <li>创建网站后会自动生成唯一的网站ID（纯数字）</li>
        <li>系统会在 /www/vhost/ 目录下创建 ID.conf 配置文件</li>
        <li>网站文件将存储在 /www/hostfiles/ID/ 目录</li>
        <li>创建后可以为网站绑定域名和上传文件</li>
    </ul>
</div>
@endsection
