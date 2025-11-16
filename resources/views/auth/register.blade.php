@extends('layouts.app')

@section('title', '注册 - Nginx虚拟主机管理系统')

@section('content')
<div class="card" style="max-width: 500px; margin: 3rem auto;">
    <h2 style="margin-bottom: 1.5rem;">注册账户</h2>
    
    <form action="{{ route('register.process') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">用户名</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        
        <div class="form-group">
            <label for="email">邮箱</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        
        <div class="form-group">
            <label for="password">密码</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <small class="text-muted">至少8个字符</small>
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">确认密码</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-success" style="width: 100%;">注册</button>
    </form>
    
    <p class="mt-3" style="text-align: center;">
        已有账户？ <a href="{{ route('login') }}">立即登录</a>
    </p>
</div>
@endsection
