@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Quản lý người dùng</h1>
        <p class="page-header-sub">Danh sách tài khoản người dùng trong hệ thống</p>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-header-title">Tất cả người dùng</h6>
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $users->total() }} tài khoản</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="padding-left:20px;width:55px;">ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th style="width:110px;">Vai trò</th>
                        <th style="width:130px;">Điện thoại</th>
                        <th style="width:110px;">Trạng thái</th>
                        <th style="width:120px;text-align:center;">Thao tác</th>
                        <th style="padding-right:20px;width:120px;">Ngày đăng ký</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td style="padding-left:20px;color:var(--text-muted);">{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:34px;height:34px;background:#EFF6FF;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-person-fill" style="color:var(--primary);font-size:0.9rem;"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.875rem;">{{ $user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:0.875rem;color:var(--text-muted);">{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge-status badge-admin">
                                    <i class="bi bi-shield-fill" style="font-size:0.6rem;"></i> Quản trị
                                </span>
                            @else
                                <span class="badge-status badge-user">
                                    <i class="bi bi-person" style="font-size:0.6rem;"></i> Người dùng
                                </span>
                            @endif
                        </td>
                        <td style="font-size:0.875rem;color:var(--text-muted);">{{ $user->phone ?? '—' }}</td>
                        <td>
                            @if($user->is_active ?? true)
                                <span class="badge-status badge-active">
                                    <i class="bi bi-check-circle-fill" style="font-size:0.6rem;"></i> Hoạt động
                                </span>
                            @else
                                <span class="badge-status badge-inactive">
                                    <i class="bi bi-lock-fill" style="font-size:0.6rem;"></i> Đã khóa
                                </span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggleActive', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                @if($user->is_active ?? true)
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            style="font-size:0.78rem;padding:4px 10px;"
                                            onclick="return confirm('Bạn có chắc muốn khóa tài khoản «{{ addslashes($user->name) }}» không?')"
                                            title="Khóa tài khoản">
                                        <i class="bi bi-lock me-1"></i>Khóa
                                    </button>
                                @else
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-success"
                                            style="font-size:0.78rem;padding:4px 10px;"
                                            onclick="return confirm('Bạn có chắc muốn mở khóa tài khoản «{{ addslashes($user->name) }}» không?')"
                                            title="Mở khóa tài khoản">
                                        <i class="bi bi-unlock me-1"></i>Mở khóa
                                    </button>
                                @endif
                            </form>
                            @else
                                <span style="font-size:0.75rem;color:var(--text-muted);">(Tài khoản bạn)</span>
                            @endif
                        </td>
                        <td style="padding-right:20px;font-size:0.82rem;color:var(--text-muted);">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-people"></i></div>
                                <div class="empty-state-title">Chưa có người dùng nào</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small style="color:var(--text-muted);">
            Hiển thị {{ $users->firstItem() }}–{{ $users->lastItem() }} trong {{ $users->total() }} người dùng
        </small>
        {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
