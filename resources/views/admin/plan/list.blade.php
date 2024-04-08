@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
    <script>
        var dataTable = null;
        $(document).ready(function() {
            dataTable = $("#table").DataTable({
                ajax: {
                    url: "/api/contracts/getAll",
                    dataSrc: "contracts",
                },
                columns: [{
                        data: function(d) {
                            return `${$('#select-month').val() == 0 ? 'Tất cả' : $('#select-month').val()}`;
                        },
                    }, {
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
                        data: function(d) {
                            return `${d.branch ? (d.branch.address || "") : ""}`;
                        },
                    },

                    {
                        data: function(d) {
                            // return `<a class="btn btn-primary btn-sm" href='/admin/contracts/update/${d.id}'>
                            //             <i class="fas fa-edit"></i>
                            //         </a>
                            //         <a class="btn btn-success btn-sm" style="padding: 4px 15px"
                            //             href='/admin/contracts/detail/${d.id}'>
                            //             <i class="fa-solid fa-info"></i>
                            //         </a>
                            //         <button data-id="${d.id }" class="btn btn-danger btn-sm btn-delete">
                            //             <i class="fas fa-trash"></i>
                            //         </button>`;
                            return `<a class="btn btn-success btn-sm" style="padding: 4px 15px"
                                        href='/admin/contracts/detail/${d.id}'>
                                        <i class="fa-solid fa-info"></i>
                                    </a>`;
                        },
                    },
                ],
            });
        })

        $(document).on("click", ".btn-delete", function() {
            if (confirm("Bạn có muốn xóa")) {
                let id = $(this).data("id");
                $.ajax({
                    type: "DELETE",
                    url: `/api/contracts/${id}/destroy`,
                    success: function(response) {
                        if (response.status == 0) {
                            toastr.success("Xóa thành công");
                            dataTable.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });
            }
        });
        $(document).on("change", "#select-month", function() {
            let requestUrl = "/api/contracts/getAll?month=" + $(this).val();
            dataTable.ajax.url(requestUrl).load();
        });
    </script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
@endpush
@section('content')
    <div class="row ">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <a href="{{ route('admin.contracts.index') }}" class="btn btn-success">Thêm mới</a>
            <div class="form-group">
                <label for="">Tháng</label>
                <select name="" class="form-control" id="select-month">
                    <option value="">--Tất cả--</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">Tháng {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <table id="table" class="table display nowrap dataTable dtr-inline collapsed">
        <thead>
            <tr>
                <th>Tháng</th>
                <th>Tên công ty</th>
                <th>Tên chi nhánh</th>
                <th>Địa chỉ</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@endsection
