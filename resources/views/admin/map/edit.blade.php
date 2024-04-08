@extends('admin.main')
@push('styles')
@endpush
@push('scripts')
    <script>
        $("#upload").change(function() {
            const form = new FormData();
            form.append("file", $(this)[0].files[0]);
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
                        $("#image_show").attr('src', response.url);
                        $("#image").val(response.url);
                    } else {
                        toastr.error(response.message, 'Thông báo');
                    }
                },
            });
        });
    </script>
@endpush
@section('content')
    <form action="{{ route('admin.maps.update', ['id' => $map->id]) }}" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Mã sơ đồ</label>
                        <input type="text" class="form-control" name="code" value="{{ old('code') ?? $map->code }}"
                            placeholder="Nhập mã sơ đồ">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Vị trí</label>
                        <input type="text" class="form-control" name="position"
                            value="{{ old('position') ?? $map->position }}" placeholder="Nhập vị trí">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="menu">Mô tả</label>
                        <textarea class="form-control" name="description" id="" cols="30" rows="5">{{ $map->description }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Phạm vi</label>
                        <input type="text" class="form-control" name="range" value="{{ old('range') ?? $map->range }}"
                            placeholder="Nhập phạm vi">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="file">Chọn ảnh</label><br>
                        <div class="">
                            <img id="image_show" style="width: 100px;height:100px" src="{{ old('image') ?? $map->image }}"
                                alt="image" />
                            <input type="file" id="upload" accept=".png,.jpeg"/>
                        </div>
                        <input type="hidden" name="image" id="image" value="{{ old('image') ?? $map->image }}">
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label>Hiệu lực</label>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="active" value="1" name="active"
                                {{ $map->active == 1 ? 'checked' : '' }}>
                            <label for="active" class="custom-control-label">Có</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="unactive" value="0" name="active"
                                {{ $map->active == 0 ? 'checked' : '' }}>
                            <label for="unactive" class="custom-control-label">Không</label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.maps.index') }}" class="btn btn-success">Xem danh sách</a>
        </div>
        @csrf
    </form>
@endsection
