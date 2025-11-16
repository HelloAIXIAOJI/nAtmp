@extends('layouts.app')

@section('title', '登录 - Nginx虚拟主机管理系统')

@section('content')
<div class="card" style="max-width: 500px; margin: 3rem auto;">
    <h2 style="margin-bottom: 1.5rem;">登录</h2>
    
    <form action="{{ route('login.process') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="email">邮箱</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
        </div>
        
        <div class="form-group">
            <label for="password">密码</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" name="remember"> 记住我
            </label>
        </div>
        
        <button type="submit" class="btn" style="width: 100%;">登录</button>
    </form>
    
    <p class="mt-3" style="text-align: center;">
        还没有账户？ <a href="{{ route('register') }}">立即注册</a>
    </p>
</div>
@endsection
