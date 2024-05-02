@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
@endpush
@push('scripts')
    <script src="/js/admin/linkscan/index.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
@endpush
@section('content')
    <form action="{{ route('admin.linkscans.store') }}" method="POST">
        <div class="row">
            <div class="col-lg-12">
                <div class="card direct-chat direct-chat-primary">
                    <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                        <h3 class="card-title text-bold">Thêm link quét</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="display: block;padding: 10px !important;">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="menu">Tiêu đề <span class="required">(*)</span></label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title') }}"
                                        placeholder="Nhập tiêu đề">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="menu">Link hoặc PostID <span class="required">(*)</span></label>
                                    <input type="text" class="form-control" name="link_or_post_id"
                                        value="{{ old('link_or_post_id') }}" placeholder="Nhập link hoặc post ID">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" value="{{ Request::get('user_id') }}" name="user_id" id="user_id" />
        @csrf
    </form>
    <div class="row">
        <div class="col-lg-12">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                    <h3 class="card-title text-bold">Danh sách link quét</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;padding: 10px !important;">
                    <div class="form-group col-lg-6">
                        <label class="count-link">Số link: 0</label><br>
                        <label class="filtering">Lọc theo: Không</label><br>
                        <label class="count-select">Đã chọn: 0</label>
                    </div>
                    <div class="form-group col-lg-6">
                        <button disabled class="btn-control btn btn-warning btn-follow-multiple">Theo dõi</button>
                        <button disabled data-is_scan="0" class="btn-control btn btn-danger btn-scan btn-sm-multiple">OFF</button>
                        <button disabled data-is_scan="1" class="btn-control btn btn-success btn-scan-multiple">ON</button>
                        <button disabled class="btn-control btn btn-danger btn-delete-multiple">Xóa</button>
                        <button data-target="#modalFilter" data-toggle="modal"
                            class="btn btn-primary btn-choose-filter">Chọn</button>
                    </div>
                    <table id="table" class="table display nowrap dataTable dtr-inline collapsed">
                        <thead>
                            <tr>
                                <th><input class="btn-select-all" type="checkbox" /></th>
                                <th>Data cuối</th>
                                <th>Ngày tạo</th>
                                <th>Tài khoản</th>
                                <th>Tiêu đề</th>
                                <th>Nội dung</th>
                                <th>Bình luận</th>
                                <th>Data</th>
                                <th>Cảm xúc</th>
                                <th>Quét</th>
                                <th>Note</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="{{ Auth::id() }}" name="" id="user_id" />
    <div class="modal fade" id="modalFilter" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Lọc</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Data cuối</label>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" data-name="Data cuối" id="time_from"
                                            value="" placeholder="Từ">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" data-name="Data cuối" id="time_to"
                                            value="" placeholder="Đến">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Ngày tạo </label>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="date" class="form-control" data-name="Ngày tạo" id="from"
                                            value="" placeholder="Từ">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="date" class="form-control" data-name="Ngày tạo" id="to"
                                            value="" placeholder="Đến">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Bình luận</label>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" data-name="Bình luận"
                                            id="comment_from" value="" placeholder="Từ">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" data-name="Bình luận" id="comment_to"
                                            value="" placeholder="Đến">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Data</label>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="text" data-name="Data" class="form-control" id="data_from"
                                            value="" placeholder="Từ">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" data-name="Data" class="form-control" id="data_to"
                                            value="" placeholder="Đến">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Cảm xúc</label>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input data-name="Cảm xúc" type="text" class="form-control"
                                            id="reaction_from" value="" placeholder="Từ">
                                    </div>
                                    <div class="col-lg-6">
                                        <input data-name="Cảm xúc" type="text" class="form-control" id="reaction_to"
                                            value="" placeholder="Đến">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Quét</label>
                                <select data-name="Trạng thái quét" class="form-control" id="is_scan">
                                    <option value="">ALL</option>
                                    <option value="0">OFF</option>
                                    <option value="1">ON</option>
                                    <option value="2">ERROR</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Tiêu đề</label>
                                <input type="text" data-name="Tiêu đề" class="form-control" id="title"
                                    value="" placeholder="Tiêu đề">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Link|PostID</label>
                                <input type="text" data-name="Link|PostID" class="form-control" id="link_or_post_id"
                                    value="" placeholder="Link|PostID">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Tài khoản</label>
                                <input type="text" data-name="Tài khoản" class="form-control" id="user" value=""
                                    placeholder="Tên tài khoản">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Nội dung</label>
                                <input type="text" data-name="Nội dung" class="form-control" id="content"
                                    value="" placeholder="Nội dung">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="type" value="0">
                    <button class="btn btn-rounded btn-warning btn-filter">Chọn</button>
                    <button class="btn btn-rounded btn-success btn-refresh">Làm mới</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection
