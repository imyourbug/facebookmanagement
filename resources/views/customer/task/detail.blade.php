@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
    <script src="/js/customer/task/detail.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
@endpush
@section('content')
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
    <input type="hidden" id="task_id" value="{{ request()->id }}" />
    <input type="hidden" id="taskdetail_id" />
@endsection
