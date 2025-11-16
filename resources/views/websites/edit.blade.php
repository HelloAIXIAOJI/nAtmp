@extends('layouts.app')

@section('title', '编辑网站 - Nginx虚拟主机管理系统')

@section('content')
<h1 style="margin-bottom: 2rem;">编辑网站</h1>

<div class="card">
    <form action="{{ route('websites.update', $website) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">网站名称 *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $website->name) }}" required>
        </div>
        
        <div class="form-group">
            <label for="description">描述</label>
            <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $website->description) }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="status">状态</label>
            <select id="status" name="status" class="form-control">
                <option value="active" {{ old('status', $website->status) == 'active' ? 'selected' : '' }}>活跃</option>
                <option value="inactive" {{ old('status', $website->status) == 'inactive' ? 'selected' : '' }}>未激活</option>
                <option value="suspended" {{ old('status', $website->status) == 'suspended' ? 'selected' : '' }}>已暂停</option>
            </select>
        </div>
        
        <div class="flex">
            <button type="submit" class="btn btn-success">保存更改</button>
            <a href="{{ route('websites.show', $website) }}" class="btn btn-secondary">取消</a>
        </div>
    </form>
</div>
@endsection
