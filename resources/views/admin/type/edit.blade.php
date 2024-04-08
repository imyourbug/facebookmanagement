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
    <form action="{{ route('admin.types.update', ['id' => $type->id]) }}" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Tên loại <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') ?? $type->name }}"
                            placeholder="Nhập tên loại">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Danh mục cha <span class="required">(*)</span></label>
                        <select class="form-control" name="parent_id">
                            <option value="0" selected>--Danh mục cha--</option>
                            @foreach ($types as $t)
                                @if ($t->id != $type->id)
                                    <option value="{{ $t->id }}" {{ $type->parent_id == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group">
                        <label for="file">Chọn ảnh</label><br>
                        <div class="">
                            <img id="image_show" style="width: 100px;height:100px" src="{{ old('image') ?? $type->image }}" alt="image" />
                            <input type="file" id="upload" accept=".png,.jpeg"/>
                        </div>
                        <input type="hidden" name="image" id="image" value="{{ old('image') ?? $type->image }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.types.index') }}" class="btn btn-success">Xem danh sách</a>
        </div>
        @csrf
    </form>
@endsection
