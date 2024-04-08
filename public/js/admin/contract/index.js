var dataTable = null;
$(document).ready(function () {
    $(".select2").select2();
    dataTable = $("#table").DataTable({
        layout: {
            topStart: {
                buttons: [{
                    extend: "excel",
                    text: "Xuất Excel",
                    exportOptions: {
                        columns: ":not(:last-child)",
                    },
                },
                    "colvis",
                ],
            },
        },
        ajax: {
            url: "/api/contracts/getAll",
            dataSrc: "contracts",
        },
        columns: [
            // {
            //     data: "id"
            // },
            {
                data: function (d) {
                    return `${d.customer.name ? d.customer.name : ""}`;
                },
            },
            {
                data: "name"
            },
            {
                data: function (d) {
                    return `${d.branch ? d.branch ? d.branch.name : "" : ""}`;
                },
            },
            {
                data: "content"
            },
            {
                data: "start"
            },
            {
                data: "finish"
            },
            {
                data: function (d) {
                    return `${getStatusContract(d.finish)}`;
                },
            },
            {
                data: function (d) {
                    return `<a class="btn btn-primary btn-sm" href='/admin/contracts/detail/${d.id}'>
                                <i class="fas fa-edit"></i>
                            </a>
                            <button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                },
            },
        ],
    });
})

$(document).on("click", ".btn-delete", function () {
    if (confirm("Bạn có muốn xóa")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/contracts/${id}/destroy`,
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

$(document).on("click", ".btn-delete-task", function () {
    if (confirm("Bạn có muốn xóa")) {
        let task_type = $(this).data("type");
        let dataStorage = JSON.parse(localStorage.getItem("data")) ?? [];
        dataStorage = dataStorage.filter((e) => e.task_type != task_type);
        localStorage.setItem("data", JSON.stringify(dataStorage));
        // render text info
        renderListTaskOpted();
    }
});

function renderDate() {
    let html = '<option value="0">Cuối tháng</option>';
    for (let index = 1; index <= 31; index++) {
        html += `<option value="${index}">${index}</option>`;
    }

    return html;
}

function renderDay() {
    return `<option value="Monday">Thứ hai</option>
            <option value="Tuesday">Thứ ba</option>
            <option value="Wednesday">Thứ tư</option>
            <option value="Thursday">Thứ năm</option>
            <option value="Friday">Thứ sáu</option>
            <option value="Saturday">Thứ bảy</option>
            <option value="Sunday">Chủ nhật</option>`;
}

function renderListTaskOpted() {
    let dataStorage = JSON.parse(localStorage.getItem("data")) ?? [];
    let html = "";
    dataStorage.forEach((info) => {
        html += `<p> <button data-type="${info.task_type}" class="btn btn-danger btn-sm btn-delete-task">
                        <i class="fas fa-trash"></i>
                    </button>
                    ${info.name_type}: ${info.value_time_type}
                </p>`;
    });
    $(".info-task").html(html);


}

function renderOption(type, id_type) {
    let html = `<div class="option-select">
        ${type == "day" ? "Chọn thứ (hàng tuần)" : "Chọn ngày (hàng tháng)"}
        <select multiple="multiple" id="value-select-type-${id_type}" class="select2 custom-select form-control-border select">
            ${type == "day" ? renderDay() : renderDate()}
            </select>
            </div>`;
    $(".option-type-" + id_type).append(html);
    $(".select2").select2();
}

// $(document).on('click', '.btn-open-modal', function () {

// })

//btn save
$(document).on("click", ".btn-save", function () {
    let dataStorage = JSON.parse(localStorage.getItem("data")) || [];

    let types_is_selected = $(".form-type")
        .find("input")
        .map(function () {
            if ($(this).is(":checked")) {
                return $(this).data("type");
            }
        })
        .get();

    // get value type time
    let info_tasks = [];
    types_is_selected.forEach((e) => {
        let dataValueSelectType = [];
        $("#value-select-type-" + e).select2("data").forEach((e) => {
            dataValueSelectType.push(e.id);
        });
        let _info = {
            task_type: e,
            time_type: $(".select-type-" + e).find("option:selected").val(),
            name_type: $("#type_" + e).data("name"),
            value_time_type: dataValueSelectType,
        };
        console.log($(".select-type-" + e).find("option:selected").val());

        if (_info.value_time_type.length > 0) {
            info_tasks.push(_info);
        }
    });

    //push data to storage
    dataStorage = [...dataStorage, ...info_tasks].reverse().filter((v, i, a) => a.findIndex(v2 => (v2.task_type === v.task_type)) === i);
    localStorage.setItem("data", JSON.stringify(dataStorage));

    // render text info
    renderListTaskOpted();
});

$(document).on("click", ".option-type", function () {
    let name = $(this).data("name");
    let id = $(this).data("type");
    if (this.checked) {
        if (!$("div.option-type").length) {
            $(".modal-option-task").append(`<div class="row option option-${id}">
                                        <div class="col-lg-12">
                                            <div class="form-group option-type-${id}" style="align-items: center">
                                                <label for="menu">${name} theo</label>
                                                <select class="custom-select form-control-borders select-type select-type-${id}" data-type="${id}">
                                                    <option value="day">Thứ</option>
                                                    <option value="date">Ngày</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>`);
            renderOption(
                $(".select-type-" + id)
                    .find(":selected")
                    .val(),
                id
            );
        }
    } else {
        $(".option-" + id).remove();
    }
});

// select type
$(document).on("change", ".select-type", function () {
    let type_time = $(this).find(":selected").val();
    let id = $(this).data("type");
    $(".option-type-" + id)
        .find(".option-select")
        .remove();
    renderOption(type_time, id);
});

//select parent type
$(".select-parent-type").on("change", function () {
    $(".option").remove();
    let parent_id = $(this).val();
    if (parent_id) {
        $.ajax({
            type: "GET",
            url: $(this).data("url") + "?id=" + parent_id,
            success: function (response) {
                let html = "";
                response.data.forEach((e) => {
                    html += `<div class="custom-control custom-checkbox">
                    <input type="checkbox" data-name="${e.name}" data-type="${e.id}" id="type_${e.id}"
                        class="option-type custom-control-input">
                    <label class="custom-control-label" for="type_${e.id}">${e.name}</label>
                </div>`;
                });
                $(".form-type").html("");
                $(".form-type").append(html);
            },
        });
    } else {
        $(".form-type").html("");
    }
});

//select customer
$(".select-customer").on("change", function () {
    reset();
    let id_customer = $(this).val();
    if (id_customer) {
        $.ajax({
            type: "GET",
            url: $(this).data("url") + "?id=" + id_customer,
            success: function (response) {
                $(".select-branch").html('');
                let html = "";
                response.data.forEach((e) => {
                    html += `<option value="${e.id}">${e.name}</option>`;
                });
                $(".select-branch").append(html);
            },
        });
    } else {
        $(".select-branch").html('');
    }
});

//add
$(document).on("click", ".btn-create", function () {
    if (confirm("Xác nhận tạo hợp đồng mới?")) {
        let data = JSON.parse(localStorage.getItem("data")) || [];
        let branch_ids = [];
        $("#branch_id").select2("data").forEach((e) => {
            branch_ids.push(e.id);
        });
        let params = {
            customer_id: $("#customer_id").val(),
            name: $("#name").val(),
            start: $("#start").val(),
            finish: $("#finish").val(),
            content: $("#content").val(),
            branch_ids: branch_ids,
            attachment: $("#value-attachment").val(),
            tasks: data,
        };
        $.ajax({
            type: "POST",
            url: $(this).data("url"),
            data: params,
            success: function (response) {
                response.status == 0
                    ? toastr.success(response.message)
                    : toastr.error(response.message);
                reset();
                dataTable.ajax.reload();
            },
        });
    }
});

//reset localStorage
function reset() {
    localStorage.removeItem("data");
    $(".info-branch").html("");
    $(".info-task").html("");
}
reset();
