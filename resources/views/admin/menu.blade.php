<nav class="main-header navbar navbar-expand navbar-white navbar-light tutorial">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ Auth::user()?->role == 1 ? route('admin.index') : route('users.home') }}" class="nav-link">TRANG
                CHỦ</a>
        </li>

        @if (Auth::user()?->role == 1)
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
                    DANH MỤC
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left" style="left: inherit; right: 0px;">
                    <p class="dropdown-item" style="font-weight: bold;">DANH SÁCH NHIỆM VỤ</p>
                    @foreach ($types as $type)
                        <a class="dropdown-item" href="{{ route('admin.tasks.index', ['type_id' => $type->id]) }}"
                            style="font-weight:bold;">
                            {{ $type->name }}
                        </a>
                        @foreach ($type->children as $child)
                            <a href="{{ route('admin.tasks.index', ['type_id' => $child->id]) }}"
                                style="padding-left: 40px" class="dropdown-item">
                                -&emsp;{{ $child->name }}
                            </a>
                        @endforeach
                        <div class="dropdown-divider"></div>
                    @endforeach
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
                    NGHIỆP VỤ
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left" style="left: inherit; right: 0px;">
                    <p class="dropdown-item" style="font-weight: bold;">KHÁCH HÀNG</p>
                    <a href="#" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Báo giá - Khảo sát
                    </a>
                    <a href="{{ route('admin.contracts.index') }}" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Hợp đồng
                    </a>
                    <div class="dropdown-divider"></div>
                    <p class="dropdown-item" style="font-weight: bold;">KẾ HOẠCH</p>
                    <a href="#" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Nhập kế hoạch
                    </a>
                    <a href="#" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Xem và gửi
                    </a>
                    <div class="dropdown-divider"></div>
                    <p class="dropdown-item" style="font-weight: bold;">BÁO CÁO</p>
                    <a href="{{ route('admin.reports.index') }}" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Nhập báo cáo
                    </a>
                    <a href="#" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Xem và gửi
                    </a>
                    <div class="dropdown-divider"></div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
                    HỆ THỐNG
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left" style="left: inherit; right: 0px;">
                    <p class="dropdown-item" style="font-weight: bold;">HỆ THỐNG</p>
                    <a href="{{ route('admin.settings.backup') }}" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Sao lưu
                    </a>
                    <a href="#" style="padding-left: 40px" class="dropdown-item btn-restore-db">
                        -&emsp;Phục hồi dữ liệu
                    </a>
                    <p class="dropdown-item" style="font-weight: bold;">QUẢN LÝ</p>
                    <a href="{{ route('admin.accounts.index') }}" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Tài khoản đăng nhập
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.customers.index') }}" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Khách hàng
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.branches.index') }}" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Chi nhánh
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.staffs.index') }}" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Nhân viên
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.types.index') }}" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Loại nhiệm vụ
                    </a>
                    <!-- <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.maps.index') }}" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Sơ đồ
                    </a> -->
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.chemistries.index') }}" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Hóa chất
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.solutions.index') }}" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Phương pháp
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.items.index') }}" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Vật tư
                    </a>
                    <!-- <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.tasks.index') }}" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Nhiệm vụ
                    </a> -->
                    <div class="dropdown-divider"></div>
                    <a href="#" style="padding-left: 40px" class="dropdown-item">
                        -&emsp;Đề xuất
                    </a>
                </div>
            </li>
        @endif
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
                TRỢ GIÚP
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left" style="left: inherit; right: 0px;">
                <p class="dropdown-item" style="font-weight: bold;">THÔNG TIN</p>
                <a href="#" style="padding-left: 40px" class="dropdown-item">
                    -&emsp;Kiến thức - Nghiệp vụ
                </a>
                <a href="#" style="padding-left: 40px" class="dropdown-item">
                    -&emsp;Phiên bản: VPSC.1215
                </a>
                <div class="dropdown-divider"></div>
                {{-- <a href="#" style="padding-left: 40px" class="dropdown-item">
                    -&emsp;Bảo trì hệ thống
                </a> --}}
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
                <i class="fa-solid fa-gear"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left" style="left: inherit; right: 0px;">
                <p class="dropdown-item" style="font-weight: bold;">Cài đặt</p>
                {{-- <a href="#" style="padding-left: 40px" class="dropdown-item" data-target="#modalChangePassword"
                    data-toggle="modal">
                    -&emsp;Đổi mật khẩu
                </a> --}}
                <button style="padding-left: 40px" class="dropdown-item" data-target="#modalChangePassword"
                    data-toggle="modal">
                    -&emsp;Đổi mật khẩu
                </button>
                <a href="{{ route('users.logout') }}" style="padding-left: 40px" class="dropdown-item"
                    onclick="return confirm('Bạn có muốn đăng xuất?')" class="nav-link">
                    -&emsp;Đăng xuất
                </a>
                <div class="dropdown-divider"></div>
            </div>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
    </ul>
</nav>
<div class="modal fade" id="modalChangePassword" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Đổi mật khẩu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input name="tel_or_email" id="tel_or_email" type="text" value="" class="form-control"
                        placeholder="Nhập số điện thoại hoặc email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="old_password" id="old_password" value=""
                        class="form-control" placeholder="Nhập mật khẩu cũ">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" id="password" value="" class="form-control"
                        placeholder="Nhập mật khẩu mới">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <button style="width: 100%" class="btn btn-primary btn-change-password">Đổi mật khẩu</button>
                    </div>
                </div>
            </div>
            <input type="hidden" id="id_electask">
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                {{-- <button type="submit" class="btn btn-primary btn-save">Lưu</button> --}}
            </div>
        </div>
    </div>
</div>
