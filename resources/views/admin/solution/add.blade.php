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
    <form action="{{ route('admin.solutions.store') }}" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Tên phương pháp <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                            placeholder="Nhập tên phương pháp">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Đối tượng</label>
                        <input type="text" class="form-control" name="target" value="{{ old('target') }}"
                            placeholder="Nhập đối tượng">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="menu">Mô tả</label>
                        <textarea class="form-control" name="description" id="" cols="30" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="file">Chọn ảnh</label><br>
                        <div class="">
                            <img id="image_show" style="width: 100px;height:100px" src="" alt="image" />
                            <input type="file" id="upload" accept=".png,.jpeg"/>
                        </div>
                        <input type="hidden" name="image" id="image" value="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label>Hiệu lực <span class="required">(*)</span></label>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="active" value="1" name="active"
                                checked>
                            <label for="active" class="custom-control-label">Có</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="unactive" value="0" name="active">
                            <label for="unactive" class="custom-control-label">Không</label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.solutions.index') }}" class="btn btn-success">Xem danh sách</a>
        </div>
        @csrf
    </form>
@endsection
