@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
    <script src="/js/admin/report/task/index.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
@endpush
@section('content')
    <div class="mb-3">
        {{-- <input class="" style="" type="date" name="from"
            value="{{ Request::get('from') ?? now()->format('Y-m-01') }}" />
        <input class="" style="" type="date" name="to"
            value="{{ Request::get('to') ?? now()->format('Y-m-t') }}" />
        <button class="btn btn-warning btn-filter" type="submit">Lọc</button> --}}
        {{-- <button class="btn btn-success btn-open-modal" data-target="#modal" data-toggle="modal">Thêm
            mới</button> --}}
    </div>
    <table id="table" class="table display nowrap dataTable dtr-inline collapsed">
        <thead>
            <tr>
                <!-- <th>ID</th> -->
                <th>Nhiệm vụ</th>
                <th>Ngày kế hoạch</th>
                <th>Ngày thực hiện</th>
                <th>Giờ vào</th>
                <th>Giờ ra</th>
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
                    <h4 class="modal-title modal-title">Cập nhật chi tiết nhiệm vụ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Ngày kế hoạch <span class="required">(*)</span></label>
                                <input type="date" id="plan_date" class="form-control" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Ngày thực hiện</label>
                                <input type="date" id="actual_date" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Giờ vào <span class="required">(*)</span></label>
                                <input type="text" id="time_in" class="form-control" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Giờ ra <span class="required">(*)</span></label>
                                <input type="text" id="time_out" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" data-url="{{ route('taskdetails.store') }}"
                        class="btn btn-primary btn-add">Lưu</button>
                    <button style="display: none" type="button" data-url="{{ route('taskdetails.update') }}"
                        class="btn btn-primary btn-update">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-danger mt-3"><i class="fa-solid fa-arrow-left"></i></a>
    <input type="hidden" id="task_id" value="{{ request()->id }}" />
    <input type="hidden" id="taskdetail_id" />
@endsection
