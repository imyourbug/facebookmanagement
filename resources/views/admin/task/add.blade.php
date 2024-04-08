@extends('admin.main')
@push('styles')
@endpush
@push('scripts')
@endpush
@section('content')
    <form action="{{ route('admin.tasks.store') }}" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Loại nhiệm vụ <span class="required">(*)</span></label>
                        <select class="form-control" name="type_id">
                            <option value="">--Loại nhiệm vụ--</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">
                                    {{ $type->id . '-' . $type->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Hợp đồng <span class="required">(*)</span></label>
                        <select class="form-control select-contract" id="contract_id" name="contract_id">
                            <option value="">--Hợp đồng--</option>
                            @foreach ($contracts as $contract)
                                <option value="{{ $contract->id }}">
                                    {{ $contract->name . '-' . ($contract->branch->name ?? '') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group">
                        <label for="menu">Ghi chú</label>
                        <textarea placeholder="Nhập ghi chú..." class="form-control" name="note" cols="30" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.tasks.index') }}" class="btn btn-success">Xem danh sách</a>
        </div>
        @csrf
    </form>
@endsection
