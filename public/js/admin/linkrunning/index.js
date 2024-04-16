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
            url: "/api/links/getAll?is_scan[]=1&is_scan[]=2",
            dataSrc: "links",
        },
        columns: [
            {
                data: function (d) {
                    return d.link.time;
                },
            },
            {
                data: function (d) {
                    return d.link.created_at;
                },
            },
            {
                data: function (d) {
                    return getListAccountNameByUserLink(d.accounts);
                },
            },
            {
                data: function (d) {
                    return d.link.title;
                },
            },
            {
                data: function (d) {
                    return `<img style="width: 50px;height:50px" src="${d.link.content}" alt="image" />`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history" data-type="comment" data-link_or_post_id="${d.link.link_or_post_id}">${d.link.comment_second} | ${parseInt(d.link.comment_second)
                        - parseInt(d.link.comment_first)}</p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history" data-type="data" data-link_or_post_id="${d.link.link_or_post_id}">${d.link.data_second} | ${parseInt(d.link.data_second)
                        - parseInt(d.link.data_first)}</p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history" data-type="emotion" data-link_or_post_id="${d.link.link_or_post_id}">${d.link.emotion_second} | ${parseInt(d.link.emotion_second)
                        - parseInt(d.link.emotion_first)}</p>`;
                },
            },
            {
                data: function (d) {
                    return d.link.delay;
                },
            },
            {
                data: function (d) {
                    return d.link.status == 0 ? `<button class="btn btn-primary btn-sm btn-status" data-link_id="${d.link.id}" data-status="1">
                                                Running
                                            </button>`
                        : `<button class="btn btn-danger btn-sm  btn-status" data-link_id="${d.link.id}" data-status="0">
                                                Stop
                                            </button>`;
                },
            },
            {
                data: function (d) {
                    return d.link.link_or_post_id;
                },
            },
            {
                data: function (d) {
                    let btnDelete = d.link.id == $('#editing_link_id').val() ? `` :
                        `<button data-id="${d.link.id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                    return `<a class="btn btn-primary btn-sm" href='/admin/linkrunnings/update/${d.link.id}'>
                                <i class="fas fa-edit"></i>
                            </a>
                            <button data-id="${d.link.id}" class="btn btn-success btn-sm btn-reset">
                                <i class="fa-solid fa-rotate-right"></i>
                            </button>
                            ${btnDelete}`;
                },
            },
        ],
    });
});

$(document).on("click", ".btn-status", function () {
    let status = $(this).data("status");
    let user_id = $('#user_id').val();
    let text = status == 0 ? 'chạy' : 'dừng';
    if (confirm(`Bạn có muốn ${text} link này?`)) {
        let link_id = $(this).data("link_id");
        $.ajax({
            type: "POST",
            url: `/api/links/update`,
            data: {
                id: link_id,
                status,
                user_id,
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

function getListAccountNameByUserLink(userLinks = []) {
    let rs = [];
    userLinks.forEach((e) => {
        rs.push(e.user.email || e.user.name);
    });

    return rs.join('|');
}

async function reload() {
    let count = 0;
    let all = 0;
    await $.ajax({
        type: "GET",
        url: "/api/links/getAll",
        success: function (response) {
            if (response.status == 0) {
                all = response.links.length;
                response.links.forEach((e) => {
                    if (e.link.is_scan == 1 || e.link.is_scan == 2) {
                        count++;
                    }
                });
            } else {
                toastr.error(response.message);
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
                is_scan: 2,
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
            url: `/api/links/${id}/destroy`,

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
