@extends('admin.main')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@push('scripts')
    <script src="/js/admin/contract/index.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
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
    <div class="">
        <div class="card-body form-contract">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Tên hợp đồng</label>
                        <input class="form-control" type="text" id="name" placeholder="Nhập tên hợp đồng..." />
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Khách hàng</label>
                        <select class="form-control select-customer" data-url="{{ route('branches.getBranchById') }}"
                            id="customer_id">
                            <option value="">--Khách hàng--</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Ngày bắt đầu</label>
                        <input type="date" class="form-control" id="start"
                            value="{{ old('start') ?? now()->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Ngày kết thúc</label>
                        <input type="date" class="form-control" id="finish"
                            value="{{ old('finish') ?? now()->format('Y-m-d') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Nội dung</label>
                        <textarea placeholder="Nhập nội dung..." class="form-control" id="content" cols="30" rows="5">{{ old('content') }}</textarea>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group form-attachment">
                        <label class="notification" for="menu">Tệp đính kèm</label>
                        <div class="">
                            <input type="file" id="attachment">
                            <input type="hidden" id="value-attachment">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div class="card-footer">
            <button class="btn btn-primary btn-create" data-url="{{ route('contracts.store') }}">Lưu</button>
            <a href="{{ route('admin.contracts.index') }}" class="btn btn-success">Xem danh sách</a>
        </div>
    </div>
    <div class="modal fade" id="modal-task" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Lựa chọn nhiệm vụ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="menu">Loại nhiệm vụ</label>
                            <div class="form-group">
                                <select class="form-control select-parent-type"
                                    data-url="{{ route('types.getTypeByParentId') }}" id="parent_type_id">
                                    <option value="">--Loại nhiệm vụ--</option>
                                    @foreach ($parent_types as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="menu">Nhiệm vụ</label>
                            <div class="form-group form-type">
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="id_electask">
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary btn-save">Lưu</button>
                </div>
            </div>

        </div>
    </div>
    <input type="hidden" class="id-branch">
@endsection
