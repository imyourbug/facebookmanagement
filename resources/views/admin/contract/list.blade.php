@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@push('scripts')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.0/js/dataTables.select.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.0/js/select.dataTables.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdn.datatables.net/keytable/2.12.0/js/dataTables.keyTable.js"></script>
    <script src="https://cdn.datatables.net/keytable/2.12.0/js/keyTable.dataTables.js"></script>
    <script src="/js/admin/contract/index.js?v=1"></script>
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
                    if (response.status == 0) {
                        toastr.success('Tải lên thành công', 'Thông báo');
                        $("#value-attachment").val(response.url);
                    } else {
                        toastr.error(response.message, 'Thông báo');
                    }
                },
            });
        });
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                    <h3 class="card-title text-bold">Thêm hợp đồng</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body form-contract" style="display: block;padding: 10px !important;">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Khách hàng <span class="required">(*)</span></label>
                                <select class="form-control select-customer"
                                    data-url="{{ route('branches.getBranchById') }}" id="customer_id">
                                    <option value="">--Khách hàng--</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Tên hợp đồng <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="name"
                                    placeholder="Nhập tên hợp đồng..." />
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Chi nhánh</label>
                                <select multiple="multiple" id="branch_id"
                                    class="select2 custom-select form-control-border select-branch">
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Nội dung</label>
                                <input placeholder="Nhập nội dung..." class="form-control" id="content"
                                    value="{{ old('content') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Ngày bắt đầu <span class="required">(*)</span></label>
                                <input type="date" class="form-control" id="start"
                                    value="{{ old('start', now()->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Ngày kết thúc <span class="required">(*)</span></label>
                                <input type="date" class="form-control" id="finish"
                                    value="{{ old('finish', now()->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Nhiệm vụ <span class="required">(*)</span></label>
                                <button data-id="" type="button" class="btn btn-success btn-open-modal"
                                    data-target="#modal-task" data-toggle="modal">
                                    <i class="fa-solid fa-plus"></i></button>
                                <div class="info-task">
                                </div>
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
                {{-- <div class="card-body form-contract" style="display: block;padding: 10px !important;">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Tên hợp đồng</label>
                                <input class="form-control" type="text" id="name"
                                    placeholder="Nhập tên hợp đồng..." />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Khách hàng</label>
                                <select class="form-control select-customer"
                                    data-url="{{ route('branches.getBranchById') }}" id="customer_id">
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
                                <label class="notification" for="attachment">Tệp đính
                                    kèm</label>
                                <div class="">
                                    <input type="file" id="attachment"
                                        accept=".png,.jpeg,.pdf,.docx,.ppt,.cad,.xlsx,.csv" />
                                    <input type="hidden" id="value-attachment">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="card-footer">
                    <button class="btn btn-primary btn-create" data-url="{{ route('contracts.store') }}">Lưu</button>
                </div>
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
                    <div class="modal-body modal-option-task">
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
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                    <h3 class="card-title text-bold">Danh sách hợp đồng</h3>
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
                                <th>Tên hợp đồng</th>
                                <th>Chi nhánh</th>
                                <th>Nội dung</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
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
