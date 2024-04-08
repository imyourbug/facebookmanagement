@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
    <script src="/js/admin/taskdetail/edit.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script></script>
@endpush
@section('content')
    <div class="">
        <a href="{{ route('admin.tasks.detail', ['id' => $taskDetail->task->id]) }}" class="btn btn-danger">Quay lại</a>
        <a class="btn btn-warning" href="{{ route('admin.settings.reload', ['taskdetail_id' => request()->id]) }}"
            onclick="return confirm('Xác nhận tải lại cài đặt cho nhiệm vụ?')">Tải lại cài đặt</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <label for="">Nhiệm vụ</label>
                <p class="form-control">{{ $taskDetail->task->type->name }}</p>
            </div>
            <div class="col-lg-6 col-md-12">
                <label for="">Phạm vi</label>
                <p class="form-control">{{ $taskDetail->range }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <label for="">Giờ vào</label>
                <p class="form-control">{{ $taskDetail->time_in }}</p>
            </div>
            <div class="col-lg-6 col-md-12">
                <label for="">Giờ ra</label>
                <p class="form-control">{{ $taskDetail->time_out }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <label for="">Ngày kế hoạch</label>
                <p class="form-control">{{ date('d-m-Y', strtotime($taskDetail->plan_date)) }}</p>

            </div>
            <div class="col-lg-6 col-md-12">
                <label for="">Ngày thực hiện</label>
                <p class="form-control">
                    {{ !$taskDetail->actual_date ? '' : date('d-m-Y', strtotime($taskDetail->actual_date)) }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                                href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home"
                                aria-selected="false">Sơ đồ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile"
                                aria-selected="false">Nhân sự</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-items-tab" data-toggle="pill"
                                href="#custom-tabs-four-items" role="tab" aria-controls="custom-tabs-four-items"
                                aria-selected="false">Vật tư</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-chemistries-tab" data-toggle="pill"
                                href="#custom-tabs-four-chemistries" role="tab"
                                aria-controls="custom-tabs-four-chemistries" aria-selected="true">Hóa chất</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-solutions-tab" data-toggle="pill"
                                href="#custom-tabs-four-solutions" role="tab" aria-controls="custom-tabs-four-solutions"
                                aria-selected="true">Phương pháp</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        {{-- Map --}}
                        <div class="tab-pane active show fade" id="custom-tabs-four-home" role="tabpanel"
                            aria-labelledby="custom-tabs-four-home-tab">
                            <button class="btn btn-success mb-4 btn-open-modal" data-target="#modal-map"
                                data-toggle="modal">Thêm
                                mới</button>
                            <table id="tableMap" class="table-map table display nowrap dataTable dtr-inline collapsed">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <th>Mã sơ đồ</th>
                                        <th>Vị trí</th>
                                        <th>Đối tượng</th>
                                        <th>Đơn vị</th>
                                        <th>KPI</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        {{-- Staff --}}
                        <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                            aria-labelledby="custom-tabs-four-profile-tab">
                            <button class="btn btn-success mb-4 btn-open-modal-staff" data-target="#modal-staff"
                                data-toggle="modal">Thêm
                                mới</button>
                            <table id="tableStaff" class="table-staff table display nowrap dataTable dtr-inline collapsed">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <th>Mã nhân viên</th>
                                        <th>Họ tên</th>
                                        <th>Chức vụ</th>
                                        <th>Số điện thoại</th>
                                        <th>CCCD</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        {{-- Item --}}
                        <div class="tab-pane fade" id="custom-tabs-four-items" role="tabpanel"
                            aria-labelledby="custom-tabs-four-items-tab">
                            <button class="btn btn-success mb-4 btn-open-modal-item" data-target="#modal-item"
                                data-toggle="modal">Thêm
                                mới</button>
                            <table id="tableItem" class="table-item table display nowrap dataTable dtr-inline collapsed">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <th>Tên</th>
                                        <th>Đơn vị</th>
                                        <th>KPI</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        {{-- Chemistry --}}
                        <div class="tab-pane fade" id="custom-tabs-four-chemistries" role="tabpanel"
                            aria-labelledby="custom-tabs-four-chemistries-tab">
                            <button class="btn btn-success mb-4 btn-open-modal-chemistry" data-target="#modal-chemistry"
                                data-toggle="modal">Thêm
                                mới</button>
                            <table id="tableChemistry"
                                class="table-chemistry table display nowrap dataTable dtr-inline collapsed">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <th>Tên</th>
                                        <th>Đơn vị</th>
                                        <th>KPI</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        {{-- Solution --}}
                        <div class="tab-pane fade" id="custom-tabs-four-solutions" role="tabpanel"
                            aria-labelledby="custom-tabs-four-solutions-tab">
                            <button class="btn btn-success mb-4 btn-open-modal-solution" data-target="#modal-solution"
                                data-toggle="modal">Thêm
                                mới</button>
                            <table id="tableSolution"
                                class="table-solution table display nowrap dataTable dtr-inline collapsed">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <th>Tên</th>
                                        <th>Đơn vị</th>
                                        <th>KPI</th>
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
        </div>
    </div>
    {{-- add solution --}}
    <div class="modal fade" id="modal-solution" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title-solution">Thêm phương pháp</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="">
                    <div class="">
                        <div class=""></div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Phương pháp</label>
                                <select class="form-control" name="" id="solution_id">
                                    @foreach ($solutions as $solution)
                                        <option value="{{ $solution->id }}">
                                            {{ $solution->id . '-' . $solution->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Đơn vị</label>
                                <input class="form-control" type="text" id="solution_unit" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">KPI</label>
                                <input class="form-control" type="text" id="solution_kpi" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" data-url="{{ route('tasksolutions.store') }}"
                        class="btn btn-primary btn-add-solution">Lưu</button>
                    <button style="display: none" type="button" data-url="{{ route('tasksolutions.update') }}"
                        class="btn btn-primary btn-update-solution">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    {{-- add item --}}
    <div class="modal fade" id="modal-item" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title-item">Thêm vật tư</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="">
                    <div class="">
                        <div class=""></div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Vật tư</label>
                                <select class="form-control" name="" id="item_id">
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->id . '-' . $item->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Đơn vị</label>
                                <input class="form-control" type="text" id="item_unit" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">KPI</label>
                                <input class="form-control" type="text" id="item_kpi" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" data-url="{{ route('taskitems.store') }}"
                        class="btn btn-primary btn-add-item">Lưu</button>
                    <button style="display: none" type="button" data-url="{{ route('taskitems.update') }}"
                        class="btn btn-primary btn-update-item">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    {{-- add map --}}
    <div class="modal fade" id="modal-map" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title-map">Thêm sơ đồ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Sơ đồ</label>
                                <select class="form-control" name="" id="map_id">
                                    @foreach ($maps as $map)
                                        <option value="{{ $map->id }}">
                                            {{ $map->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Chỉ số</label>
                                <input class="form-control" type="text" id="unit" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">KPI</label>
                                <input class="form-control" type="text" id="kpi" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" data-url="{{ route('taskmaps.store') }}"
                        class="btn btn-primary btn-add-map">Lưu</button>
                    <button style="display: none" type="button" data-url="{{ route('taskmaps.update') }}"
                        class="btn btn-primary btn-update-map">Lưu</button>
                </div>
            </div>

        </div>
    </div>
    {{-- add staff --}}
    <div class="modal fade" id="modal-staff" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title-staff">Thêm nhân sự</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Nhân sự</label>
                                <select class="form-control" name="" id="staff_id">
                                    @foreach ($staffs as $staff)
                                        <option value="{{ $staff->id }}">
                                            {{ $staff->id . '-' . $staff->staff->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" data-url="{{ route('taskstaff.store') }}"
                        class="btn btn-primary btn-add-staff">Lưu</button>
                    <button style="display: none" type="button" data-url="{{ route('taskstaff.update') }}"
                        class="btn btn-primary btn-update-staff">Lưu</button>
                </div>
            </div>

        </div>
    </div>
    {{-- add chemistry --}}
    <div class="modal fade" id="modal-chemistry" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title-chemistry">Thêm hóa chất</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="">
                    <div class="">
                        <div class=""></div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Hóa chất</label>
                                <select class="form-control" name="" id="chemistry_id">
                                    @foreach ($chemistries as $chemistry)
                                        <option value="{{ $chemistry->id }}">
                                            {{ $chemistry->id . '-' . $chemistry->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Đơn vị</label>
                                <input class="form-control" type="text" id="chemistry_unit" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">KPI</label>
                                <input class="form-control" type="text" id="chemistry_kpi" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" data-url="{{ route('taskchemistries.store') }}"
                        class="btn btn-primary btn-add-chemistry">Lưu</button>
                    <button style="display: none" type="button" data-url="{{ route('taskchemistries.update') }}"
                        class="btn btn-primary btn-update-chemistry">Lưu</button>
                </div>
            </div>

        </div>
    </div>
    <input type="hidden" name="" value="{{ $taskDetail->id }}" id="task_id">
    <input type="hidden" name="" value="" id="taskmap_id">
    <input type="hidden" name="" value="" id="taskstaff_id">
    <input type="hidden" name="" value="" id="taskchemistry_id">
    <input type="hidden" name="" value="" id="taskitem_id">
    <input type="hidden" name="" value="" id="tasksolution_id">
@endsection
