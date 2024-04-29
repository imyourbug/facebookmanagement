var dataTable = null;
var allRecord = [];
var tempAllRecord = [];

$(document).ready(function () {
    reload();

    dataTable = $("#table").DataTable({
        lengthMenu: [
            [100, 250, 500],
            [100, 250, 500]
        ],
        layout: {
            topStart: {
                buttons: [
                    {
                        extend: "excel",
                        text: "Xuất Excel",
                        exportOptions: {
                            columns: ":not(:last-child)",
                        },
                    },
                    "colvis",
                ],
            },
            top2Start: 'pageLength',
        },
        ajax: {
            url: `/api/comments/getAll?user_id=${$('#user_id').val()}`,
            dataSrc: "comments",
        },
        columns: [
            {
                data: function (d) {
                    return `<input class="btn-select" type="checkbox" data-id="${d.comment.id}" />`;
                }
            },
            {
                data: function (d) {
                    return d.comment.created_at;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-title tool-tip" data-content="${d.comment.content}" data-link_or_post_id="${d.link.link_or_post_id}" data-id="${d.comment.id}">${d.comment.title}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-title tooltip-title-${d.comment.id}">
                    </div></p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-name_facebook tool-tip" data-id="${d.comment.id}" data-value="${d.comment.uid}" data-uid="${d.comment.uid}">${d.comment.name_facebook || ''}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-name_facebook tooltip-name_facebook-${d.comment.id}">
                    </div></p>`;
                },
            },
            {
                data: function (d) {
                    return d.comment.phone;
                },
            },
            {
                data: function (d) {
                    return d.comment.content;
                },
            },
            {
                data: function (d) {
                    return d.comment.note;
                },
            },
            {
                data: function (d) {
                    return `<button class="btn btn-sm btn-primary btn-edit" data-note="${d.comment.note}"
                            data-target="#modalEditComment" data-toggle="modal" data-id=${d.comment.id}>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button data-id="${d.comment.id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                },
            },
        ],
    });
});

var searchParams = new Map([
    ["from", ""],
    ["to", ""],
    ["content", ""],
    ["phone", ""],
    ["note", ""],
    ["uid", ""],
    ["name_facebook", ""],
    ["title", ""],
    ["link_or_post_id", ""],
]);

var isFiltering = [];

$(document).on('click', '.btn-edit', function () {
    let id = $(this).data('id');
    let note = $(this).data('note');
    $('#note-edit').val(note);
    $('#id-editting').val(id);
});

$(document).on('click', '.btn-save', function () {
    let id = $('#id-editting').val();
    let note = $('#note-edit').val();
    $.ajax({
        type: "POST",
        url: `/api/comments/updateById`,
        data: {
            id,
            note
        },
        success: function (response) {
            if (response.status == 0) {
                toastr.success("Cập nhật thành công");
                dataTable.ajax.reload();
                reload();
                //
                closeModal('modalEditComment');
            } else {
                toastr.error(response.message);
            }
        },
    });
});

function getQueryUrlWithParams() {
    let query = `user_id=${$('#user_id').val()}`;
    Array.from(searchParams).forEach(([key, values], index) => {
        query += `&${key}=${typeof values == "array" ? values.join(",") : values}`;
    })

    return query;
}

function reloadAll() {
    // enable or disable button
    $('.btn-control').prop('disabled', tempAllRecord.length ? false : true);
    $('.count-select').text(`Đã chọn: ${tempAllRecord.length}`);
}

$(document).on("click", ".btn-select-all", function () {
    tempAllRecord = [];
    if ($(this).is(':checked')) {
        $('.btn-select').each(function () {
            if ($(this).is(':checked')) {
                $(this).prop('checked', false);
            } else {
                $(this).prop('checked', true);
                tempAllRecord.push($(this).data('id'));
            }
        });
    } else {
        $('.btn-select').each(function () {
            $(this).prop('checked', false);
        });
    }
    reloadAll();
    console.log(tempAllRecord);
});

$(document).on("click", ".btn-select", async function () {
    let id = $(this).data("id");
    if ($(this).is(':checked')) {
        if (!tempAllRecord.includes(id)) {
            tempAllRecord.push(id);
        }
    } else {
        tempAllRecord = tempAllRecord.filter((e) => e != id);
    }
    console.log(tempAllRecord);
    reloadAll();
});

$(document).on("click", ".btn-filter", async function () {
    isFiltering = [];
    tempAllRecord = [];
    Array.from(searchParams).forEach(([key, values], index) => {
        searchParams.set(key, String($('#' + key).val()).length ? $('#' + key).val() : '');
        if ($('#' + key).val() && $('#' + key).attr('data-name')) {
            isFiltering.push($('#' + key).attr('data-name'));
        }
    });
    // display filtering
    displayFiltering();

    // reload
    // dataTable.clear().rows.add(tempAllRecord).draw();
    dataTable.ajax
        .url("/api/comments/getAll?" + getQueryUrlWithParams())
        .load();

    //
    await $.ajax({
        type: "GET",
        url: `/api/comments/getAll?${getQueryUrlWithParams()}`,
        success: function (response) {
            if (response.status == 0) {
                response.comments.forEach((e) => {
                    tempAllRecord.push(e.comment.id);
                });
            }
        }
    });

    // auto selected
    tempAllRecord.forEach((e) => {
        $(`.btn-select[data-id="${e}"]`).prop('checked', true);
    });
    $('.btn-select-all').prop('checked', true);
    // reload all
    reloadAll();
});

$(document).on("click", ".btn-refresh", function () {
    Array.from(searchParams).forEach(([key, values], index) => {
        $('#' + key).val('');
    });

    // display filtering
    isFiltering = [];
    displayFiltering();

    // reload table
    dataTable.ajax
        .url(`/api/comments/getAll?user_id=${$('#user_id').val()}&type=1`)
        .load();

    // reload count and record
    reload();
    // reload all
    reloadAll();
});

function displayFiltering() {
    isFiltering = isFiltering.filter(function (item, pos, self) {
        return self.indexOf(item) == pos;
    });
    // isFiltering.forEach((e) => {
    //     console.log(e);
    //     html += `<button class="btn btn-warning">${e}</button>`;
    // });
    $('.filtering').text(`Lọc theo: ${isFiltering.join(',')}`);

}

$(document).on("click", ".btn-delete", function () {
    if (confirm("Bạn có muốn xóa?")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/comments/${id}/destroy`,
            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Xóa thành công");
                    dataTable.ajax.reload();
                    reload();
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
});

async function reload() {
    await $.ajax({
        type: "GET",
        url: `/api/comments/getAll?user_id=${$('#user_id').val()}`,
        success: function (response) {
            if (response.status == 0) {
                $('.count-comment').text(`Tổng số bình luận: ${response.comments.length}`);
            }
        }
    });

    //
    tempAllRecord = [];
    reloadAll();
}

$(document).on("click", ".btn-delete-multiple", function () {
    if (confirm("Bạn có muốn xóa các comment đang hiển thị?")) {
        if (tempAllRecord.length) {
            $.ajax({
                type: "POST",
                url: `/api/comments/deleteAll`,
                data: { ids: tempAllRecord },
                success: function (response) {
                    if (response.status == 0) {
                        toastr.success("Xóa thành công");
                        reload();
                        dataTable.ajax.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        } else {
            toastr.error('Link trống');
        }
    }
});
