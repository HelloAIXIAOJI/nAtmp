@extends('layouts.app')

@section('title', '我的网站 - Nginx虚拟主机管理系统')

@section('content')
<div class="flex-between mb-3">
    <h1>我的网站</h1>
    <a href="{{ route('websites.create') }}" class="btn btn-success">创建新网站</a>
</div>

<div class="card">
    @if($websites->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>描述</th>
                    <th>域名数量</th>
                    <th>状态</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($websites as $website)
                <tr>
                    <td><strong>{{ $website->id }}</strong></td>
                    <td>{{ $website->name }}</td>
                    <td>{{ Str::limit($website->description, 50) }}</td>
                    <td>{{ $website->domains->count() }}</td>
                    <td>
                        @if($website->status == 'active')
                            <span class="badge badge-success">活跃</span>
                        @elseif($website->status == 'inactive')
                            <span class="badge badge-warning">未激活</span>
                        @else
                            <span class="badge badge-danger">已暂停</span>
                        @endif
                    </td>
                    <td>{{ $website->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <div class="flex">
                            <a href="{{ route('websites.show', $website) }}" class="btn" style="padding: 0.25rem 0.75rem;">查看</a>
                            <a href="{{ route('websites.edit', $website) }}" class="btn btn-secondary" style="padding: 0.25rem 0.75rem;">编辑</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">您还没有创建任何网站。</p>
        <a href="{{ route('websites.create') }}" class="btn btn-success mt-3">创建第一个网站</a>
    @endif
</div>
@endsection
