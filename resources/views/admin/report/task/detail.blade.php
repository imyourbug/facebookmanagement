@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
    <script src="/js/admin/report/task/detail.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script>
        $(".upload").change(function() {
            const form = new FormData();
            form.append("file", $(this)[0].files[0]);
            let type = $(this).data('type');
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
                        $("#image_show_" + type).attr('src', response.url);
                        $("#image_" + type).val(response.url);
                    } else {
                        toastr.error(response.message, 'Thông báo');
                    }
                },
            });
        });
    </script>
@endpush
@section('content')
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
                            <div class="mb-4">
                                <a href="{{ route('admin.reports.reload', ['id' => request()->id]) }}"
                                    onclick="return confirm('Bạn có chắc muốn tải lên kết quả mẫu?')"
                                    class="btn btn-success btn-open-modal">Tải lên kết quả mẫu
                                </a>
                                <a class="btn btn-warning"
                                    href="{{ route('admin.settings.reload', ['taskdetail_id' => request()->id]) }}"
                                    onclick="return confirm('Xác nhận tải lại cài đặt cho nhiệm vụ?')">Tải lại cài đặt
                                </a>
                            </div>
                            <table id="tableMap" class="table-map table display nowrap dataTable dtr-inline collapsed">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <th><input type="checkbox" class="select-id-map-all" />
                                            <button class="btn btn-danger btn-sm btn-delete-map-all" style="display: none">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </th>
                                        <th>Mã sơ đồ</th>
                                        <th>Vị trí</th>
                                        <th>Đối tượng</th>
                                        <th>Đơn vị</th>
                                        <th>KPI</th>
                                        <th>Kết quả</th>
                                        <th>Ảnh</th>
                                        <th>Chi tiết</th>
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
                            {{-- <button class="btn btn-success mb-4 btn-open-modal-staff" data-target="#modal-staff"
                                data-toggle="modal">Thêm
                                mới</button> --}}
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
                            {{-- <button class="btn btn-success mb-4 btn-open-modal-item" data-target="#modal-item"
                                data-toggle="modal">Thêm
                                mới</button> --}}
                            <table id="tableItem" class="table-item table display nowrap dataTable dtr-inline collapsed">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <th>Tên</th>
                                        <th>Đơn vị</th>
                                        <th>KPI</th>
                                        <th>Kết quả</th>
                                        <th>Ảnh</th>
                                        <th>Chi tiết</th>
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
                            {{-- <button class="btn btn-success mb-4 btn-open-modal-chemistry" data-target="#modal-chemistry"
                                data-toggle="modal">Thêm
                                mới</button> --}}
                            <table id="tableChemistry"
                                class="table-chemistry table display nowrap dataTable dtr-inline collapsed">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <th>Tên</th>
                                        <th>Đơn vị</th>
                                        <th>KPI</th>
                                        <th>Kết quả</th>
                                        <th>Ảnh</th>
                                        <th>Chi tiết</th>
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
                            {{-- <button class="btn btn-success mb-4 btn-open-modal-solution" data-target="#modal-solution"
                                data-toggle="modal">Thêm
                                mới</button> --}}
                            <table id="tableSolution"
                                class="table-solution table display nowrap dataTable dtr-inline collapsed">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <th>Tên</th>
                                        <th>Đơn vị</th>
                                        <th>KPI</th>
                                        <th>Kết quả</th>
                                        <th>Ảnh</th>
                                        <th>Chi tiết</th>
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
    <a href="{{ route('admin.reports.task', ['id' => $taskDetail->task_id]) }}" class="btn btn-danger"><i
            class="fa-solid fa-arrow-left"></i></a>

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
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Phương pháp <span class="required">(*)</span></label>
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
                                <label for="menu">Đơn vị <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="solution_unit" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">KPI <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="solution_kpi" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Kết quả <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="solution_result" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="file">Chọn ảnh <span class="required">(*)</span></label><br>
                                <div class="">
                                    <img id="image_show_solution" style="width: 100px;height:100px" src=""
                                        alt="image" />
                                    <input type="file" class="upload" data-type="solution" accept=".png,.jpeg" />
                                </div>
                                <input type="hidden" id="image_solution" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Chi tiết</label>
                                <textarea placeholder="Nhập chi tiết..." class="form-control" id="solution_detail" cols="30" rows="5"></textarea>
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
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Vật tư <span class="required">(*)</span></label>
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
                                <label for="menu">Đơn vị <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="item_unit" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">KPI <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="item_kpi" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Kết quả <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="item_result" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="file">Chọn ảnh <span class="required">(*)</span></label><br>
                                <div class="">
                                    <img id="image_show_item" style="width: 100px;height:100px" src=""
                                        alt="image" />
                                    <input type="file" class="upload" data-type="item" accept=".png,.jpeg" />
                                </div>
                                <input type="hidden" id="image_item" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Chi tiết</label>
                                <textarea placeholder="Nhập chi tiết..." class="form-control" id="item_detail" cols="30" rows="5"></textarea>
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
                        {{-- <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Sơ đồ <span class="required">(*)</span></label>
                                <select class="form-control" name="" id="map_id">
                                    @foreach ($maps as $map)
                                        <option value="{{ $map->id }}">{{ $map->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Mã sơ đồ <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="code" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Chỉ số <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="unit" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">KPI <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="kpi" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Kết quả <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="result" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="file">Chọn ảnh <span class="required">(*)</span></label><br>
                                <div class="">
                                    <img id="image_show_map" style="width: 100px;height:100px" src=""
                                        alt="image" />
                                    <input type="file" class="upload" data-type="map" accept=".png,.jpeg" />
                                </div>
                                <input type="hidden" id="image_map" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Chi tiết</label>
                                <textarea placeholder="Nhập chi tiết..." class="form-control" id="detail" cols="30" rows="5"></textarea>
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
                                <label for="menu">Nhân sự <span class="required">(*)</span></label>
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
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Hóa chất <span class="required">(*)</span></label>
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
                                <label for="menu">Đơn vị <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="chemistry_unit" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">KPI <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="chemistry_kpi" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Kết quả <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="chemistry_result" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="file">Chọn ảnh <span class="required">(*)</span></label><br>
                                <div class="">
                                    <img id="image_show_chemistry" style="width: 100px;height:100px" src=""
                                        alt="image" />
                                    <input type="file" class="upload" data-type="chemistry" accept=".png,.jpeg" />
                                </div>
                                <input type="hidden" id="image_chemistry" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Chi tiết</label>
                                <textarea placeholder="Nhập chi tiết..." class="form-control" id="chemistry_detail" cols="30" rows="5"></textarea>
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
