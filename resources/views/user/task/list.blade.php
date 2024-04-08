@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script src="/js/user/task/index.js?v=1" type="text/javascript"></script>
@endpush
@section('content')
    <div class="card direct-chat direct-chat-primary">
        <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
            <h3 class="card-title text-bold">Nhập dữ liệu báo cáo</h3>
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
        </div>
    </div>
    <input type="hidden" id="user_id" value="{{ Auth::id() }}" />
@endsection
