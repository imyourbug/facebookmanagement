@extends('admin.main')
@push('styles')
@endpush
@push('scripts')
@endpush
@section('content')
    <form action="{{ route('admin.branches.store') }}" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Tên chi nhánh <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                            placeholder="Nhập tên chi nhánh">
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
                        <label for="menu">Email <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="email" value="{{ old('email') }}"
                            placeholder="Nhập email">
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
                        <label for="menu">Quản lý <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="manager" value="{{ old('manager') }}"
                            placeholder="Nhập quản lý">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Khách hàng <span class="required">(*)</span></label>
                        <select class="form-control" name="user_id">
                            <option value="">--Khách hàng--</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->user_id }}">{{ $customer->name }}</option>
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
@endsection
