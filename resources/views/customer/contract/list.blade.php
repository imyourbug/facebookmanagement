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
                    url: "/api/contracts/getAll?customer_id=" + $('#customer_id').val(),
                    dataSrc: "contracts",
                },
                columns: [{
                        data: "id"
                    },
                    {
                        data: "name"
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
                            return `${d.customer.name}`;
                        },
                    },
                    {
                        data: function(d) {
                            return `${getStatusContract(d.finish)}`;
                        },
                    },
                    {
                        data: function(d) {
                            return `<a class="btn btn-success btn-sm" style="padding: 4px 15px"
                                        href='/customer/contracts/detail/${d.id}'>
                                        <i class="fa-solid fa-info"></i>
                                    </a>`;
                        },
                    },
                ],
            });
        })
    </script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
@endpush
@section('content')
    <table id="table" class="table display nowrap dataTable dtr-inline collapsed">
        <thead>
            <tr>
                <!-- <th>ID</th> -->
                <th>Tên hợp đồng</th>
                <th>Chi nhánh</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Nội dung</th>
                <th>Khách hàng</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <input type="hidden" id="customer_id" value="{{ $customer->id }}" />
@endsection
