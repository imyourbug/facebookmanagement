@extends('admin.main')
@push('styles')
@endpush
@push('scripts')
    {{-- <script src="/js/admin/contract/index.js"></script> --}}
    <script>
        $("#attachment").change(function() {
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
                    var html = 'Tệp đính kèm ';
                    if (response.status == 0) {
                        $("#value-attachment").val(response.url);
                        html += '<i class="fa-solid fa-check btn-success icon"></i>';
                    } else {
                        html += '<i class="fa-solid fa-x btn-danger icon"></i>';
                    }
                    $('.notification').html('');
                    $('.notification').append(html);
                },
            });
        });
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <form action="{{ route('admin.contracts.update', ['id' => $contract->id]) }}" method="POST" class="form-contract">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Tên hợp đồng <span class="required">(*)</span></label>
                                <input class="form-control" type="text" name="name"
                                    value="{{ old('name') ?? $contract->name }}" placeholder="Nhập tên hợp đồng..." />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Khách hàng</label>
                                <select class="form-control" name="customer_id">
                                    <option value="">--Khách hàng--</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ $customer->id == $contract->customer_id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Chi nhánh</label>
                                <input class="form-control" type="text" disabled
                                    value="{{ $contract->branch->name ?? '' }}" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Ngày bắt đầu</label>
                                <input type="date" class="form-control" id="start" name="start"
                                    value="{{ old('start') ?? $contract->start }}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Ngày kết thúc</label>
                                <input type="date" class="form-control" id="finish" name="finish"
                                    value="{{ old('finish') ?? $contract->finish }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Nội dung</label>
                                <textarea placeholder="Nhập nội dung..." class="form-control" name="content" cols="30" rows="5">{{ old('content') ?? $contract->content }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group form-attachment">
                                <label class="notification" for="menu">Tệp đính kèm</label>
                                <div class="">
                                    <input type="file" id="attachment">
                                    <input type="hidden" name="attachment" id="value-attachment" value="{{ $contract->attachment }}">
                                </div>
                            </div>
                            @if ($contract->attachment)
                                <a href="{{ $contract->attachment }}" target="_blank">Xem</a>
                            @else
                                Trống
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-create">Lưu</button>
                    <a href="{{ route('admin.contracts.index') }}" class="btn btn-success">Xem danh sách</a>
                </div>
            </form>
        </div>
    </div>
@endsection
