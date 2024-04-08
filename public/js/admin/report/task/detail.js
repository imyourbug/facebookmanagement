var dataTableMap = null;
var dataTableStaff = null;
var dataTableItem = null;
var dataTableChemistry = null;
var dataTableSolution = null;
var listIdMap = [];
var listAllIdMap = [];

$(document).on("click", ".select-id-map-all", function () {
    if ($(this).prop('checked')) {
        $('.select-id-map').prop('checked', true);
        listIdMap = listAllIdMap;
    } else {
        $('.select-id-map').prop('checked', false);
        listIdMap = [];
    }
    $('.btn-delete-map-all').css('display', listIdMap.length > 0 ? 'block' : 'none');
});

$(document).on("click", ".select-id-map", function () {
    let id = $(this).data('id');
    if (!listIdMap.includes(id)) {
        listIdMap.push(id);
    } else {
        listIdMap = listIdMap.filter(function (item) {
            return item !== id;
        })
    };
    $('.btn-delete-map-all').css('display', listIdMap.length > 0 ? 'block' : 'none');
});

$(document).on("click", ".btn-delete-map-all", function () {
    if (confirm("Bạn có muốn xóa")) {
        $.ajax({
            type: "POST",
            url: `/api/taskmaps/deleteAll`,
            data: {
                ids: listIdMap
            },
            success: function (response) {
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

function closeModal(type) {
    $("#modal-" + type).css("display", "none");
    $("body").removeClass("modal-open");
    $(".modal-backdrop").remove();
}

$(document).ready(function () {
    // map
    dataTableMap = $("#tableMap").DataTable({
        ajax: {
            url: "/api/taskmaps?id=" + $("#task_id").val(),
            dataSrc: "taskMaps",
        },
        columns: [
            {
                data: function (d) {
                    if (!listAllIdMap.includes(d.id)) {
                        listAllIdMap.push(d.id);
                    }
                    return `<input class="select-id-map" data-id="${d.id}" type="checkbox" /> `;
                },
            },
            // { data: "id" },
            {
                data: function (d) {
                    return d.code ? d.code : `${d.map.area}-${d.map.id.toString().padStart(3, "0")}`;
                },
            },
            { data: "map.area" },
            { data: "map.target" },
            { data: "unit" },
            { data: "kpi" },
            { data: "result" },
            {
                data: function (d) {
                    return `<img style="width: 50px;height:50px" src="${d.image}" alt="image" />`;
                },
            },
            { data: "detail" },
            {
                data: function (d) {
                    return `<a class="btn btn-primary btn-sm btn-edit" data-id="${d.id}" data-target="#modal-map" data-toggle="modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>`;
                    // return `<a class="btn btn-primary btn-sm btn-edit" data-id="${d.id}" data-target="#modal-map" data-toggle="modal">
                    //                             <i class="fas fa-edit"></i>
                    //                         </a>
                    //                         <button data-id="${d.id}"
                    //                             class="btn btn-danger btn-sm btn-delete">
                    //                             <i class="fas fa-trash"></i>
                    //                         </button>`;
                },
            },
        ],
    });
    // staff
    dataTableStaff = $("#tableStaff").DataTable({
        ajax: {
            url: "/api/taskstaff?id=" + $("#task_id").val(),
            dataSrc: "taskStaff",
        },
        columns: [
            // { data: "id" },
            {
                data: function (d) {
                    return `NV${d.user.staff.id >= 10
                        ? d.user.staff.id
                        : "0" + d.user.staff.id
                        }`;
                },
            },
            { data: "user.staff.name" },
            { data: "user.staff.position" },
            { data: "user.staff.identification" },
            { data: "user.staff.tel" },
            {
                data: function (d) {
                    return `<a class="btn btn-primary btn-sm btn-edit-staff" data-id="${d.id}" data-target="#modal-staff" data-toggle="modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>`;
                    // return `<a class="btn btn-primary btn-sm btn-edit-staff" data-id="${d.id}" data-target="#modal-staff" data-toggle="modal">
                    //                             <i class="fas fa-edit"></i>
                    //                         </a>
                    //                         <button data-id="${d.id}"
                    //                             class="btn btn-danger btn-sm btn-delete-staff">
                    //                             <i class="fas fa-trash"></i>
                    //                         </button>`;
                },
            },
        ],
    });
    // chemistry
    dataTableChemistry = $("#tableChemistry").DataTable({
        ajax: {
            url: "/api/taskchemistries?id=" + $("#task_id").val(),
            dataSrc: "taskChemistries",
        },
        columns: [
            // { data: "id" },
            { data: "chemistry.name" },
            { data: "unit" },
            { data: "kpi" },
            { data: "result" },
            {
                data: function (d) {
                    return `<img style="width: 50px;height:50px" src="${d.image}" alt="image" />`;
                },
            },
            { data: "detail" },
            {
                data: function (d) {
                    return `<a class="btn btn-primary btn-sm btn-edit-chemistry" data-id="${d.id}" data-target="#modal-chemistry" data-toggle="modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>`;
                    // return `<a class="btn btn-primary btn-sm btn-edit-chemistry" data-id="${d.id}" data-target="#modal-chemistry" data-toggle="modal">
                    //                             <i class="fas fa-edit"></i>
                    //                         </a>
                    //                         <button data-id="${d.id}"
                    //                             class="btn btn-danger btn-sm btn-delete-chemistry">
                    //                             <i class="fas fa-trash"></i>
                    //                         </button>`;
                },
            },
        ],
    });
    // item
    dataTableItem = $("#tableItem").DataTable({
        ajax: {
            url: "/api/taskitems?id=" + $("#task_id").val(),
            dataSrc: "taskItems",
        },
        columns: [
            // { data: "id" },
            { data: "item.name" },
            { data: "unit" },
            { data: "kpi" },
            { data: "result" },
            {
                data: function (d) {
                    return `<img style="width: 50px;height:50px" src="${d.image}" alt="image" />`;
                },
            },
            { data: "detail" },
            {
                data: function (d) {
                    return `<a class="btn btn-primary btn-sm btn-edit-item" data-id="${d.id}" data-target="#modal-item" data-toggle="modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>`;
                    // return `<a class="btn btn-primary btn-sm btn-edit-item" data-id="${d.id}" data-target="#modal-item" data-toggle="modal">
                    //                             <i class="fas fa-edit"></i>
                    //                         </a>
                    //                         <button data-id="${d.id}"
                    //                             class="btn btn-danger btn-sm btn-delete-item">
                    //                             <i class="fas fa-trash"></i>
                    //                         </button>`;
                },
            },
        ],
    });
    // solution
    dataTableSolution = $("#tableSolution").DataTable({
        ajax: {
            url: "/api/tasksolutions?id=" + $("#task_id").val(),
            dataSrc: "taskSolutions",
        },
        columns: [
            // { data: "id" },
            { data: "solution.name" },
            { data: "unit" },
            { data: "kpi" },
            { data: "result" },
            {
                data: function (d) {
                    return `<img style="width: 50px;height:50px" src="${d.image}" alt="image" />`;
                },
            },
            { data: "detail" },
            {
                data: function (d) {
                    return `<a class="btn btn-primary btn-sm btn-edit-solution" data-id="${d.id}" data-target="#modal-solution" data-toggle="modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>`;
                    // return `<a class="btn btn-primary btn-sm btn-edit-solution" data-id="${d.id}" data-target="#modal-solution" data-toggle="modal">
                    //                             <i class="fas fa-edit"></i>
                    //                         </a>
                    //                         <button data-id="${d.id}"
                    //                             class="btn btn-danger btn-sm btn-delete-solution">
                    //                             <i class="fas fa-trash"></i>
                    //                         </button>`;
                },
            },
        ],
    });
});

// solution
$(document).on("click", ".btn-edit-solution", function () {
    $.ajax({
        type: "GET",
        url: "/api/tasksolutions/" + $(this).data("id") + "/show",
        success: function (response) {
            if (response.status == 0) {
                let taskSolution = response.taskSolution;
                $(".modal-title-solution").text("Cập nhật vật tư");
                $("#solution_id").val(taskSolution.solution_id);
                $("#solution_unit").val(taskSolution.unit);
                $("#solution_kpi").val(taskSolution.kpi);
                $("#solution_result").val(taskSolution.result);
                $("#solution_detail").val(taskSolution.detail);
                //
                $("#image_show_solution").attr("src", taskSolution.image);
                $("#image_solution").val(taskSolution.image);
                //
                $(".btn-add-solution").css("display", "none");
                $(".btn-update-solution").css("display", "block");
                $("#tasksolution_id").val(taskSolution.id);
            } else {
                toastr.error(response.message);
            }
        },
    });
});

$(".btn-open-modal-solution").on("click", function () {
    $(".modal-title-solution").text("Thêm vật tư");
    $(".btn-add-solution").css("display", "block");
    $(".btn-update-solution").css("display", "none");
});

$(document).on("click", ".btn-update-solution", function () {
    if (confirm("Bạn có muốn sửa")) {
        let data = {
            id: $("#tasksolution_id").val(),
            unit: $("#solution_unit").val(),
            kpi: $("#solution_kpi").val(),
            image: $("#image_solution").val(),
            result: $("#solution_result").val(),
            detail: $("#solution_detail").val(),
            solution_id: $("#solution_id").val(),
        };
        $.ajax({
            type: "POST",
            url: $(this).data("url"),
            data: data,
            success: function (response) {
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

$(document).on("click", ".btn-delete-solution", function () {
    if (confirm("Bạn có muốn xóa")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/tasksolutions/${id}/destroy`,
            success: function (response) {
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

$(document).on("click", ".btn-add-solution", function () {
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
        success: function (response) {
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
$(document).on("click", ".btn-edit-item", function () {
    $.ajax({
        type: "GET",
        url: "/api/taskitems/" + $(this).data("id") + "/show",
        success: function (response) {
            if (response.status == 0) {
                let taskItem = response.taskItem;
                $(".modal-title-item").text("Cập nhật vật tư");
                $("#item_id").val(taskItem.item_id);
                $("#item_unit").val(taskItem.unit);
                $("#item_kpi").val(taskItem.kpi);
                $("#item_result").val(taskItem.result);
                $("#item_detail").val(taskItem.detail);
                //
                $("#image_show_item").attr("src", taskItem.image);
                $("#image_item").val(taskItem.image);
                //
                $(".btn-add-item").css("display", "none");
                $(".btn-update-item").css("display", "block");
                $("#taskitem_id").val(taskItem.id);
            } else {
                toastr.error(response.message);
            }
        },
    });
});

$(".btn-open-modal-item").on("click", function () {
    $(".modal-title-item").text("Thêm vật tư");
    $(".btn-add-item").css("display", "block");
    $(".btn-update-item").css("display", "none");
});

$(document).on("click", ".btn-update-item", function () {
    if (confirm("Bạn có muốn sửa")) {
        let data = {
            id: $("#taskitem_id").val(),
            unit: $("#item_unit").val(),
            kpi: $("#item_kpi").val(),
            image: $("#image_item").val(),
            result: $("#item_result").val(),
            detail: $("#item_detail").val(),
            item_id: $("#item_id").val(),
        };
        $.ajax({
            type: "POST",
            url: $(this).data("url"),
            data: data,
            success: function (response) {
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

$(document).on("click", ".btn-delete-item", function () {
    if (confirm("Bạn có muốn xóa")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/taskitems/${id}/destroy`,
            success: function (response) {
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

$(document).on("click", ".btn-add-item", function () {
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
        success: function (response) {
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
$(document).on("click", ".btn-edit", function () {
    $.ajax({
        type: "GET",
        url: "/api/taskmaps/" + $(this).data("id") + "/show",
        success: function (response) {
            if (response.status == 0) {
                let taskMap = response.taskMap;
                $(".modal-title-map").text("Cập nhật sơ đồ");
                $("#map_id").val(taskMap.map_id);
                $("#kpi").val(taskMap.kpi);
                $("#unit").val(taskMap.unit);
                $("#result").val(taskMap.result);
                $("#detail").val(taskMap.detail);
                $("#code").val(taskMap.code);
                //
                $("#image_show_map").attr("src", taskMap.image);
                $("#image_map").val(taskMap.image);
                //
                $(".btn-add-map").css("display", "none");
                $(".btn-update-map").css("display", "block");
                $("#taskmap_id").val(taskMap.id);
            } else {
                toastr.error(response.message);
            }
        },
    });
});

$(".btn-open-modal").on("click", function () {
    $(".modal-title-map").text("Thêm sơ đồ");
    $("#kpi").val("");
    $("#unit").val("");
    $(".btn-add-map").css("display", "block");
    $(".btn-update-map").css("display", "none");
});

$(document).on("click", ".btn-update-map", function () {
    if (confirm("Bạn có muốn sửa")) {
        let data = {
            id: $("#taskmap_id").val(),
            unit: $("#unit").val(),
            kpi: $("#kpi").val(),
            code: $("#code").val(),
            // map_id: $("#map_id").val(),
            image: $("#image_map").val(),
            result: $("#result").val(),
            detail: $("#detail").val(),
            task_id: $("#task_id").val(),

            // target: $("#target").val(),
        };
        console.log(data);
        $.ajax({
            type: "POST",
            url: $(this).data("url"),
            data: data,
            success: function (response) {
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

$(document).on("click", ".btn-delete", function () {
    if (confirm("Bạn có muốn xóa")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/taskmaps/${id}/destroy`,

            success: function (response) {
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

// $(document).on("click", ".btn-add-map", function () {
//     let data = {
//         unit: $("#unit").val(),
//         kpi: $("#kpi").val(),
//         target: $("#target").val(),
//         task_id: $("#task_id").val(),
//         map_id: $("#map_id").val(),
//     };
//     $.ajax({
//         type: "POST",
//         data: data,
//         url: $(this).data("url"),
//         success: function (response) {
//             if (response.status == 0) {
//                 closeModal("map");
//                 dataTableMap.ajax.reload();
//                 toastr.success(response.message);
//             } else {
//                 toastr.error(response.message);
//             }
//         },
//     });
// });


// staff
$(document).on("click", ".btn-edit-staff", function () {
    $.ajax({
        type: "GET",
        url: "/api/taskstaff/" + $(this).data("id") + "/show",
        success: function (response) {
            if (response.status == 0) {
                let taskStaff = response.taskStaff;
                $(".modal-title-staff").text("Cập nhật nhân sự");
                $("#staff_id").val(taskStaff.user_id);
                //
                $(".btn-add-staff").css("display", "none");
                $(".btn-update-staff").css("display", "block");
                $("#taskstaff_id").val(taskStaff.id);
            } else {
                toastr.error(response.message);
            }
        },
    });
});

$(".btn-open-modal-staff").on("click", function () {
    $(".modal-title-staff").text("Thêm nhân sự");
    $(".btn-add-staff").css("display", "block");
    $(".btn-update-staff").css("display", "none");
});

$(document).on("click", ".btn-update-staff", function () {
    if (confirm("Bạn có muốn sửa")) {
        let data = {
            id: $("#taskstaff_id").val(),
            task_id: $("#task_id").val(),
            user_id: $("#staff_id").val(),
        };
        $.ajax({
            type: "POST",
            url: $(this).data("url"),
            data: data,
            success: function (response) {
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

$(document).on("click", ".btn-delete-staff", function () {
    if (confirm("Bạn có muốn xóa")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/taskstaff/${id}/destroy`,

            success: function (response) {
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

$(document).on("click", ".btn-add-staff", function () {
    let data = {
        task_id: $("#task_id").val(),
        user_id: $("#staff_id").val(),
    };
    $.ajax({
        type: "POST",
        data: data,
        url: $(this).data("url"),
        success: function (response) {
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
$(document).on("click", ".btn-edit-chemistry", function () {
    $.ajax({
        type: "GET",
        url: "/api/taskchemistries/" + $(this).data("id") + "/show",
        success: function (response) {
            if (response.status == 0) {
                let taskChemistry = response.taskChemistry;
                $(".modal-title-chemistry").text("Cập nhật hóa chất");
                $("#chemistry_id").val(taskChemistry.chemistry_id);
                $("#chemistry_unit").val(taskChemistry.unit);
                $("#chemistry_kpi").val(taskChemistry.kpi);
                $("#chemistry_result").val(taskChemistry.result);
                $("#chemistry_detail").val(taskChemistry.detail);
                //
                $("#image_show_chemistry").attr("src", taskChemistry.image);
                $("#image_chemistry").val(taskChemistry.image);
                //
                $(".btn-add-chemistry").css("display", "none");
                $(".btn-update-chemistry").css("display", "block");
                $("#taskchemistry_id").val(taskChemistry.id);
            } else {
                toastr.error(response.message);
            }
        },
    });
});

$(".btn-open-modal-chemistry").on("click", function () {
    $(".modal-title-chemistry").text("Thêm hóa chất");
    $(".btn-add-chemistry").css("display", "block");
    $(".btn-update-chemistry").css("display", "none");
});

$(document).on("click", ".btn-update-chemistry", function () {
    if (confirm("Bạn có muốn sửa")) {
        let data = {
            id: $("#taskchemistry_id").val(),
            unit: $("#chemistry_unit").val(),
            kpi: $("#chemistry_kpi").val(),
            image: $("#image_chemistry").val(),
            result: $("#chemistry_result").val(),
            detail: $("#chemistry_detail").val(),
            chemistry_id: $("#chemistry_id").val(),
        };
        $.ajax({
            type: "POST",
            url: $(this).data("url"),
            data: data,
            success: function (response) {
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

$(document).on("click", ".btn-delete-chemistry", function () {
    if (confirm("Bạn có muốn xóa")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/taskchemistries/${id}/destroy`,
            success: function (response) {
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

$(document).on("click", ".btn-add-chemistry", function () {
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
        success: function (response) {
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
