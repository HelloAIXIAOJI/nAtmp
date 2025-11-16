@extends('layouts.app')

@section('title', '系统更新 - Nginx虚拟主机管理系统')

@section('content')
<h1 style="margin-bottom: 2rem;">系统更新与迁移</h1>

@if(session('output'))
    <div class="card">
        <h3>执行输出</h3>
        <pre style="background: #f8f9fa; padding: 1rem; border-radius: 4px; overflow-x: auto;">{{ session('output') }}</pre>
    </div>
@endif

<div class="card">
    <h2>数据库迁移</h2>
    <p class="text-muted">使用此功能可视化执行数据库迁移和回滚操作</p>
    
    @if($lastMigration)
        <p style="margin-top: 1rem;">
            <strong>上次迁移时间：</strong> {{ $lastMigration }}
        </p>
    @endif
    
    @if($pendingMigrations->count() > 0)
        <div style="margin: 1.5rem 0; padding: 1rem; background: #fff3cd; border-radius: 4px;">
            <strong>待执行的迁移 ({{ $pendingMigrations->count() }})</strong>
            <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                @foreach($pendingMigrations as $migration)
                    <li><code>{{ $migration }}</code></li>
                @endforeach
            </ul>
        </div>
    @else
        <div style="margin: 1.5rem 0; padding: 1rem; background: #d4edda; border-radius: 4px; color: #155724;">
            ✓ 所有迁移已执行完毕
        </div>
    @endif
    
    <div class="flex mt-3">
        <form action="{{ route('update.migrate') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-success" onclick="return confirm('确定要执行数据库迁移吗？')">
                执行迁移
            </button>
        </form>
        
        <form action="{{ route('update.rollback') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger" onclick="return confirm('确定要回滚最后一批迁移吗？此操作可能导致数据丢失！')">
                回滚迁移
            </button>
        </form>
        
        <button onclick="checkStatus()" class="btn btn-secondary">
            查看迁移状态
        </button>
    </div>
</div>

<div id="status-output" class="card" style="display: none;">
    <h3>迁移状态</h3>
    <pre id="status-content" style="background: #f8f9fa; padding: 1rem; border-radius: 4px; overflow-x: auto;"></pre>
</div>

<div class="card">
    <h3>说明</h3>
    <ul style="margin-left: 1.5rem; margin-top: 1rem;">
        <li><strong>执行迁移：</strong>运行所有待执行的数据库迁移，创建或更新表结构</li>
        <li><strong>回滚迁移：</strong>撤销最后一批执行的迁移，回退到之前的状态</li>
        <li><strong>查看状态：</strong>查看所有迁移的执行状态</li>
        <li>在执行迁移前，建议备份数据库</li>
        <li>回滚操作可能导致数据丢失，请谨慎使用</li>
    </ul>
</div>

@endsection

@section('scripts')
<script>
function checkStatus() {
    fetch('{{ route('update.status') }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('status-content').textContent = data.output;
                document.getElementById('status-output').style.display = 'block';
            } else {
                alert('获取状态失败：' + data.error);
            }
        })
        .catch(error => {
            alert('请求失败：' + error);
        });
}
</script>
@endsection
