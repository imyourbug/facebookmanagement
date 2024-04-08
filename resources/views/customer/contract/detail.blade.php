@extends('admin.main')
@push('styles')
@endpush
@push('scripts')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                responsive: true
            });

        });
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <label for="menu">Tên hợp đồng</label>
                            <p class="form-control">
                                {{ $contract->name }}
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <label for="menu">Khách hàng</label>
                            <p class="form-control">
                                {{ $contract->customer->name }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="menu">Chi nhánh</label>
                            <p class="form-control">
                                {{ $contract->branch->name ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <label for="menu">Ngày bắt đầu</label>
                            <p class="form-control">
                                {{ $contract->start }}
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <label for="menu">Ngày kết thúc</label>
                            <p class="form-control">
                                {{ $contract->finish }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="menu">Nội dung</label>
                            <textarea placeholder="Nhập nội dung..." class="form-control" name="content" cols="30" rows="5">{{ old('content') ?? $contract->content }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group form-attachment">
                            <label class="notification" for="menu">Tệp đính kèm</label>&emsp13;
                            @if ($contract->attachment)
                                <a href="{{ $contract->attachment }}" target="_blank">Xem</a>
                            @else
                                Trống
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card direct-chat direct-chat-primary">
        <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
            <h3 class="card-title text-bold">Nhiệm vụ</h3>
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
                        <th>Ghi chú</th>
                        <th>Ngày lập</th>
                        <th>Thao tác</th>
                    </tr>
                <tbody>
                    @foreach ($contract->tasks as $task)
                        <tr class="row{{ $task->id }}">
                            <th>{{ $task->id }}</th>
                            <th>{{ $task->type->name }}</th>
                            <td>{{ $task->note ?? 'Trống' }}</td>
                            <td>{{ date('d-m-Y', strtotime($task->created_at)) }}</td>
                            <td><a class="btn btn-success btn-sm" style="padding: 4px 15px"
                                    href='{{ route('customers.tasks.detail', ['id' => $task->id]) }}'>
                                    <i class="fa-solid fa-info"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                </thead>
            </table>
        </div>
    </div>
@endsection
