@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
    <style>
        .tab-content {
            padding: 10px;
        }
    </style>
@endpush
@push('scripts')
    <script src="/js/admin/task/detail.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script>
        var dataTableMap = null;
        var dataTableStaff = null;
        var dataTableItem = null;
        var dataTableChemistry = null;
        var dataTableSolution = null;
        var listIdMap = [];
        var listIdStaff = [];
        var listIdItem = [];
        var listIdChemistry = [];
        var listIdSolution = [];
        var listAllIdMap = [];
        var listAllIdStaff = [];
        var listAllIdItem = [];
        var listAllIdChemistry = [];
        var listAllIdSolution = [];

        function closeModal(type) {
            $("#modal-" + type).css("display", "none");
            $("body").removeClass("modal-open");
            $(".modal-backdrop").remove();
        }

        $("#upload").change(function() {
            const form = new FormData();
            form.append("file", $(this)[0].files[0]);
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

        $(document).ready(function() {
            // map
            dataTableMap = $("#tableMap").DataTable({
                ajax: {
                    url: "/api/settingtaskmaps?id=" + $("#task_id").val(),
                    dataSrc: "taskMaps",
                },
                columns: [
                    {
                        data: function(d) {
                            if (!listAllIdMap.includes(d.id)) {
                                listAllIdMap.push(d.id);
                            }
                            return `<input class="select-id-map" data-id="${d.id}" type="checkbox" /> `;
                        },
                    },
                    // {
                    //     data: "id"
                    // },
                    {
                        data: "code"
                    },
                    {
                        data: "position"
                    },
                    {
                        data: "target"
                    },
                    {
                        data: "unit"
                    },
                    {
                        data: "kpi"
                    },
                    {
                        data: "fake_result"
                    },
                    {
                        data: "area"
                    },
                    // {
                    //     data: "description"
                    // },
                    {
                        data: "round"
                    },
                    {
                        data: function(d) {
                            return `<img style="width: 50px;height:50px" src="${d.image}" alt="image" />`;
                        },
                    },
                    // {
                    //     data: function(d) {
                    //         return getActive(d.active);
                    //     },
                    // },
                    {
                        data: function(d) {
                            return `<a class="btn btn-primary btn-sm btn-edit" data-id="${d.id}" data-target="#modal-map" data-toggle="modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button data-id="${d.id}"
                                                    class="btn btn-danger btn-sm btn-delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>`;
                        },
                    },
                ],
            });
            // staff
            dataTableStaff = $("#tableStaff").DataTable({
                ajax: {
                    url: "/api/settingtaskstaff?id=" + $("#task_id").val(),
                    dataSrc: "taskStaff",
                },
                columns: [
                    // {
                    //     data: "id"
                    // },
                    {
                        data: function(d) {
                            return `NV${
                        d.user.staff.id >= 10
                            ? d.user.staff.id
                            : "0" + d.user.staff.id
                    }`;
                        },
                    },
                    {
                        data: "user.staff.name"
                    },
                    {
                        data: "user.staff.position"
                    },
                    {
                        data: "user.staff.identification"
                    },
                    {
                        data: "user.staff.tel"
                    },
                    {
                        data: function(d) {
                            return `<a class="btn btn-primary btn-sm btn-edit-staff" data-id="${d.id}" data-target="#modal-staff" data-toggle="modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button data-id="${d.id}"
                                                    class="btn btn-danger btn-sm btn-delete-staff">
                                                    <i class="fas fa-trash"></i>
                                                </button>`;
                        },
                    },
                ],
            });
            // chemistry
            dataTableChemistry = $("#tableChemistry").DataTable({
                ajax: {
                    url: "/api/settingtaskchemistries?id=" + $("#task_id").val(),
                    dataSrc: "taskChemistries",
                },
                columns: [
                    // {
                    //     data: "id"
                    // },
                    {
                        data: "chemistry.name"
                    },
                    {
                        data: "unit"
                    },
                    {
                        data: "kpi"
                    },
                    {
                        data: function(d) {
                            return `<a class="btn btn-primary btn-sm btn-edit-chemistry" data-id="${d.id}" data-target="#modal-chemistry" data-toggle="modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button data-id="${d.id}"
                                                    class="btn btn-danger btn-sm btn-delete-chemistry">
                                                    <i class="fas fa-trash"></i>
                                                </button>`;
                        },
                    },
                ],
            });
            // item
            dataTableItem = $("#tableItem").DataTable({
                ajax: {
                    url: "/api/settingtaskitems?id=" + $("#task_id").val(),
                    dataSrc: "taskItems",
                },
                columns: [
                    // {
                    //     data: "id"
                    // },
                    {
                        data: "item.name"
                    },
                    {
                        data: "unit"
                    },
                    {
                        data: "kpi"
                    },
                    {
                        data: function(d) {
                            return `<a class="btn btn-primary btn-sm btn-edit-item" data-id="${d.id}" data-target="#modal-item" data-toggle="modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button data-id="${d.id}"
                                                    class="btn btn-danger btn-sm btn-delete-item">
                                                    <i class="fas fa-trash"></i>
                                                </button>`;
                        },
                    },
                ],
            });
            // solution
            dataTableSolution = $("#tableSolution").DataTable({
                ajax: {
                    url: "/api/settingtasksolutions?id=" + $("#task_id").val(),
                    dataSrc: "taskSolutions",
                },
                columns: [
                    // {
                    //     data: "id"
                    // },
                    {
                        data: "solution.name"
                    },
                    {
                        data: "unit"
                    },
                    {
                        data: "kpi"
                    },
                    {
                        data: function(d) {
                            return `<a class="btn btn-primary btn-sm btn-edit-solution" data-id="${d.id}" data-target="#modal-solution" data-toggle="modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button data-id="${d.id}"
                                                    class="btn btn-danger btn-sm btn-delete-solution">
                                                    <i class="fas fa-trash"></i>
                                                </button>`;
                        },
                    },
                ],
            });
        });

        // solution
        $(document).on("click", ".btn-edit-solution", function() {
            $.ajax({
                type: "GET",
                url: "/api/settingtasksolutions/" + $(this).data("id") + "/show",
                success: function(response) {
                    if (response.status == 0) {
                        let taskSolution = response.taskSolution;
                        $(".modal-title-solution").text("Cập nhật phương pháp");
                        $("#solution_id").val(taskSolution.solution_id);
                        $("#solution_unit").val(taskSolution.unit);
                        $("#solution_kpi").val(taskSolution.kpi);
                        //
                        $(".btn-add-solution").css("display", "none");
                        $(".btn-update-solution").css("display", "block");
                        $("#settingtasksolution_id").val(taskSolution.id);
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        });

        $(".btn-open-modal-solution").on("click", function() {
            $(".modal-title-solution").text("Thêm phương pháp");
            $(".btn-add-solution").css("display", "block");
            $(".btn-update-solution").css("display", "none");
        });

        $(document).on("click", ".btn-update-solution", function() {
            if (confirm("Bạn có muốn sửa")) {
                let data = {
                    id: $("#settingtasksolution_id").val(),
                    unit: $("#solution_unit").val(),
                    kpi: $("#solution_kpi").val(),
                    solution_id: $("#solution_id").val(),
                };
                $.ajax({
                    type: "POST",
                    url: $(this).data("url"),
                    data: data,
                    success: function(response) {
                        if (response.status == 0) {
                            closeModal("solution");
                            toastr.success("Cập nhật thành công");
                            dataTableSolution.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });
            }
        });

        $(document).on("click", ".btn-delete-solution", function() {
            if (confirm("Bạn có muốn xóa")) {
                let id = $(this).data("id");
                $.ajax({
                    type: "DELETE",
                    url: `/api/settingtasksolutions/${id}/destroy`,
                    success: function(response) {
                        if (response.status == 0) {
                            toastr.success("Xóa thành công");
                            dataTableSolution.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });
            }
        });

        $(document).on("click", ".btn-add-solution", function() {
            let data = {
                unit: $("#solution_unit").val(),
                kpi: $("#solution_kpi").val(),
                solution_id: $("#solution_id").val(),
                task_id: $("#task_id").val(),
            };
            $.ajax({
                type: "POST",
                data: data,
                url: $(this).data("url"),
                success: function(response) {
                    if (response.status == 0) {
                        closeModal("solution");
                        dataTableSolution.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        });

        // item
        $(document).on("click", ".btn-edit-item", function() {
            $.ajax({
                type: "GET",
                url: "/api/settingtaskitems/" + $(this).data("id") + "/show",
                success: function(response) {
                    if (response.status == 0) {
                        let taskItem = response.taskItem;
                        $(".modal-title-item").text("Cập nhật vật tư");
                        $("#item_id").val(taskItem.item_id);
                        $("#item_unit").val(taskItem.unit);
                        $("#item_kpi").val(taskItem.kpi);
                        //
                        $(".btn-add-item").css("display", "none");
                        $(".btn-update-item").css("display", "block");
                        $("#settingtaskitem_id").val(taskItem.id);
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        });

        $(".btn-open-modal-item").on("click", function() {
            $(".modal-title-item").text("Thêm vật tư");
            $(".btn-add-item").css("display", "block");
            $(".btn-update-item").css("display", "none");
        });

        $(document).on("click", ".btn-update-item", function() {
            if (confirm("Bạn có muốn sửa")) {
                let data = {
                    id: $("#settingtaskitem_id").val(),
                    unit: $("#item_unit").val(),
                    kpi: $("#item_kpi").val(),
                    item_id: $("#item_id").val(),
                };
                $.ajax({
                    type: "POST",
                    url: $(this).data("url"),
                    data: data,
                    success: function(response) {
                        if (response.status == 0) {
                            closeModal("item");
                            toastr.success("Cập nhật thành công");
                            dataTableItem.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });
            }
        });

        $(document).on("click", ".btn-delete-item", function() {
            if (confirm("Bạn có muốn xóa")) {
                let id = $(this).data("id");
                $.ajax({
                    type: "DELETE",
                    url: `/api/settingtaskitems/${id}/destroy`,
                    success: function(response) {
                        if (response.status == 0) {
                            toastr.success("Xóa thành công");
                            dataTableItem.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });
            }
        });

        $(document).on("click", ".btn-add-item", function() {
            let data = {
                unit: $("#item_unit").val(),
                kpi: $("#item_kpi").val(),
                item_id: $("#item_id").val(),
                task_id: $("#task_id").val(),
            };
            $.ajax({
                type: "POST",
                data: data,
                url: $(this).data("url"),
                success: function(response) {
                    if (response.status == 0) {
                        closeModal("item");
                        dataTableItem.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        });

        // map
        $(document).on("click", ".select-id-map-all", function() {
            if ($(this).prop('checked')) {
                $('.select-id-map').prop('checked', true);
                listIdMap = listAllIdMap;
            } else {
                $('.select-id-map').prop('checked', false);
                listIdMap = [];
            }
            $('.btn-delete-map-all').css('display', listIdMap.length > 0 ? 'block' : 'none');
        });
        $(document).on("click", ".select-id-map", function() {
            let id = $(this).data('id');
            if (!listIdMap.includes(id)) {
                listIdMap.push(id);
            } else {
                listIdMap = listIdMap.filter(function(item) {
                    return item !== id;
                })
            };
            $('.btn-delete-map-all').css('display', listIdMap.length > 0 ? 'block' : 'none');
        });
        $(document).on("click", ".btn-edit", function() {
            $.ajax({
                type: "GET",
                url: "/api/settingtaskmaps/" + $(this).data("id") + "/show",
                success: function(response) {
                    if (response.status == 0) {
                        let taskMap = response.taskMap;
                        $(".modal-title-map").text("Cập nhật sơ đồ");
                        $("#map_id").val(taskMap.map_id);
                        $("#kpi").val(taskMap.kpi);
                        $("#unit").val(taskMap.unit);
                        $("#code").val(taskMap.code);
                        $("#position").val(taskMap.position);
                        $("#target").val(taskMap.target);
                        $("#kpi").val(taskMap.kpi);
                        $("#fake_result").val(taskMap.fake_result);
                        $("#area").val(taskMap.area);
                        // $("#description").val(taskMap.description);
                        $("#range").val(taskMap.round);
                        $("#image_show").attr("src", taskMap.image);
                        $("#image").val(taskMap.image);
                        //
                        $(".btn-add-map").css("display", "none");
                        $(".btn-update-map").css("display", "block");
                        $("#settingtaskmap_id").val(taskMap.id);
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        });

        $(".btn-open-modal").on("click", function() {
            $(".modal-title-map").text("Thêm sơ đồ");
            $("#kpi").val("");
            $("#unit").val("");
            $(".btn-add-map").css("display", "block");
            $(".btn-update-map").css("display", "none");
        });

        $(document).on("click", ".btn-update-map", function() {
            if (confirm("Bạn có muốn sửa")) {
                let data = {
                    id: $("#settingtaskmap_id").val(),
                    kpi: $("#kpi").val(),
                    unit: $("#unit").val(),
                    target: $("#target").val(),
                    code: $("#code").val(),
                    position: $("#position").val(),
                    fake_result: $("#fake_result").val(),
                    area: $("#area").val(),
                    image: $("#image").val(),
                    // description: $("#description").val(),
                    round: $("#range").val(),
                    task_id: $("#task_id").val(),
                };
                console.log(data);
                $.ajax({
                    type: "POST",
                    url: $(this).data("url"),
                    data: data,
                    success: function(response) {
                        if (response.status == 0) {
                            closeModal("map");
                            toastr.success("Cập nhật thành công");
                            dataTableMap.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });
            }
        });

        $(document).on("click", ".btn-delete", function() {
            if (confirm("Bạn có muốn xóa")) {
                let id = $(this).data("id");
                $.ajax({
                    type: "DELETE",
                    url: `/api/settingtaskmaps/${id}/destroy`,

                    success: function(response) {
                        if (response.status == 0) {
                            toastr.success("Xóa thành công");
                            dataTableMap.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });
            }
        });

        $(document).on("click", ".btn-delete-map-all", function() {
            if (confirm("Bạn có muốn xóa")) {
                $.ajax({
                    type: "POST",
                    url: `/api/settingtaskmaps/deleteAll`,
                    data: {
                        ids: listIdMap
                    },
                    success: function(response) {
                        if (response.status == 0) {
                            toastr.success("Xóa thành công");
                            dataTableMap.ajax.reload();
                            listIdMap.length = 0;
                            $('.btn-delete-map-all').css('display', 'none');
                            $('.select-id-map-all').prop('checked', false);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });
            }
        });

        $(document).on("click", ".btn-add-map", function() {
            let data = {
                code: $("#code").val(),
                position: $("#position").val(),
                number: $("#number").val(),
                area: $("#area").val(),
                target: $("#target").val(),
                image: $("#image").val(),
                // description: $("#description").val(),
                range: $("#range").val(),
                active: $("#active").val(),
                unit: $("#unit").val(),
                kpi: $("#kpi").val(),
                target: $("#target").val(),
                task_id: $("#task_id").val(),
                fake_result: $("#fake_result").val(),
                map_id: $("#map_id").val(),
            };
            $.ajax({
                type: "POST",
                data: data,
                url: $(this).data("url"),
                success: function(response) {
                    if (response.status == 0) {
                        closeModal("map");
                        dataTableMap.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        });

        // staff
        $(document).on("click", ".btn-edit-staff", function() {
            $.ajax({
                type: "GET",
                url: "/api/settingtaskstaff/" + $(this).data("id") + "/show",
                success: function(response) {
                    if (response.status == 0) {
                        let taskStaff = response.taskStaff;
                        $(".modal-title-staff").text("Cập nhật nhân sự");
                        $("#staff_id").val(taskStaff.user_id);
                        //
                        $(".btn-add-staff").css("display", "none");
                        $(".btn-update-staff").css("display", "block");
                        $("#settingtaskstaff_id").val(taskStaff.id);
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        });

        $(".btn-open-modal-staff").on("click", function() {
            $(".modal-title-staff").text("Thêm nhân sự");
            $(".btn-add-staff").css("display", "block");
            $(".btn-update-staff").css("display", "none");
        });

        $(document).on("click", ".btn-update-staff", function() {
            if (confirm("Bạn có muốn sửa")) {
                let data = {
                    id: $("#settingtaskstaff_id").val(),
                    task_id: $("#task_id").val(),
                    user_id: $("#staff_id").val(),
                };
                $.ajax({
                    type: "POST",
                    url: $(this).data("url"),
                    data: data,
                    success: function(response) {
                        if (response.status == 0) {
                            closeModal("staff");
                            toastr.success("Cập nhật thành công");
                            dataTableStaff.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });
            }
        });

        $(document).on("click", ".btn-delete-staff", function() {
            if (confirm("Bạn có muốn xóa")) {
                let id = $(this).data("id");
                $.ajax({
                    type: "DELETE",
                    url: `/api/settingtaskstaff/${id}/destroy`,

                    success: function(response) {
                        if (response.status == 0) {
                            toastr.success("Xóa thành công");
                            dataTableStaff.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });
            }
        });

        $(document).on("click", ".btn-add-staff", function() {
            let data = {
                task_id: $("#task_id").val(),
                user_id: $("#staff_id").val(),
            };
            $.ajax({
                type: "POST",
                data: data,
                url: $(this).data("url"),
                success: function(response) {
                    if (response.status == 0) {
                        closeModal("staff");
                        dataTableStaff.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        });

        // chemistry
        $(document).on("click", ".btn-edit-chemistry", function() {
            $.ajax({
                type: "GET",
                url: "/api/settingtaskchemistries/" + $(this).data("id") + "/show",
                success: function(response) {
                    if (response.status == 0) {
                        let taskChemistry = response.taskChemistry;
                        $(".modal-title-chemistry").text("Cập nhật hóa chất");
                        $("#chemistry_id").val(taskChemistry.chemistry_id);
                        $("#chemistry_unit").val(taskChemistry.unit);
                        $("#chemistry_kpi").val(taskChemistry.kpi);
                        //
                        $(".btn-add-chemistry").css("display", "none");
                        $(".btn-update-chemistry").css("display", "block");
                        $("#settingtaskchemistry_id").val(taskChemistry.id);
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        });

        $(".btn-open-modal-chemistry").on("click", function() {
            $(".modal-title-chemistry").text("Thêm hóa chất");
            $(".btn-add-chemistry").css("display", "block");
            $(".btn-update-chemistry").css("display", "none");
        });

        $(document).on("click", ".btn-update-chemistry", function() {
            if (confirm("Bạn có muốn sửa")) {
                let data = {
                    id: $("#settingtaskchemistry_id").val(),
                    unit: $("#chemistry_unit").val(),
                    kpi: $("#chemistry_kpi").val(),
                    chemistry_id: $("#chemistry_id").val(),
                };
                $.ajax({
                    type: "POST",
                    url: $(this).data("url"),
                    data: data,
                    success: function(response) {
                        if (response.status == 0) {
                            closeModal("chemistry");
                            toastr.success("Cập nhật thành công");
                            dataTableChemistry.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });
            }
        });

        $(document).on("click", ".btn-delete-chemistry", function() {
            if (confirm("Bạn có muốn xóa")) {
                let id = $(this).data("id");
                $.ajax({
                    type: "DELETE",
                    url: `/api/settingtaskchemistries/${id}/destroy`,
                    success: function(response) {
                        if (response.status == 0) {
                            toastr.success("Xóa thành công");
                            dataTableChemistry.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });
            }
        });

        $(document).on("click", ".btn-add-chemistry", function() {
            let data = {
                unit: $("#chemistry_unit").val(),
                kpi: $("#chemistry_kpi").val(),
                chemistry_id: $("#chemistry_id").val(),
                task_id: $("#task_id").val(),
            };
            $.ajax({
                type: "POST",
                data: data,
                url: $(this).data("url"),
                success: function(response) {
                    if (response.status == 0) {
                        closeModal("chemistry");
                        dataTableChemistry.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        });
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                    <h3 class="card-title text-bold">Danh sách</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;padding: 10px !important;">
                    {{-- <a href="{{ route('admin.tasks.create') }}" class="btn btn-success">Thêm mới</a> --}}
                    {{-- <input class="" style="" type="date" name="from"
            value="{{ Request::get('from') ?? now()->format('Y-m-01') }}" />
        <input class="" style="" type="date" name="to"
            value="{{ Request::get('to') ?? now()->format('Y-m-t') }}" />
        <button class="btn btn-warning btn-filter" type="submit">Lọc</button> --}}
                    <div class="mb-2">
                        <button class="btn btn-success btn-open-modal" data-target="#modal" data-toggle="modal">Thêm
                            mới</button>
                    </div>
                    <table id="table" class="table display nowrap dataTable dtr-inline collapsed">
                        <thead>
                            <tr>
                                <!-- <th>ID</th> -->
                                <th>Nhiệm vụ</th>
                                <th>Ngày kế hoạch</th>
                                <th>Ngày thực hiện</th>
                                <th>Giờ vào</th>
                                <th>Giờ ra</th>
                                <th>Ngày lập</th>
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
    <div class="modal fade" id="modal" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title modal-title-taskdetail">Cập nhật chi tiết nhiệm vụ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Ngày kế hoạch <span class="required">(*)</span></label>
                                <input type="date" id="plan_date" class="form-control" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Ngày thực hiện</label>
                                <input type="date" id="actual_date" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Giờ vào <span class="required">(*)</span></label>
                                <input type="text" id="time_in" class="form-control" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Giờ ra <span class="required">(*)</span></label>
                                <input type="text" id="time_out" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" data-url="{{ route('taskdetails.store') }}"
                        class="btn btn-primary btn-add-detail">Lưu</button>
                    <button style="display: none" type="button" data-url="{{ route('taskdetails.update') }}"
                        class="btn btn-primary btn-update-detail">Lưu</button>
                </div>
            </div>
        </div>
    </div>
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
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                                                href="#custom-tabs-four-home" role="tab"
                                                aria-controls="custom-tabs-four-home" aria-selected="false">Sơ đồ</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                                href="#custom-tabs-four-profile" role="tab"
                                                aria-controls="custom-tabs-four-profile" aria-selected="false">Nhân sự</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-four-items-tab" data-toggle="pill"
                                                href="#custom-tabs-four-items" role="tab"
                                                aria-controls="custom-tabs-four-items" aria-selected="false">Vật tư</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-four-chemistries-tab" data-toggle="pill"
                                                href="#custom-tabs-four-chemistries" role="tab"
                                                aria-controls="custom-tabs-four-chemistries" aria-selected="true">Hóa
                                                chất</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-four-solutions-tab" data-toggle="pill"
                                                href="#custom-tabs-four-solutions" role="tab"
                                                aria-controls="custom-tabs-four-solutions" aria-selected="true">Phương
                                                pháp</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        {{-- Map --}}
                                        <div class="tab-pane active show fade" id="custom-tabs-four-home" role="tabpanel"
                                            aria-labelledby="custom-tabs-four-home-tab">
                                            <button class="mb-2 btn btn-success btn-open-modal" data-target="#modal-map"
                                                data-toggle="modal">Thêm
                                                mới</button>
                                            <table id="tableMap"
                                                class="table-map table display nowrap dataTable dtr-inline collapsed">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" class="select-id-map-all" />
                                                            <button class="btn btn-danger btn-sm btn-delete-map-all"
                                                                style="display: none">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </th>
                                                        <!-- <th>ID</th> -->
                                                        <th>Mã sơ đồ</th>
                                                        <th>Vị trí</th>
                                                        <th>Đối tượng</th>
                                                        <th>Đơn vị</th>
                                                        <th>KPI</th>
                                                        <th>Kết quả dự kiến</th>
                                                        <th>Khu vực</th>
                                                        {{-- <th>Mô tả</th> --}}
                                                        <th>Phạm vi</th>
                                                        <th>Ảnh</th>
                                                        {{-- <th>Hiệu lực</th> --}}
                                                        <th>Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        {{-- Staff --}}
                                        <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                                            aria-labelledby="custom-tabs-four-profile-tab">
                                            <button class="mb-2 btn btn-success btn-open-modal-staff"
                                                data-target="#modal-staff" data-toggle="modal">Thêm
                                                mới</button>
                                            <table id="tableStaff"
                                                class="table-staff table display nowrap dataTable dtr-inline collapsed">
                                                <thead>
                                                    <tr>
                                                        <!-- <th>ID</th> -->
                                                        <th>Mã nhân viên</th>
                                                        <th>Họ tên</th>
                                                        <th>Chức vụ</th>
                                                        <th>Số điện thoại</th>
                                                        <th>CCCD</th>
                                                        <th>Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        {{-- Item --}}
                                        <div class="tab-pane fade" id="custom-tabs-four-items" role="tabpanel"
                                            aria-labelledby="custom-tabs-four-items-tab">
                                            <button class="mb-2 btn btn-success btn-open-modal-item"
                                                data-target="#modal-item" data-toggle="modal">Thêm
                                                mới</button>
                                            <table id="tableItem"
                                                class="table-item table display nowrap dataTable dtr-inline collapsed">
                                                <thead>
                                                    <tr>
                                                        <!-- <th>ID</th> -->
                                                        <th>Tên</th>
                                                        <th>Đơn vị</th>
                                                        <th>KPI</th>
                                                        <th>Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        {{-- Chemistry --}}
                                        <div class="tab-pane fade" id="custom-tabs-four-chemistries" role="tabpanel"
                                            aria-labelledby="custom-tabs-four-chemistries-tab">
                                            <button class="mb-2 btn btn-success btn-open-modal-chemistry"
                                                data-target="#modal-chemistry" data-toggle="modal">Thêm
                                                mới</button>
                                            <table id="tableChemistry"
                                                class="table-chemistry table display nowrap dataTable dtr-inline collapsed">
                                                <thead>
                                                    <tr>
                                                        <!-- <th>ID</th> -->
                                                        <th>Tên</th>
                                                        <th>Đơn vị</th>
                                                        <th>KPI</th>
                                                        <th>Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        {{-- Solution --}}
                                        <div class="tab-pane fade" id="custom-tabs-four-solutions" role="tabpanel"
                                            aria-labelledby="custom-tabs-four-solutions-tab">
                                            <button class="mb-2 btn btn-success btn-open-modal-solution"
                                                data-target="#modal-solution" data-toggle="modal">Thêm
                                                mới</button>
                                            <table id="tableSolution"
                                                class="table-solution table display nowrap dataTable dtr-inline collapsed">
                                                <thead>
                                                    <tr>
                                                        <!-- <th>ID</th> -->
                                                        <th>Tên</th>
                                                        <th>Đơn vị</th>
                                                        <th>KPI</th>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- add solution --}}
    <div class="modal fade" id="modal-solution" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title-solution">Thêm phương pháp</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="">
                    <div class="">
                        <div class=""></div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Phương pháp <span class="required">(*)</span></label>
                                <select class="form-control" name="" id="solution_id">
                                    @foreach ($solutions as $solution)
                                        <option value="{{ $solution->id }}">
                                            {{ $solution->id . '-' . $solution->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Đơn vị <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="solution_unit" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">KPI <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="solution_kpi" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" data-url="{{ route('settingtasksolutions.store') }}"
                        class="btn btn-primary btn-add-solution">Lưu</button>
                    <button style="display: none" type="button" data-url="{{ route('settingtasksolutions.update') }}"
                        class="btn btn-primary btn-update-solution">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    {{-- add item --}}
    <div class="modal fade" id="modal-item" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title-item">Thêm vật tư</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="">
                    <div class="">
                        <div class=""></div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Vật tư <span class="required">(*)</span></label>
                                <select class="form-control" name="" id="item_id">
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->id . '-' . $item->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Đơn vị <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="item_unit" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">KPI <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="item_kpi" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" data-url="{{ route('settingtaskitems.store') }}"
                        class="btn btn-primary btn-add-item">Lưu</button>
                    <button style="display: none" type="button" data-url="{{ route('settingtaskitems.update') }}"
                        class="btn btn-primary btn-update-item">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    {{-- add map --}}
    <div class="modal fade" id="modal-map" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title-map">Thêm sơ đồ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Số lượng sơ đồ <span class="required">(*)</span></label>
                                <input type="number" class="form-control" id="number" value="1"
                                    placeholder="Nhập số lượng sơ đồ">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Mã sơ đồ <span class="required">(*)</span></label>
                                <input type="text" class="form-control" id="code" value=""
                                    placeholder="Nhập mã sơ đồ">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Vị trí</label>
                                <input type="text" class="form-control" id="position" value=""
                                    placeholder="Nhập vị trí">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Đối tượng</label>
                                <input type="text" class="form-control" id="target" value=""
                                    placeholder="Nhập đối tượng">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Đơn vị <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="unit" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">KPI <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="kpi" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Kết quả dự kiến</label>
                                <input class="form-control" type="text" id="fake_result" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Khu vực</label>
                                <input type="text" class="form-control" id="area" value=""
                                    placeholder="Nhập khu vực" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Mô tả</label>
                                <input class="form-control" id="description" placeholder="Nhập mô tả" />
                            </div>
                        </div> --}}
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Phạm vi</label>
                                <input type="text" class="form-control" id="range" value=""
                                    placeholder="Nhập phạm vi" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="file">Chọn ảnh</label><br>
                                <div class="">
                                    <img id="image_show" style="width: 100px;height:100px" src=""
                                        alt="image" />
                                    <input type="file" id="upload" accept=".png,.jpeg" />
                                </div>
                                <input type="hidden" id="image" value="">
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label>Hiệu lực</label>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="active" value="1"
                                        name="active" checked>
                                    <label for="active" class="custom-control-label">Có</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="unactive" value="0"
                                        name="active">
                                    <label for="unactive" class="custom-control-label">Không</label>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" data-url="{{ route('settingtaskmaps.store') }}"
                        class="btn btn-primary btn-add-map">Lưu</button>
                    <button style="display: none" type="button" data-url="{{ route('settingtaskmaps.update') }}"
                        class="btn btn-primary btn-update-map">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    {{-- add staff --}}
    <div class="modal fade" id="modal-staff" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title-staff">Thêm nhân sự</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Nhân sự <span class="required">(*)</span></label>
                                <select class="form-control" name="" id="staff_id">
                                    @foreach ($staffs as $staff)
                                        <option value="{{ $staff->id }}">
                                            {{ $staff->id . '-' . $staff->staff->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" data-url="{{ route('settingtaskstaff.store') }}"
                        class="btn btn-primary btn-add-staff">Lưu</button>
                    <button style="display: none" type="button" data-url="{{ route('settingtaskstaff.update') }}"
                        class="btn btn-primary btn-update-staff">Lưu</button>
                </div>
            </div>

        </div>
    </div>
    {{-- add chemistry --}}
    <div class="modal fade" id="modal-chemistry" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title-chemistry">Thêm hóa chất</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="">
                    <div class="">
                        <div class=""></div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Hóa chất <span class="required">(*)</span></label>
                                <select class="form-control" name="" id="chemistry_id">
                                    @foreach ($chemistries as $chemistry)
                                        <option value="{{ $chemistry->id }}">
                                            {{ $chemistry->id . '-' . $chemistry->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Đơn vị <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="chemistry_unit" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">KPI <span class="required">(*)</span></label>
                                <input class="form-control" type="text" id="chemistry_kpi" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" data-url="{{ route('settingtaskchemistries.store') }}"
                        class="btn btn-primary btn-add-chemistry">Lưu</button>
                    <button style="display: none" type="button" data-url="{{ route('settingtaskchemistries.update') }}"
                        class="btn btn-primary btn-update-chemistry">Lưu</button>
                </div>
            </div>

        </div>
    </div>
    {{-- <input type="hidden" name="" value="{{ $taskDetail->id }}" id="task_id"> --}}
    <input type="hidden" name="" value="" id="settingtaskstaff_id">
    <input type="hidden" name="" value="" id="settingtaskchemistry_id">
    <input type="hidden" name="" value="" id="settingtaskitem_id">
    <input type="hidden" name="" value="" id="settingtasksolution_id">
    <input type="hidden" name="" value="" id="settingtaskmap_id">
    <input type="hidden" id="task_id" value="{{ request()->id }}" />
    <input type="hidden" id="taskdetail_id" />
@endsection
