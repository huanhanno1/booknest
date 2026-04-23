@extends('layouts.app')

@section('title', 'Đăng Nhập - BookHaven')

@push('styles')
<style>
.auth-wrapper {
    min-height: calc(100vh - 160px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2.5rem 1rem;
}
.auth-card {
    width: 100%;
    max-width: 420px;
}
.auth-brand {
    text-align: center;
    margin-bottom: 1.75rem;
}
.auth-brand a {
    text-decoration: none;
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}
.auth-brand .brand-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    background: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.5rem;
}
.auth-brand .brand-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.02em;
}
.auth-brand .brand-sub {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-top: -4px;
}
.auth-box {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 2rem;
}
.auth-box h4 {
    font-weight: 700;
    font-size: 1.15rem;
    color: var(--text);
    margin-bottom: 1.5rem;
    text-align: center;
}
.form-field {
    margin-bottom: 1rem;
}
.form-field label {
    display: block;
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--text);
    margin-bottom: 0.4rem;
}
.input-wrap {
    position: relative;
}
.input-wrap .field-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.9rem;
    pointer-events: none;
}
.input-wrap input {
    width: 100%;
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 0.65rem 0.875rem 0.65rem 2.4rem;
    font-size: 0.9rem;
    color: var(--text);
    background: var(--card);
    outline: none;
    transition: border-color 0.15s;
    box-sizing: border-box;
}
.input-wrap input:focus {
    border-color: var(--primary);
}
.input-wrap input.is-invalid {
    border-color: var(--danger);
}
.invalid-msg {
    color: var(--danger);
    font-size: 0.78rem;
    margin-top: 0.3rem;
    display: flex;
    align-items: center;
    gap: 4px;
}
.btn-auth {
    width: 100%;
    padding: 0.75rem;
    border: none;
    border-radius: 6px;
    background: var(--primary);
    color: #fff;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: background 0.15s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.btn-auth:hover {
    background: var(--primary-dark);
}
.auth-footer {
    text-align: center;
    margin-top: 1.25rem;
    font-size: 0.875rem;
    color: var(--text-muted);
}
.auth-footer a {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
}
.auth-footer a:hover {
    text-decoration: underline;
}
.check-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
}
.check-wrap input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: var(--primary);
    cursor: pointer;
    flex-shrink: 0;
}
.check-wrap label {
    font-size: 0.85rem;
    color: var(--text-muted);
    cursor: pointer;
    margin: 0;
}
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">

        {{-- Thương hiệu --}}
        <div class="auth-brand">
            <a href="{{ url('/') }}">
                <div class="brand-icon">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="brand-name">BookHaven</div>
            </a>
            <p class="brand-sub mt-2 mb-0">Đăng nhập để tiếp tục mua sắm</p>
        </div>

        {{-- Hộp đăng nhập --}}
        <div class="auth-box">
            <h4>Đăng Nhập</h4>

            @if(session('status'))
                <div style="background:#DCFCE7; border:1px solid #BBF7D0; border-radius:6px; padding:0.65rem 0.875rem; margin-bottom:1rem; font-size:0.875rem; color:#166534; display:flex; align-items:center; gap:8px;">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-field">
                    <label for="email">Địa chỉ Email</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope field-icon"></i>
                        <input type="email"
                               id="email"
                               name="email"
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email') }}"
                               placeholder="example@email.com"
                               required
                               autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="password">Mật Khẩu</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock field-icon"></i>
                        <input type="password"
                               id="password"
                               name="password"
                               class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                               placeholder="Nhập mật khẩu"
                               required>
                    </div>
                    @error('password')
                        <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-field" style="margin-bottom:1.5rem;">
                    <div class="check-wrap">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Ghi nhớ đăng nhập</label>
                    </div>
                </div>

                <button type="submit" class="btn-auth">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Đăng Nhập
                </button>

            </form>
        </div>

        <div class="auth-footer">
            Chưa có tài khoản?
            <a href="{{ route('register') }}">Đăng ký ngay</a>
        </div>

    </div>
</div>
@endsection
