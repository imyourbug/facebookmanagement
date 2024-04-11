var dataTable = null;
var searchParams = new Map();

$(document).ready(function () {
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
            {
                data: "created_at",
            },
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
            // {
            //     data: function (d) {
            //         let btnDelete = d.id == $('#editing_link_id').val() ? `` :
            //             `<button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
            //                     <i class="fas fa-trash"></i>
            //                 </button>`;
            //         return `<a class="btn btn-primary btn-sm" href='/admin/comments/update/${d.id}'>
            //                     <i class="fas fa-edit"></i>
            //                 </a>
            //                 ${btnDelete}`;
            //     },
            // },
        ],
    });
});

$(document).on("click", ".copy", function () {
    let value = $(this).data("value");
    navigator.clipboard.writeText(value);
    toastr.success("Đã sao chép", "Thông báo");
});

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
