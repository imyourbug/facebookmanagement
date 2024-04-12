@extends('admin.main')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
@endpush
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card direct-chat direct-chat-primary">
            <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                <h3 class="card-title text-bold">Cài đặt</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="display: block;padding: 10px !important;">
                
            </div>
        </div>
    </div>
</div>
@endsection
