var dataTable = null;
$(document).ready(function () {
    reload();

    dataTable = $("#table").DataTable({
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
        },
        ajax: {
            url: "/admin/linkrunnings",
            dataSrc: "linkrunnings",
        },
        columns: [
            {
                data: "time",
            },
            {
                data: "updated_at",
            },
            // {
            //     data: "time",
            // },
            {
                data: "title",
            },
            // {
            //     data: "content",
            // },
            {
                data: function (d) {
                    return `<img style="width: 50px;height:50px" src="${d.content}" alt="image" />`;
                },
            },
            {
                data: function (d) {
                    return `${d.comment_second} | ${parseInt(d.comment_second) - parseInt(d.comment_first)}`;
                },
            },
            {
                data: function (d) {
                    return `${d.data_second} | ${parseInt(d.data_second) - parseInt(d.data_first)}`;
                },
            },
            {
                data: function (d) {
                    return `${d.emotion_second} | ${parseInt(d.emotion_second) - parseInt(d.emotion_first)}`;
                },
            },
            {
                data: "delay",
            },
            {
                data: function (d) {
                    return d.status == 0 ? `<button class="btn btn-primary btn-sm">
                                                Running
                                            </button>`
                                        : `<button class="btn btn-danger btn-sm">
                                                Error
                                            </button>`;
                },
            },
            {
                data: function (d) {
                    let btnDelete = d.id == $('#editing_link_id').val() ? `` :
                        `<button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                    return `<a class="btn btn-primary btn-sm" href='/admin/linkrunnings/update/${d.id}'>
                                <i class="fas fa-edit"></i>
                            </a>
                            <button data-id="${d.id}" class="btn btn-success btn-sm btn-reset">
                                <i class="fa-solid fa-rotate-right"></i>
                            </button>
                            ${btnDelete}`;
                },
            },
        ],
    });
});

async function reload() {
    let count = 0;
    let all = 0;
    await $.ajax({
        type: "GET",
        url: "/api/links/getAll",
        success: function (response) {
            all = response.links.length;
            if (response.status == 0) {
                response.links.forEach((e) => {
                    if (e.type == 2) {
                        count++;
                    }
                });
            }
        }
    });

    $('.count-link').text(`Tổng số link đang chạy: ${count}/${all}`);
}

$(document).on("click", ".btn-reset", function () {
    if (confirm("Bạn có muốn làm mới link này?")) {
        let id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: `/api/links/update`,
            data: {
                id,
                type: 0,
            },
            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Làm mới thành công");
                    reload();
                    dataTable.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
});

$(document).on("click", ".btn-delete", function () {
    if (confirm("Bạn có muốn xóa?")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/linkrunnings/${id}/destroy`,

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
