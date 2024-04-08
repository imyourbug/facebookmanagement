@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
    <script src="/js/customer/taskdetail/edit.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
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
                            {{-- <button class="btn btn-success mb-4 btn-open-modal" data-target="#modal-map"
                                data-toggle="modal">Thêm
                                mới</button> --}}
                            <table id="tableMap" class="table-map table display nowrap dataTable dtr-inline collapsed">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <th>Mã sơ đồ</th>
                                        <th>Vị trí</th>
                                        <th>Đối tượng</th>
                                        <th>Đơn vị</th>
                                        <th>KPI</th>
                                        <th>Kết quả</th>
                                        <th>Ảnh</th>
                                        <th>Chi tiết</th>
                                        {{-- <th>Thao tác</th> --}}
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
                                        {{-- <th>Thao tác</th> --}}
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
                                        {{-- <th>Thao tác</th> --}}
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
                                        {{-- <th>Thao tác</th> --}}
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
                                        {{-- <th>Thao tác</th> --}}
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
    <a href="{{ route('customers.tasks.detail', ['id' => $taskDetail->task_id]) }}" class="btn btn-danger"><i
            class="fa-solid fa-arrow-left"></i></a>
    <input type="hidden" name="" value="{{ $taskDetail->id }}" id="task_id">
    <input type="hidden" name="" value="" id="taskmap_id">
    <input type="hidden" name="" value="" id="taskstaff_id">
    <input type="hidden" name="" value="" id="taskchemistry_id">
    <input type="hidden" name="" value="" id="taskitem_id">
    <input type="hidden" name="" value="" id="tasksolution_id">
@endsection
