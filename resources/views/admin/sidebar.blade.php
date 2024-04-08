<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <!-- Sidebar -->
    <div class="sidebar">
        @switch(Auth::user()?->role)
            @case(0)
                <a href="{{ route('users.tasks.taskToday') }}" class="brand-link text-center">
                    <span class="brand-text font-weight-light">Nhân viên</span>
                </a>
            @break

            @case(1)
                <a href="{{ route('admin.index') }}" class="brand-link text-center">
                    <span class="brand-text font-weight-light">Quản lý</span>
                </a>
            @break

            @case(2)
                <a href="{{ route('customers.me') }}" class="brand-link text-center">
                    <span class="brand-text font-weight-light">Khách hàng</span>
                </a>
            @break
        @endswitch
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            @switch(Auth::user()?->role)
                @case(0)
                    <div class="image">
                        <img src="{{ Auth::user()?->staff->avatar ?? '/images/default.jpg' }}" class="img-circle elevation-2"
                            alt="User Image">
                    </div>
                @break
            @endswitch
            <div class="info">
                @switch(Auth::user()?->role)
                    @case(0)
                        <a href="{{ route('users.tasks.taskToday') }}"
                            class="d-block">{{ Auth::user()?->staff->name ?? Auth::user()?->email }}</a>
                    @break

                    @case(1)
                        <a href="{{ route('admin.index') }}" class="d-block">{{ Auth::user()?->email }}</a>
                    @break

                    @case(2)
                        <a href="{{ route('customers.me') }}" class="d-block">{{ Auth::user()?->email }}</a>
                    @break
                @endswitch
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @switch(Auth::user()?->role)
                    {{-- Staff --}}
                    @case(0)
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['users.home', 'users.tasks.index', 'users.tasks.taskToday'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-bars"></i>
                                <p>
                                    Nhiệm vụ của tôi
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('users.tasks.index') }}"
                                        class="nav-link {{ in_array(request()->route()->getName(), ['users.tasks.index', 'users.home']) ? 'option-open' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('users.tasks.taskToday') }}"
                                        class="nav-link {{ request()->route()->getName() == 'users.tasks.taskToday' ? 'option-open' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nhiệm vụ cần làm</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['users.me']) ? 'menu-is-opening menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-user"></i>
                                <p>
                                    Thông tin cá nhân
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('users.me') }}"
                                        class="nav-link {{ request()->route()->getName() == 'users.me' ? 'option-open' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Cập nhật</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @break

                    {{-- Admin --}}
                    @case(1)
                        {{-- <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['admin.accounts.index', 'admin.accounts.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-user"></i>
                                <p>
                                    Tài khoản
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.accounts.create' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.accounts.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm tài khoản</p>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.accounts.index' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.accounts.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách tài khoản</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['admin.staffs.index', 'admin.staffs.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-users"></i>
                                <p>
                                    Nhân viên
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.staffs.index' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.staffs.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách nhân viên</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item  {{ in_array(request()->route()->getName(), ['admin.types.index', 'admin.types.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-list"></i>
                                <p>
                                    Loại nhiệm vụ
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.types.create' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.types.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm loại nhiệm vụ</p>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.types.index' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.types.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách loại nhiệm vụ</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item  {{ in_array(request()->route()->getName(), ['admin.maps.index', 'admin.maps.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-map"></i>
                                <p>
                                    Sơ đồ
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.maps.create' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.maps.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm sơ đồ</p>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.maps.index' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.maps.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách sơ đồ</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item  {{ in_array(request()->route()->getName(), ['admin.chemistries.index', 'admin.chemistries.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-skull-crossbones"></i>
                                <p>
                                    Hóa chất
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.chemistries.create' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.chemistries.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm hóa chất</p>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.chemistries.index' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.chemistries.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách hóa chất</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item  {{ in_array(request()->route()->getName(), ['admin.solutions.index', 'admin.solutions.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-walkie-talkie"></i>
                                <p>
                                    Phương pháp
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.solutions.create' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.solutions.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm phương pháp</p>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.solutions.index' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.solutions.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách phương pháp</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item  {{ in_array(request()->route()->getName(), ['admin.items.index', 'admin.items.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-screwdriver-wrench"></i>
                                <p>
                                    Vật tư
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.items.create' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.items.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm vật tư</p>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.items.index' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.items.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách vật tư</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['admin.branches.index', 'admin.branches.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-code-branch"></i>
                                <p>
                                    Chi nhánh
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.branches.create' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.branches.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm chi nhánh</p>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.branches.index' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.branches.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách chi nhánh</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ in_array(request()->route()->getName(), ['admin.tasks.index', 'admin.tasks.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-list-check"></i>
                                <p>
                                    Nhiệm vụ
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.tasks.create' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.tasks.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm nhiệm vụ</p>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.tasks.index' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.tasks.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách nhiệm vụ</p>
                                    </a>
                                </li>
                            </ul>
                        </li> --}}
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['admin.plans.index']) ? 'menu-is-opening menu-open' : '' }}">
                            <a href="{{ route('admin.plans.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-note-sticky"></i>
                                <p>
                                    Kế hoạch
                                </p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['admin.contracts.index', 'admin.contracts.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-file-contract"></i>
                                <p>
                                    Hợp đồng
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                {{-- <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.contracts.create' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.contracts.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm hợp đồng</p>
                                    </a>
                                </li> --}}
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.contracts.index' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.contracts.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách hợp đồng</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['admin.customers.index', 'admin.customers.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                                
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-person"></i>
                                <p>
                                    Khách hàng
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li
                                    class="nav-item  {{ request()->route()->getName() == 'admin.customers.index' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.customers.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách khách hàng</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{-- report --}}
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['admin.reports.index', 'admin.reports.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-flag"></i>
                                <p>
                                    Báo cáo
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li
                                    class="nav-item {{ request()->route()->getName() == 'admin.reports.index' ? 'option-open' : '' }}">
                                    <a href="{{ route('admin.reports.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Báo cáo</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), [])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-money-bill"></i>
                                <p>
                                    Báo giá
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), [])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-newspaper"></i>
                                <p>
                                    Biểu mẫu
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), [])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-gear"></i>
                                <p>
                                    Hệ thống
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>
                    @break

                    {{-- Customer --}}
                    @case(2)
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['customers.contracts.index']) ? 'menu-is-opening menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-file-contract"></i>
                                <p>
                                    Hợp đồng
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('customers.contracts.index') }}"
                                        class="nav-link {{ in_array(request()->route()->getName(), ['customers.contracts.index', 'customers.home']) ? 'option-open' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['customers.me']) ? 'menu-is-opening menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-user"></i>
                                <p>
                                    Thông tin cá nhân
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('customers.me') }}"
                                        class="nav-link {{ request()->route()->getName() == 'customers.me' ? 'option-open' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Cập nhật</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @break
                @endswitch
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
