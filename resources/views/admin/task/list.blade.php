@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
    <script src="/js/admin/task/index.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
@endpush
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card direct-chat direct-chat-primary">
            <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                <h3 class="card-title text-bold">Thêm nhiệm vụ</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body form-contract" style="display: block;padding: 10px !important;">
                <form action="{{ route('admin.tasks.store') }}" method="POST">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="menu">Loại nhiệm vụ <span class="required">(*)</span></label>
                                    <select class="form-control" name="type_id">
                                        <option value="">--Loại nhiệm vụ--</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">
                                                {{ $type->id . '-' . $type->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="menu">Hợp đồng <span class="required">(*)</span></label>
                                    <select class="form-control select-contract" id="contract_id" name="contract_id">
                                        <option value="">--Hợp đồng--</option>
                                        @foreach ($contracts as $contract)
                                            <option value="{{ $contract->id }}">
                                                {{ $contract->name . '-' . ($contract->branch->name ?? '') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label for="menu">Ghi chú</label>
                                    <textarea placeholder="Nhập ghi chú..." class="form-control" name="note" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" class="id-branch">
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card direct-chat direct-chat-primary">
            <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                <h3 class="card-title text-bold">Danh sách nhiệm vụ</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="display: block;padding: 10px !important;">
                <div class="mb-3">
                    <input class="" style="" type="date" id="from"
                        value="{{ Request::get('from') ?? now()->format('Y-m-01') }}" />
                    <input class="" style="" type="date" id="to"
                        value="{{ Request::get('to') ?? now()->format('Y-m-t') }}" />
                    <button class="btn btn-warning btn-filter">Lọc</button>
                </div>
                <table id="table" class="table display nowrap dataTable dtr-inline collapsed">
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th>Nhiệm vụ</th>
                            <th>Hợp đồng</th>
                            <th>Ghi chú</th>
                            <th>Ngày lập</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="modal fade" id="modal" style="display: none;" aria-modal="true" role="dialog">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title modal-title">Cập nhật nhiệm vụ</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="menu">Loại nhiệm vụ <span class="required">(*)</span></label>
                                            <select class="form-control" id="type_id">
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id }}">
                                                        {{ $type->id . '-' . $type->name ?? '' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="menu">Hợp đồng</label>
                                            <select class="form-control select-contract" id="contract_id">
                                                <option value="">--Hợp đồng--</option>
                                                @foreach ($contracts as $contract)
                                                    <option value="{{ $contract->id }}">
                                                        {{ $contract->name . '-' . ($contract->branch->name ?? '') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="menu">Ghi chú <span class="required">(*)</span></label>
                                            <textarea placeholder="Nhập ghi chú..." class="form-control" id="note" cols="30" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                <button type="button" data-url="{{ route('tasks.update') }}"
                                    class="btn btn-primary btn-update">Lưu</button>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="task_id">
                <input type="hidden" id="request_type_id" value="{{ request()->type_id }}">
            </div>
        </div>
    </div>
</div>
@endsection
