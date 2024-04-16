<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/js/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/js/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/js/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/js/dist/css/adminlte.min.css">
    <!-- ajax -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <!-- select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <style>
        span.required {
            color: red;
        }

        span.select2-dropdown {
            top: -25px;
        }

        #table_filter {
            text-align: right;
        }

        .hidden {
            display: none;
        }

        .option-open {
            background-color: rgba(255, 255, 255, .1);
        }

        .open-block {
            display: block;
        }

        .open-none {
            display: none;
        }

        .table.dataTable.nowrap th,
        .table.dataTable.nowrap td {
            white-space: normal !important;
        }

        .dataTables_paginate {
            float: right;
        }

        .pagination li {
            margin-left: 10px;
        }

        .select2-container,
        .form-inline,
        .form-inline label {
            display: inline !important;
        }

        .select2-search__field {
            border: none !important;
        }

        .select2-selection__choice__display {
            color: black;
        }

        .icon {
            padding: 3px 4px;
            border-radius: 10px;
        }

        .table {
            width: 100% !important;
        }

        @media (max-width: 600px) {
            .hide-max-600 {
                display: none !important;
                color: white !important;
            }
        }

        .header-color {
            background-color: #28a745;
            color: white;
        }
    </style>
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="/images/gg.png" alt="Áo đá bóng" height="60"
                width="120">
        </div> --}}
        @include('admin.menu')
        @include('admin.sidebar')

        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary mt-3">
                                <div class="card-header">
                                    <h3 class="card-title">{{ $title }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    @yield('content')
                </div>
            </section>
        </div>
    </div>
    <div class="modal fade" id="modalHistory" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Lịch sử thay đổi</h4>
                    <button type="button" class="closeModalHistory close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table style="width: 100%">
                        <thead>
                            <th style="display: none" class="display-comment">Bình luận trước</th>
                            <th style="display: none" class="display-comment">Bình luận sau</th>
                            <th style="display: none" class="display-comment">Chênh</th>
                            <th style="display: none" class="display-data">Data trước</th>
                            <th style="display: none" class="display-data">Data sau</th>
                            <th style="display: none" class="display-data">Chênh</th>
                            <th style="display: none" class="display-emotion">Cảm xúc trước</th>
                            <th style="display: none" class="display-emotion">Cảm xúc sau</th>
                            <th style="display: none" class="display-emotion">Chênh</th>
                            <th>Thời gian</th>
                        </thead>
                        <tbody class="table-content">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <button style="display:none" class="btn-history" data-target="#modalHistory" data-toggle="modal"></button>
    <input type="file" style="opacity: 0" id="file-restore-db" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/js/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/js/dist/js/adminlte.min.js"></script>
    <!-- main.js-->
    {{-- <script src="/js/main.js"></script> --}}
    <div class="Toastify"></div>
    <script src="https://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
    {{-- select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- common --}}
    <script src="/js/common/index.js"></script>
    <script>
        $(document).on('mouseenter', '.show-history', function() {
            let link_or_post_id = $(this).data('link_or_post_id');
            let type = $(this).data('type');
            const allType = [
                'comment',
                'data',
                'emotion'
            ];
            $.ajax({
                type: "GET",
                url: `/api/linkHistories/getAll?link_or_post_id=${link_or_post_id}`,
                success: function(response) {
                    if (response.status == 0) {
                        $('.table-content').html('');
                        allType.forEach(e => {
                            $('.display-' + e).css('display', 'none');
                        });
                        $('.display-' + type).css('display', '');
                        var html = '';
                        response.histories.forEach(e => {
                            switch (type) {
                                case "comment":
                                    html += `<tr>
                                                <td>${e.comment_first}</td>
                                                <td>${e.comment_second}</td>
                                                <td>${e.comment_second - e.comment_first}</td>
                                                <td>${e.created_at}</td>
                                            </tr>`;
                                    break;
                                case "data":
                                    html += `<tr>
                                                <td>${e.data_first}</td>
                                                <td>${e.data_second}</td>
                                                <td>${e.data_second - e.data_first}</td>
                                                <td>${e.created_at}</td>
                                            </tr>`;
                                    break;
                                case "emotion":
                                    html += `<tr>
                                                <td>${e.emotion_first}</td>
                                                <td>${e.emotion_second}</td>
                                                <td>${e.emotion_second - e.emotion_first}</td>
                                                <td>${e.created_at}</td>
                                            </tr>`;
                                    break;
                            }
                        });
                        $('.table-content').html(html);
                        $('.btn-history').click();
                    } else {
                        toastr.error(response.message, "Thông báo");
                    }
                },
            });
        });

        // $(document).on('mouseleave', '.show-history', function() {
        //     // closeModal("modalHistory");
        //     $('.closeModalHistory').click();
        // })

        function closeModal(id) {
            $("#" + id).css("display", "none");
            $("body").removeClass("modal-open");
            $(".modal-backdrop").remove();
        }

        function closeModalChangePassword() {
            $("#modalChangePassword").css("display", "none");
            $("body").removeClass("modal-open");
            $(".modal-backdrop").remove();
        }
        $(document).on('click', '.btn-change-password', function() {
            $.ajax({
                type: "POST",
                data: {
                    tel_or_email: $('#tel_or_email').val(),
                    password: $('#password').val(),
                    old_password: $('#old_password').val(),
                },
                url: "/api/user/change_password",
                success: function(response) {
                    if (response.status == 0) {
                        toastr.success(response.message, "Thông báo");
                        closeModalChangePassword();
                    } else {
                        toastr.error(response.message, "Thông báo");
                    }
                },
            });
        })
    </script>
    @stack('scripts')
</body>

</html>
