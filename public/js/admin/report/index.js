var dataTable = null;

var searchParams = new Map();

$(document).on("change", "#select-time", function () {
    if ($(this).val()) {
        let time = $(this).val().split("-");
        let year = time[0];
        let month = time[1];
        searchParams.set("month", month);
        searchParams.set("year", year);
        dataTable.ajax
            .url("/api/tasks/getAll?" + getQueryUrlWithParams())
            .load();
    }
});

$(document).on("change", ".select2", function () {
    let contracts = $(this).val();
    searchParams.set("contracts", contracts);
    dataTable.ajax
        .url("/api/tasks/getAll?" + getQueryUrlWithParams())
        .load();
});

function getQueryUrlWithParams() {
    let query = '';
    Array.from(searchParams).forEach(([key, values], index) => {
        if (index = 0) {
            query += `${key}=${typeof values == "array" ? values.join(",") : values}`;
        } else {
            query += `&${key}=${typeof values == "array" ? values.join(",") : values}`;
        }
    })

    return query;
}

$(document).ready(function () {
    // select 2
    $(".select2").select2();
    // solution
    dataTable = $("#table").DataTable({
        ajax: {
            url: "/api/tasks/getAll",
            dataSrc: "tasks",
        },
        columns: [
            // { data: "id" },
            { data: "type.name" },
            {
                data: function (d) {
                    return `${d.contract.name} - ${d.contract.branch ? d.contract.branch.name : ''}`;
                },
            },
            { data: "note" },
            { data: "created_at" },
            {
                data: function (d) {
                    return `<a class="btn btn-primary btn-sm btn-edit" data-id="${d.id}" data-target="#modal" data-toggle="modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a class="btn btn-success btn-sm" style="padding: 4px 15px" href="/admin/reports/task/${d.id}">
                                                    <i class="fa-solid fa-info"></i>
                                                </a>`;
                    // return `<a class="btn btn-primary btn-sm btn-edit" data-id="${d.id}" data-target="#modal" data-toggle="modal">
                    //                             <i class="fas fa-edit"></i>
                    //                         </a>
                    //                         <a class="btn btn-success btn-sm" style="padding: 4px 15px" href="/admin/reports/task/${d.id}">
                    //                             <i class="fa-solid fa-info"></i>
                    //                         </a>
                    //                         <button data-id="${d.id}"
                    //                             class="btn btn-danger btn-sm btn-delete">
                    //                             <i class="fas fa-trash"></i>
                    //                         </button>`;
                },
            },
        ],
    });
});

$(document).on("click", ".btn-edit", function () {
    $.ajax({
        type: "GET",
        url: "/api/tasks/" + $(this).data("id") + "/getById",
        success: function (response) {
            if (response.status == 0) {
                let task = response.task;
                $("#type_id").val(task.type_id);
                $("#contract_id").val(task.contract_id);
                $("#note").text(task.note);
                $("#task_id").val(task.id);
            } else {
                toastr.error(response.message);
            }
        },
    });
});

$(document).on("click", ".btn-update", function () {
    if (confirm("Bạn có muốn sửa")) {
        let data = {
            id: $("#task_id").val(),
            note: $("#note").val(),
            contract_id: $("#contract_id").val(),
            type_id: $("#type_id").val(),
        };
        console.log(data);
        $.ajax({
            type: "POST",
            url: $(this).data("url"),
            data: data,
            success: function (response) {
                if (response.status == 0) {
                    closeModal("solution");
                    toastr.success("Cập nhật thành công");
                    dataTable.ajax.reload();
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
            url: `/api/tasks/${id}/destroy`,

            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Xóa thành công");
                    dataTable.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
});
