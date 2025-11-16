@extends('layouts.app')

@section('title', $website->name . ' - Nginx虚拟主机管理系统')

@section('content')
<div class="flex-between mb-3">
    <h1>{{ $website->name }}</h1>
    <div class="flex">
        <a href="{{ route('websites.edit', $website) }}" class="btn">编辑</a>
        <a href="{{ route('files.index', $website) }}" class="btn btn-success">文件管理</a>
    </div>
</div>

<div class="card">
    <h2>基本信息</h2>
    <table style="width: 100%; margin-top: 1rem;">
        <tr>
            <td style="padding: 0.5rem; width: 200px;"><strong>网站ID</strong></td>
            <td style="padding: 0.5rem;">{{ $website->id }}</td>
        </tr>
        <tr>
            <td style="padding: 0.5rem;"><strong>名称</strong></td>
            <td style="padding: 0.5rem;">{{ $website->name }}</td>
        </tr>
        <tr>
            <td style="padding: 0.5rem;"><strong>描述</strong></td>
            <td style="padding: 0.5rem;">{{ $website->description ?: '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 0.5rem;"><strong>状态</strong></td>
            <td style="padding: 0.5rem;">
                @if($website->status == 'active')
                    <span class="badge badge-success">活跃</span>
                @elseif($website->status == 'inactive')
                    <span class="badge badge-warning">未激活</span>
                @else
                    <span class="badge badge-danger">已暂停</span>
                @endif
            </td>
        </tr>
        <tr>
            <td style="padding: 0.5rem;"><strong>配置文件路径</strong></td>
            <td style="padding: 0.5rem;"><code>{{ $website->conf_path }}</code></td>
        </tr>
        <tr>
            <td style="padding: 0.5rem;"><strong>文件目录</strong></td>
            <td style="padding: 0.5rem;"><code>{{ $website->files_path }}</code></td>
        </tr>
        <tr>
            <td style="padding: 0.5rem;"><strong>创建时间</strong></td>
            <td style="padding: 0.5rem;">{{ $website->created_at->format('Y-m-d H:i:s') }}</td>
        </tr>
    </table>
</div>

<div class="card">
    <div class="flex-between mb-3">
        <h2>域名绑定</h2>
        <button onclick="document.getElementById('add-domain-form').style.display='block'" class="btn btn-success">添加域名</button>
    </div>
    
    <div id="add-domain-form" style="display: none; padding: 1rem; background: #f8f9fa; border-radius: 4px; margin-bottom: 1rem;">
        <h3>添加域名</h3>
        <form action="{{ route('domains.store', $website) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="domain">域名</label>
                <input type="text" id="domain" name="domain" class="form-control" placeholder="example.com" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_primary" value="1"> 设为主域名
                </label>
            </div>
            <div class="flex">
                <button type="submit" class="btn btn-success">添加</button>
                <button type="button" onclick="document.getElementById('add-domain-form').style.display='none'" class="btn btn-secondary">取消</button>
            </div>
        </form>
    </div>
    
    @if($website->domains->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>域名</th>
                    <th>类型</th>
                    <th>添加时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($website->domains as $domain)
                <tr>
                    <td>{{ $domain->domain }}</td>
                    <td>
                        @if($domain->is_primary)
                            <span class="badge badge-success">主域名</span>
                        @else
                            <span class="badge">附加域名</span>
                        @endif
                    </td>
                    <td>{{ $domain->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <form action="{{ route('domains.destroy', [$website, $domain]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.75rem;" onclick="return confirm('确定删除此域名？')">删除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">还没有绑定域名</p>
    @endif
</div>

<div class="card">
    <h2>危险操作</h2>
    <form action="{{ route('websites.destroy', $website) }}" method="POST" onsubmit="return confirm('确定要删除这个网站吗？此操作不可恢复！');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">删除网站</button>
    </form>
</div>
@endsection
