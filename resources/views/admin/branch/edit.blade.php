@extends('admin.main')
@push('styles')
@endpush
@push('scripts')
@endpush
@section('content')
    <form action="{{ route('admin.branches.update', ['id' => $branch->id]) }}" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Tên chi nhánh <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') ?? $branch->name }}"
                            placeholder="Nhập tên chi nhánh">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Địa chỉ <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="address"
                            value="{{ old('address') ?? $branch->address }}" placeholder="Nhập địa chỉ">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Email <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="email"
                            value="{{ old('email') ?? $branch->email }}" placeholder="Nhập email">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Số điện thoại <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="tel" value="{{ old('tel') ?? $branch->tel }}"
                            placeholder="Nhập số điện thoại">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Quản lý <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="manager"
                            value="{{ old('manager') ?? $branch->manager }}" placeholder="Nhập quản lý">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Khách hàng <span class="required">(*)</span></label>
                        <select class="form-control" name="user_id">
                            <option value="">--Khách hàng--</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->user_id }}"
                                    {{ $customer->user_id == $branch->user_id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{route('admin.branches.index')}}" class="btn btn-success">Xem danh sách</a>
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
                        <th>Khách hàng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($branch->contracts as $key => $contract)
                        <tr class="row{{ $contract->id }}">
                            <th>{{ $contract->id }}</th>
                            <th>{{ $contract->name }}</th>
                            <th>{{ $branch->name }}</th>
                            <td>{{ date('d-m-Y', strtotime($contract->start)) }}</td>
                            <td>{{ date('d-m-Y', strtotime($contract->finish)) }}</td>
                            <td>{{ $contract->content }}</td>
                            <td>{{ $contract->customer->name }}</td>
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
