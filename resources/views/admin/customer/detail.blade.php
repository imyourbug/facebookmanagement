@extends('admin.main')
@push('styles')
@endpush
@push('scripts')
@endpush
@section('content')
    <form action="{{ route('admin.customers.update', ['id' => $customer->id]) }}" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Họ tên <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') ?? $customer->name }}"
                            placeholder="Nhập họ tên">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Người đại diện</label>
                        <input type="text" class="form-control" name="representative"
                            value="{{ old('representative') ?? $customer->representative }}"
                            placeholder="Nhập người đại diện">
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Mã số thuế</label>
                        <input type="text" class="form-control" name="tax_code"
                            value="{{ old('tax_code') ?? $customer->tax_code }}" placeholder="Nhập lĩnh vực">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Địa chỉ <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="address"
                            value="{{ old('address') ?? $customer->address }}" placeholder="Nhập địa chỉ">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Website</label>
                        <input type="text" class="form-control" id="website" name="website"
                            value="{{ old('website') ?? $customer->website }}" placeholder="Nhập website">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Email <span class="required">(*)</span></label>
                        <input type="email" class="form-control" name="email"
                            value="{{ old('email') ?? $customer->email }}" placeholder="Nhập email">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Người liên hệ</label>
                        <input type="text" class="form-control" name="manager"
                            value="{{ old('manager') ?? $customer->manager }}" placeholder="Nhập người liên hệ">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Số điện thoại <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="tel" value="{{ old('tel') ?? $customer->tel }}"
                            placeholder="Nhập số điện thoại">
                    </div>
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Tài khoản (Số điện thoại hoặc email) <span class="required">(*)</span></label>
                        <input type="text" readonly class="form-control" name="tel_or_email"
                            value="{{ $customer->user->email ?? ($customer->user->name ?? '') }}"
                            placeholder="Nhập tài khoản">
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Mật khẩu <span class="required">(*)</span></label>
                        <input type="password" class="form-control" name="password" value="{{ old('password') }}"
                            placeholder="Nhập mật khẩu">
                    </div>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="file">Chọn ảnh</label><br>
                        <div class="">
                            <img id="image_show" style="width: 100px;height:100px" src="" alt="Avatar" />
                            <input type="file" id="upload" accept=".png,.jpeg">
                        </div>
                        <input type="hidden" name="avatar" id="avatar"
                            value="{{ old('avatar') ?? $customer->avatar }}">
                    </div>
                </div>

            </div>
            <div class="row">

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-success">Xem danh sách</a>
        </div>
        @csrf
    </form>
    <div class="card direct-chat direct-chat-primary">
        <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
            <h3 class="card-title text-bold">Thông tin hợp đồng</h3>
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
                        <th>Tên hợp đồng</th>
                        <th>Chi nhánh</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Nội dung</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customer->contracts as $key => $contract)
                        <tr class="row{{ $contract->id }}">
                            <th>{{ $contract->id }}</th>
                            <th>{{ $contract->name }}</th>
                            <th>{{ $contract->branch->name ?? '' }}</th>
                            <td>{{ date('d-m-Y', strtotime($contract->start)) }}</td>
                            <td>{{ date('d-m-Y', strtotime($contract->finish)) }}</td>
                            <td>{{ $contract->content }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm"
                                    href='{{ route('admin.contracts.show', ['id' => $contract->id]) }}'>
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a class="btn btn-success btn-sm" style="padding: 4px 15px"
                                    href='{{ route('admin.contracts.detail', ['id' => $contract->id]) }}'>
                                    <i class="fa-solid fa-info"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
