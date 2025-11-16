@extends('layouts.app')

@section('title', '仪表盘 - Nginx虚拟主机管理系统')

@section('content')
<h1 style="margin-bottom: 2rem;">欢迎，{{ $user->name }}！</h1>

<div class="card">
    <h2>统计信息</h2>
    <p style="font-size: 2rem; margin: 1rem 0;">
        <strong>{{ $websiteCount }}</strong> 个网站
    </p>
</div>

<div class="card">
    <div class="flex-between mb-3">
        <h2>最近的网站</h2>
        <a href="{{ route('websites.create') }}" class="btn btn-success">创建新网站</a>
    </div>
    
    @if($websites->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>域名</th>
                    <th>状态</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($websites as $website)
                <tr>
                    <td>{{ $website->id }}</td>
                    <td>{{ $website->name }}</td>
                    <td>
                        @if($website->domains->count() > 0)
                            {{ $website->domains->first()->domain }}
                            @if($website->domains->count() > 1)
                                <span class="text-muted">+{{ $website->domains->count() - 1 }}</span>
                            @endif
                        @else
                            <span class="text-muted">无域名</span>
                        @endif
                    </td>
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
                        <a href="{{ route('websites.show', $website) }}" class="btn" style="padding: 0.25rem 0.75rem;">查看</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($websiteCount > 5)
            <div class="mt-3">
                <a href="{{ route('websites.index') }}">查看全部网站 →</a>
            </div>
        @endif
    @else
        <p class="text-muted">您还没有创建任何网站。</p>
        <a href="{{ route('websites.create') }}" class="btn btn-success mt-3">创建第一个网站</a>
    @endif
</div>
@endsection
