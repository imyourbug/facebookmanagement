var dataTable = null;
var searchParams = new Map();

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
            url: `/api/user/reactions/getAll?user_id=${$('#user_id').val()}`,
            dataSrc: "reactions",
        },
        columns: [
            {
                data: function (d) {
                    return d.reaction.created_at;
                },
            },
            {
                data: function (d) {
                    return d.reaction.title;
                },
            },
            {
                data: function (d) {
                    return `<span class="copy" data-value="${d.reaction.uid}">${d.reaction.uid}</span>`;
                },
            },
            {
                data: function (d) {
                    return d.reaction.phone;
                },
            },
            {
                data: function (d) {
                    return d.reaction.reaction;
                },
            },
            {
                data: function (d) {
                    return d.reaction.note;
                },
            },
            {
                data: function (d) {
                    return `<button data-id="${d.reaction.id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                },
            },
        ],
    });
});

function getListAccountNameByUserLink(userLinks = []) {
    let rs = [];
    userLinks.forEach((e) => {
        rs.push(e.user.email || e.user.name);
    });

    return rs.join('|');
}

$(document).on("click", ".btn-delete", function () {
    if (confirm("Bạn có muốn xóa?")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/reactions/${id}/destroy`,
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
        url: "/api/reactions",
        success: function (response) {
            if (response.status == 0) {
                $('.count-comment').text(`Tổng số cảm xúc: ${response.reactions.length}`);
            }
        }
    });

}

$(document).on("click", ".copy", function () {
    let value = $(this).data("value");
    navigator.clipboard.writeText(value);
    toastr.success("Đã sao chép", "Thông báo");
});

$(document).on("change", "#to", function () {
    if ($(this).val()) {
        let time = $(this).val();
        searchParams.set("to", time);
        dataTable.ajax
            .url(`/api/user/comments/getAll` + getQueryUrlWithParams())
            .load();
    }
    else if (!$('#from').val()) {
        dataTable.ajax.url("/api/reactions").load();
    }
});

$(document).on("change", "#from", function () {
    if ($(this).val()) {
        let time = $(this).val();
        searchParams.set("from", time);
        dataTable.ajax
            .url(`/api/user/comments/getAll` + getQueryUrlWithParams())
            .load();
    }
    else if (!$('#to').val()) {
        dataTable.ajax.url("/api/reactions").load();
    }
});

$(document).on("change", ".select2", function () {
    let contracts = $(this).val();
    searchParams.set("contracts", contracts);
    dataTable.ajax
        .url(`/api/user/comments/getAll` + getQueryUrlWithParams())
        .load();
});

function getQueryUrlWithParams() {
    let query = `user_id=${$('#user_id').val()}`;
    Array.from(searchParams).forEach(([key, values], index) => {
        if (index = 0) {
            query += `${key}=${typeof values == "array" ? values.join(",") : values}`;
        } else {
            query += `&${key}=${typeof values == "array" ? values.join(",") : values}`;
        }
    })

    return query;
}
