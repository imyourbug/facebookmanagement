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
            url: "/admin/linkfollows",
            dataSrc: "linkfollows",
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
                data: "note",
            },
            {
                data: function (d) {
                    let btnDelete = d.id == $('#editing_link_id').val() ? `` :
                        `<button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                    return `<a class="btn btn-primary btn-sm" href='/admin/linkfollows/update/${d.id}'>
                                <i class="fas fa-edit"></i>
                            </a>
                            <button data-id="${d.id}" class="btn btn-success btn-sm btn-scan">
                                <i class="fa-solid fa-barcode"></i>
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
                    if (e.type == 1) {
                        count++;
                    }
                });
            }
        }
    });

    $('.count-link').text(`Tổng số link theo dõi: ${count}/${all}`);
}

$(document).on("click", ".btn-scan", function () {
    if (confirm("Bạn có muốn quét link này?")) {
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
                    toastr.success("Quét thành công");
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
            url: `/api/linkfollows/${id}/destroy`,

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
