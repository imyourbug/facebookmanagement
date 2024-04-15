@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
@endpush
@push('scripts')
    <script src="/js/admin/account/index.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
@endpush
@section('content')
    <form action="{{ route('admin.accounts.update', ['id' => $user->id]) }}" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Tài khoản <span class="required">(*)</span></label>
                        <input type="text" class="form-control" id="name" value="{{ $user->name ?? $user->email }}"
                            placeholder="Nhập tên người dùng" disabled>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Mật khẩu</label>
                        <input type="password" class="form-control" id="name" name="password"
                            value="{{ old('password') }}" placeholder="Nhập mật khẩu">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Delay time mặc định <span class="required">(*)</span></label>
                        <input type="number" min="0" class="form-control" name="delay"
                            value="{{ old('delay') ?? $user->delay }}" placeholder="Nhập delay time mặc định">
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Limit post quét <span class="required">(*)</span></label>
                        <input type="number" min="0" class="form-control" name="limit"
                            value="{{ old('limit') ?? $user->limit }}" placeholder="Nhập limit post quét">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Số ngày hết hạn <span class="required">(*)</span></label>
                        <input type="number" min="0" class="form-control" name="expire"
                            value="{{ old('expire') ?? $user->expire }}" placeholder="Nhập số ngày hết hạn">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Phân quyền <span class="required">(*)</span></label>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="user" value="0" name="role"
                                {{ $user->role == 0 ? 'checked' : '' }}>
                            <label for="user" class="custom-control-label">Người dùng</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="admin" value="1"
                                name="role"{{ $user->role == 1 ? 'checked' : '' }}>
                            <label for="admin" class="custom-control-label">Quản lý</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-success">Xem danh sách</a>
        </div>
        @csrf
    </form>
@endsection
