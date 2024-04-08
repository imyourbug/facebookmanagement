@extends('admin.main')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
<script>
    function closeModal() {
        $("#modal").css("display", "none");
        $("body").removeClass("modal-open");
        $(".modal-backdrop").remove();
    }
    var dataTable = null;
    var dataTableBranch = null;
    var dataTableTaskDetail = null;
    $(document).ready(function() {
        dataTable = $("#table").DataTable({
            ajax: {
                url: "/api/contracts/getAll",
                dataSrc: "contracts",
            },
            columns: [
                // {
                //     data: "id"
                // },
                {
                    data: function(d) {
                        return `${d.customer.name}`;
                    },
                },
                {
                    data: function(d) {
                        return `${d.branch ? d.branch.name : ""}`;
                    },
                },
                {
                    data: "start"
                },
                {
                    data: "finish"
                },
                {
                    data: "content"
                },
                {
                    data: function(d) {
                        return `${getStatusContract(d.finish)}`;
                    },
                },
                {
                    data: function(d) {
                        return `<a class="btn btn-primary btn-sm" href='/admin/contracts/detail/${d.id}'>
                                    <i class="fas fa-edit"></i>
                                </a>`;
                    },
                },
            ],
        });
        dataTableBranch = $("#tableBranch").DataTable({
            ajax: {
                url: "/admin/branches",
                dataSrc: "branches",
            },
            columns: [
                // {
                //     data: "id"
                // },
                {
                    data: "name"
                },
                {
                    data: "address"
                },
                {
                    data: "tel"
                },
                {
                    data: "email"
                },
                {
                    data: "manager"
                },
                {
                    data: function(d) {
                        return d.user.customer.name;
                    },
                },
                {
                    data: function(d) {
                        return `<a class="btn btn-primary btn-sm" href='/admin/branches/update/${d.id}'>
                                        <i class="fas fa-edit"></i>
                                    </a>`;
                    },
                },
            ],
        });
        dataTableTaskDetail = $("#tableTaskDetail").DataTable({
            ajax: {
                url: "/api/taskdetails",
                dataSrc: "taskDetails",
            },
            columns: [
                // {
                //     data: "id"
                // },
                {
                    data: "task.type.name"
                },
                // { data: function (d) {
                //     return `${formatDate(d.plan_date)}`
                // } },
                {
                    data: "plan_date"
                },
                {
                    data: "actual_date"
                },
                {
                    data: "time_in"
                },
                {
                    data: "time_out"
                },
                {
                    data: "created_at"
                },
                {
                    data: function(d) {
                        return `<a class="btn btn-primary btn-sm btn-edit" href="/admin/taskdetails/update/${d.id}">
                                        <i class="fas fa-edit"></i>
                                    </a>`;
                    },
                },
            ],
        });
    })
    $(document).on('click', '.btn-change-password', function() {
        $.ajax({
            type: "POST",
            data: {
                tel_or_email: $('#tel_or_email').val(),
                password: $('#password').val(),
                old_password: $('#old_password').val(),
            },
            url: "/api/users/change_password",
            success: function(response) {
                if (response.status == 0) {
                    toastr.success(response.message, "Thông báo");
                    closeModal();
                } else {
                    toastr.error(response.message, "Thông báo");
                }
            },
        });
    })
    //
    $(document).on("change", "#select-month-contract", function() {
        let requestUrl = "/api/contracts/getAll?month=" + $(this).val();
        dataTable.ajax.url(requestUrl).load();
    });
    $(document).on("change", "#select-month-taskdetail", function() {
        let requestUrl = "/api/taskdetails?month=" + $(this).val();
        dataTableTaskDetail.ajax.url(requestUrl).load();
    });
</script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
@endpush
@section('content')
<div class="card direct-chat direct-chat-primary">
    <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
        <h3 class="card-title text-bold">DANH SÁCH THEO DÕI HỢP ĐỒNG - KHÁCH HÀNG</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body" style="display: block;padding: 10px !important;">
        <div class="row ">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="">Tháng</label>
                    <select name="" class="form-control" id="select-month-contract">
                        <option value="">--Tất cả--</option>
                        @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}">Tháng {{ $i }}</option>
                            @endfor
                    </select>
                </div>
            </div>
        </div>
        <table id="table" class="table display nowrap dataTable dtr-inline collapsed">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Khách hàng</th>
                    <th>Chi nhánh</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Nội dung</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="card direct-chat direct-chat-primary">
    <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
        <h3 class="card-title text-bold">CHI NHÁNH - ĐỊA ĐIỂM ÁP DỤNG</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body" style="display: block;padding: 10px !important;">
        <table id="tableBranch" class="table display nowrap dataTable dtr-inline collapsed">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Tên chi nhánh</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Quản lý</th>
                    <th>Khách hàng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="card direct-chat direct-chat-primary">
    <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
        <h3 class="card-title text-bold">CHI TIẾT</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body" style="display: block;padding: 10px !important;">
        <div class="row ">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="">Tháng</label>
                    <select name="" class="form-control" id="select-month-taskdetail">
                        <option value="">--Tất cả--</option>
                        @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}">Tháng {{ $i }}</option>
                            @endfor
                    </select>
                </div>
            </div>
        </div>
        <table id="tableTaskDetail" class="table display nowrap dataTable dtr-inline collapsed">
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
    </div>
</div>
@endsection