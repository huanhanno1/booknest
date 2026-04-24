@extends('layouts.app')

@section('title', 'Tài Khoản Của Tôi - BookHaven')

@push('styles')
<style>
.profile-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    height: 100%;
}
.profile-card-header {
    padding: 0.875rem 1.25rem;
    border-bottom: 1px solid var(--border);
    background: var(--bg);
    display: flex;
    align-items: center;
    gap: 0.625rem;
}
.profile-card-header .card-icon {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    background: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.85rem;
    flex-shrink: 0;
}
.profile-card-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--text);
}
.profile-card-body {
    padding: 1.5rem;
}
.form-field {
    margin-bottom: 1.1rem;
}
.form-field label {
    display: block;
    font-weight: 600;
    font-size: 0.83rem;
    color: var(--text);
    margin-bottom: 0.4rem;
}
.form-field label .req {
    color: var(--danger);
    margin-left: 2px;
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
.input-wrap.textarea-wrap .field-icon {
    top: 0.65rem;
    transform: none;
}
.input-wrap input,
.input-wrap textarea {
    width: 100%;
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 0.6rem 0.875rem 0.6rem 2.4rem;
    font-size: 0.875rem;
    color: var(--text);
    background: var(--card);
    outline: none;
    transition: border-color 0.15s;
    box-sizing: border-box;
}
.input-wrap input:focus,
.input-wrap textarea:focus {
    border-color: var(--primary);
}
.input-wrap input.is-invalid,
.input-wrap textarea.is-invalid {
    border-color: var(--danger);
}
.input-wrap input:disabled,
.input-wrap input[readonly] {
    background: var(--bg);
    color: var(--text-muted);
    cursor: not-allowed;
}
.input-wrap textarea {
    resize: none;
}
.field-note {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 0.3rem;
    display: flex;
    align-items: center;
    gap: 4px;
}
.invalid-msg {
    color: var(--danger);
    font-size: 0.78rem;
    margin-top: 0.3rem;
    display: flex;
    align-items: center;
    gap: 4px;
}
.alert-success-custom {
    background: #F0FDF4;
    border: 1px solid #BBF7D0;
    border-radius: 6px;
    padding: 0.75rem 1rem;
    margin-bottom: 1.25rem;
    font-size: 0.875rem;
    color: #166534;
    display: flex;
    align-items: center;
    gap: 8px;
}
.btn-save {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0.6rem 1.5rem;
    border: none;
    border-radius: 6px;
    background: var(--primary);
    color: #fff;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: background 0.15s;
}
.btn-save:hover {
    background: var(--primary-dark);
}
.quick-nav-card {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 1rem 1.25rem;
    text-decoration: none;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    transition: border-color 0.15s;
}
.quick-nav-card:hover {
    border-color: var(--primary);
}
.quick-nav-card .nav-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: var(--bg);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.quick-nav-card .nav-title {
    font-weight: 600;
    color: var(--text);
    font-size: 0.9rem;
}
.quick-nav-card .nav-desc {
    font-size: 0.78rem;
    color: var(--text-muted);
    margin-top: 1px;
}
</style>
@endpush

@section('content')
<div class="container my-5">

    {{-- Tiêu đề --}}
    <div class="mb-4">
        <h1 style="font-size:1.5rem; font-weight:700; color:var(--text); margin:0;">Quản Lý Tài Khoản</h1>
        <p class="mb-0 mt-1" style="color:var(--text-muted); font-size:0.875rem;">
            Cập nhật thông tin cá nhân và bảo mật tài khoản của bạn
        </p>
    </div>

    <div class="row g-4">

        {{-- Card 1: Thông tin cá nhân --}}
        <div class="col-lg-6">
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="card-icon"><i class="bi bi-person-lines-fill"></i></div>
                    <h5>Thông Tin Cá Nhân</h5>
                </div>
                <div class="profile-card-body">

                    @if(session('success') && !session('password_success'))
                        <div class="alert-success-custom">
                            <i class="bi bi-check-circle-fill"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-field">
                            <label>Họ và Tên <span class="req">*</span></label>
                            <div class="input-wrap">
                                <i class="bi bi-person field-icon"></i>
                                <input type="text"
                                       name="name"
                                       class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                                       value="{{ old('name', $user->name) }}"
                                       placeholder="Nhập họ và tên"
                                       required>
                            </div>
                            @error('name')
                                <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label>Địa Chỉ Email</label>
                            <div class="input-wrap">
                                <i class="bi bi-envelope field-icon"></i>
                                <input type="email"
                                       value="{{ $user->email }}"
                                       disabled>
                            </div>
                            <div class="field-note">
                                <i class="bi bi-info-circle"></i>Email không thể thay đổi
                            </div>
                        </div>

                        <div class="form-field">
                            <label>Số Điện Thoại</label>
                            <div class="input-wrap">
                                <i class="bi bi-telephone field-icon"></i>
                                <input type="tel"
                                       name="phone"
                                       class="{{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="Ví dụ: 0901234567">
                            </div>
                            @error('phone')
                                <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-field" style="margin-bottom:1.5rem;">
                            <label>Địa Chỉ Giao Hàng</label>
                            <div class="input-wrap textarea-wrap">
                                <i class="bi bi-geo-alt field-icon"></i>
                                <textarea name="address"
                                          class="{{ $errors->has('address') ? 'is-invalid' : '' }}"
                                          rows="3"
                                          style="padding-left:2.4rem;"
                                          placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('address', $user->address) }}</textarea>
                            </div>
                            @error('address')
                                <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-save">
                            <i class="bi bi-check-circle"></i>Cập Nhật Thông Tin
                        </button>

                    </form>
                </div>
            </div>
        </div>

        {{-- Card 2: Đổi mật khẩu --}}
        <div class="col-lg-6">
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="card-icon"><i class="bi bi-shield-lock"></i></div>
                    <h5>Đổi Mật Khẩu</h5>
                </div>
                <div class="profile-card-body">

                    @if(session('password_success'))
                        <div class="alert-success-custom">
                            <i class="bi bi-check-circle-fill"></i>
                            {{ session('password_success') }}
                        </div>
                    @endif

                    <form action="{{ route('profile.updatePassword') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-field">
                            <label>Mật Khẩu Hiện Tại <span class="req">*</span></label>
                            <div class="input-wrap">
                                <i class="bi bi-lock field-icon"></i>
                                <input type="password"
                                       name="current_password"
                                       class="{{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                                       placeholder="Nhập mật khẩu hiện tại"
                                       required>
                            </div>
                            @error('current_password')
                                <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label>Mật Khẩu Mới <span class="req">*</span></label>
                            <div class="input-wrap">
                                <i class="bi bi-lock-fill field-icon"></i>
                                <input type="password"
                                       name="password"
                                       class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                                       placeholder="Tối thiểu 8 ký tự"
                                       required>
                            </div>
                            @error('password')
                                <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                            @else
                                <div class="field-note">
                                    <i class="bi bi-info-circle"></i>Mật khẩu phải có ít nhất 8 ký tự
                                </div>
                            @enderror
                        </div>

                        <div class="form-field" style="margin-bottom:1.5rem;">
                            <label>Xác Nhận Mật Khẩu Mới <span class="req">*</span></label>
                            <div class="input-wrap">
                                <i class="bi bi-lock-fill field-icon"></i>
                                <input type="password"
                                       name="password_confirmation"
                                       placeholder="Nhập lại mật khẩu mới"
                                       required>
                            </div>
                        </div>

                        <button type="submit" class="btn-save">
                            <i class="bi bi-key"></i>Đổi Mật Khẩu
                        </button>

                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- Điều hướng nhanh --}}
    <div class="row g-3 mt-2">
        <div class="col-sm-6 col-md-4">
            <a href="{{ route('orders.index') }}" class="quick-nav-card">
                <div class="nav-icon">
                    <i class="bi bi-bag" style="color:var(--primary);"></i>
                </div>
                <div>
                    <div class="nav-title">Đơn Hàng</div>
                    <div class="nav-desc">Xem lịch sử mua hàng</div>
                </div>
                <i class="bi bi-chevron-right ms-auto" style="color:var(--border); font-size:0.8rem;"></i>
            </a>
        </div>
        <div class="col-sm-6 col-md-4">
            <a href="{{ route('wishlist.index') }}" class="quick-nav-card">
                <div class="nav-icon">
                    <i class="bi bi-heart" style="color:var(--danger);"></i>
                </div>
                <div>
                    <div class="nav-title">Sách Yêu Thích</div>
                    <div class="nav-desc">Danh sách sách yêu thích</div>
                </div>
                <i class="bi bi-chevron-right ms-auto" style="color:var(--border); font-size:0.8rem;"></i>
            </a>
        </div>
    </div>

</div>
@endsection