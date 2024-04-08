@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
@endpush
@push('scripts')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="/js/admin/customer/index.js?v=1"></script>
    <script>
        $("#upload").change(function() {
            const form = new FormData();
            form.append("file", $(this)[0].files[0]);
            console.log(form);
            $.ajax({
                processData: false,
                contentType: false,
                type: "POST",
                data: form,
                url: "/api/upload",
                success: function(response) {
                    if (response.status == 0) {
                        //hiển thị ảnh
                        $("#image_show").attr('src', response.url);
                        $("#avatar").val(response.url);
                    } else {
                        toastr.error(response.message, 'Thông báo');
                    }
                },
            });
        });
    </script>
@endpush
@section('content')
    <form action="{{ route('admin.customers.store') }}" method="POST">
        <div class="row">
            <div class="col-lg-12">
                <div class="card direct-chat direct-chat-primary">
                    <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                        <h3 class="card-title text-bold">Thông tin khách hàng</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="display: block;padding: 10px !important;">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="menu">Họ tên <span class="required">(*)</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                        placeholder="Nhập họ tên">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="menu">Đại diện khách hàng</label>
                                    <input type="text" class="form-control" name="representative"
                                        value="{{ old('representative') }}" placeholder="Nhập người đại diện">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="menu">Mã số thuế</label>
                                    <input type="text" class="form-control" name="tax_code" value="{{ old('tax_code') }}"
                                        placeholder="Nhập mã số thuế">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="menu">Địa chỉ <span class="required">(*)</span></label>
                                    <input type="text" class="form-control" name="address" value="{{ old('address') }}"
                                        placeholder="Nhập địa chỉ">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="menu">Website</label>
                                    <input type="text" class="form-control" id="website" name="website"
                                        value="{{ old('website') }}" placeholder="Nhập website">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="menu">Email <span class="required">(*)</span></label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                        placeholder="Nhập email">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="menu">Người liên hệ</label>
                                    <input type="text" class="form-control" name="manager" value="{{ old('manager') }}"
                                        placeholder="Nhập người liên hệ">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="menu">Số điện thoại <span class="required">(*)</span></label>
                                    <input type="text" class="form-control" name="tel" value="{{ old('tel') }}"
                                        placeholder="Nhập số điện thoại">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="file">Ảnh đại diện</label><br>
                                    <div class="">
                                        <img id="image_show" style="width: 100px;height:100px" src=""
                                            alt="Avatar" />
                                        <input type="file" id="upload" accept=".png,.jpeg">
                                    </div>
                                    <input type="hidden" name="avatar" id="avatar" value="{{ old('avatar') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                        <h3 class="card-title text-bold">Thông tin đăng nhập</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="display: block;padding: 10px !important;">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="menu">Tài khoản (Số điện thoại hoặc email) <span
                                            class="required">(*)</span></label>
                                    <input type="text" class="form-control" name="tel_or_email"
                                        value="{{ old('tel_or_email') }}" placeholder="Nhập tài khoản">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="menu">Mật khẩu <span class="required">(*)</span></label>
                                    <input type="password" class="form-control" name="password"
                                        value="{{ old('password') }}" placeholder="Nhập mật khẩu">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @csrf
    </form>
    <div class="row">
        <div class="col-lg-12">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                    <h3 class="card-title text-bold">Danh sách khách hàng</h3>
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
                                <!-- <th>ID</th> -->
                                <th>Khách hàng</th>
                                <th>Đại diện khách hàng</th>
                                <th>Mã số thuế</th>
                                <th>Địa chỉ</th>
                                <th>Website</th>
                                <th>Email</th>
                                <th>Ảnh đại diện</th>
                                <th>Người liên hệ</th>
                                <th>Số điện thoại</th>
                                <th>Tài khoản</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
