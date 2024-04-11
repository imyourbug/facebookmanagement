var dataTable = null;
$(document).ready(function () {
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
            url: "/admin/linkscans",
            dataSrc: "linkscans",
        },
        columns: [
            {
                data: "time",
            },
            {
                data: "created_at",
            },
            {
                data: "time",
            },
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
                data: function (d) {
                    return d.is_scan == 1 ? `<button class="btn btn-danger btn-scan" data-is_scan="0" data-id=${d.id}>Tắt</button>`
                        : `<button data-is_scan="1" data-id=${d.id} class="btn btn-success btn-scan">Bật</button>`;
                }
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
                    return `<a class="btn btn-primary btn-sm" href='/admin/linkscans/update/${d.id}'>
                                <i class="fas fa-edit"></i>
                            </a>
                            <button data-id="${d.id}" class="btn btn-success btn-sm btn-follow">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                            ${btnDelete}`;
                },
            },
        ],
    });
});

$(document).on("click", ".btn-scan", function () {
    let is_scan = $(this).data("is_scan");
    let text = is_scan == 0 ? 'tắt' : 'mở';
    if (confirm(`Bạn có muốn ${text} quét link`)) {
        let id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: `/api/linkscans/changeIsScan`,
            data: {
                id,
                is_scan,
            },
            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Cập nhật thành công");
                    dataTable.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
});

$(document).on("click", ".btn-follow", function () {
    if (confirm("Bạn có muốn theo dõi link này?")) {
        let id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: `/api/linkscans/follow`,
            data: {
                id,
            },
            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Theo dõi thành công");
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
            url: `/api/linkscans/${id}/destroy`,

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
