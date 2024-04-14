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
            url: "/admin/comments",
            dataSrc: "comments",
        },
        columns: [
            {
                data: "created_at",
            },
            // {
            //     data: "created_at",
            // },
            {
                data: "title",
            },
            {
                data: function (d) {
                    return `<span class="copy" data-value="${d.uid}">${d.uid}</span>`;
                },
            },
            {
                data: "phone",
            },
            {
                data: "content",
            },
            {
                data: "note",
            },
            {
                data: function (d) {
                    return `<button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                },
            },
        ],
    });
});

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
        url: "/api/comments",
        success: function (response) {
            if (response.status == 0) {
                $('.count-comment').text(`Tổng số bình luận: ${response.comments.length}`);
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
            .url("/api/comments?" + getQueryUrlWithParams())
            .load();
    }
    else if (!$('#from').val()) {
        dataTable.ajax.url("/api/comments").load();
    }
});

$(document).on("change", "#from", function () {
    if ($(this).val()) {
        let time = $(this).val();
        searchParams.set("from", time);
        dataTable.ajax
            .url("/api/comments?" + getQueryUrlWithParams())
            .load();
    }
    else if (!$('#to').val()) {
        dataTable.ajax.url("/api/comments").load();
    }
});

$(document).on("change", ".select2", function () {
    let contracts = $(this).val();
    searchParams.set("contracts", contracts);
    dataTable.ajax
        .url("/api/comments?" + getQueryUrlWithParams())
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
