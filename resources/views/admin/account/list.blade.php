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
    <form action="{{ route('admin.accounts.store') }}" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Tài khoản <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="tel_or_email" value="{{ old('tel_or_email') }}"
                            placeholder="Nhập tiêu đề">
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Mật khẩu <span class="required">(*)</span></label>
                        <input type="password" class="form-control" name="password" value="{{ old('password') }}"
                            placeholder="Nhập mật khẩu">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Delay time mặc định <span class="required">(*)</span></label>
                        <input type="number" min="0" class="form-control" name="delay"
                            value="{{ $setting['delay-time'] ?? old('delay') }}" placeholder="Nhập delay time mặc định">
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Limit post quét <span class="required">(*)</span></label>
                        <input type="number" min="0" class="form-control" name="limit"
                            value="{{ $setting['craw-count'] ?? old('limit') }}" placeholder="Nhập limit post quét">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Số ngày hết hạn <span class="required">(*)</span></label>
                        <input type="number" min="0" class="form-control" name="expire" value="{{ old('expire') }}"
                            placeholder="Nhập số ngày hết hạn">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Phân quyền <span class="required">(*)</span></label>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="user" value="0" name="role"
                                checked>
                            <label for="user" class="custom-control-label">Người dùng</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="admin" value="1" name="role">
                            <label for="admin" class="custom-control-label">Quản lý</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
        @csrf
    </form>
    <div class="row">
        <div class="col-lg-12">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                    <h3 class="card-title text-bold">Danh sách tài khoản</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;padding: 10px !important;">
                    <table id="table" class="table display nowrap dataTable dtr-inline collapsed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Delay time mặc định</th>
                                <th>Limit post quét</th>
                                <th>Số ngày hết hạn</th>
                                <th>Quyền</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <input type="hidden" id="logging_user_id" value="{{ Auth::id() }}" />
                </div>
            </div>
        </div>
    </div>
@endsection
