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
            url: `/api/links/getAll?user_id=${$('#user_id').val()}&type=0`,
            dataSrc: "links",
        },
        columns: [
            {
                data: function (d) {
                    return getDateDiffInHours(new Date(d.link.updated_at), new Date()) + "h";
                }
            },
            {
                data: function (d) {
                    return d.link.created_at;
                },
            },
            {
                data: function (d) {
                    return d.link.link_or_post_id;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-title tool-tip" data-id="${d.link.id}" data-link_or_post_id="${d.link.link_or_post_id}">${d.link.title}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-title tooltip-title-${d.link.id}">
                    </div></p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-content tool-tip" data-link_or_post_id="${d.link.link_or_post_id}" data-content="${d.link.content}">
                    <img style="width: 50px;height:50px" src="${d.link.content}" alt="image" />
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-content tooltip-content-${d.link.link_or_post_id}">
                    </div></p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="comment" data-link_or_post_id="${d.link.link_or_post_id}">${d.link.comment_second} | ${parseInt(d.link.comment_second)
                        - parseInt(d.link.comment_first)}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-comment tooltiptext-comment-${d.link.link_or_post_id}"></div></p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="data" data-link_or_post_id="${d.link.link_or_post_id}">${d.link.data_second} | ${parseInt(d.link.data_second)
                        - parseInt(d.link.data_first)}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-data tooltiptext-data-${d.link.link_or_post_id}"></div></p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="emotion" data-link_or_post_id="${d.link.link_or_post_id}">${d.link.emotion_second} | ${parseInt(d.link.emotion_second)
                        - parseInt(d.link.emotion_first)}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-emotion tooltiptext-emotion-${d.link.link_or_post_id}"></div></p>`;
                },
            },
            {
                data: function (d) {
                    return d.link.is_scan == 0 ? `<button class="btn btn-danger btn-scan" data-is_scan="1" data-id=${d.link.id}>OFF</button>`
                        : (d.link.is_scan == 1 ? `<button data-is_scan="0" data-id=${d.link.id} class="btn btn-success btn-scan">ON</button>`
                            : `<button class="btn btn-primary">RESET</button>`);
                }
            },
            {
                data: function (d) {
                    return d.link.note;
                },
            },
            {
                data: function (d) {
                    let btnDelete = d.link.id == $('#editing_link_id').val() ? `` :
                        `<button data-id="${d.link.id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                    return `<a class="btn btn-primary btn-sm" href='/user/linkscans/update/${d.link.id}'>
                                <i class="fas fa-edit"></i>
                            </a>
                            <button data-id="${d.link.id}" class="btn btn-success btn-sm btn-follow">
                                <i class="fa-solid fa-user-plus"></i>
                            </button>
                            ${btnDelete}`;
                },
            },
        ],
    });
});

var searchParams = new Map([
    ["time_from", ""],
    ["time_to", ""],
    ["data_from", ""],
    ["data_to", ""],
    ["comment_from", ""],
    ["comment_to", ""],
    ["reaction_from", ""],
    ["reaction_to", ""],
    ["from", ""],
    ["to", ""],
    // ["user", ""],
    // ["note", ""],
    ["is_scan", ""],
    ["type", ""],
]);

function getQueryUrlWithParams() {
    let query = `user_id=${$('#user_id').val()}`;
    Array.from(searchParams).forEach(([key, values], index) => {
        query += `&${key}=${typeof values == "array" ? values.join(",") : values}`;
    })

    return query;
}

$(document).on("click", ".btn-filter", function () {
    Array.from(searchParams).forEach(([key, values], index) => {
        searchParams.set(key, String($('#' + key).val()).length ? $('#' + key).val() : '');
    });
    dataTable.ajax
        .url("/api/links/getAll?" + getQueryUrlWithParams())
        .load();
});

$(document).on("click", ".btn-refresh", function () {
    // Array.from(searchParams).forEach(([key, values], index) => {
    //     if (key != 'type') {
    //         $('#' + key).val('');
    //     }
    // });

    dataTable.ajax
        .url(`/api/links/getAll?user_id=${$('#user_id').val()}&type=0`)
        .load();
});

async function reload() {
    let count = 0;
    let all = 0;
    let user_id = $('#user_id').val();

    await $.ajax({
        type: "GET",
        url: `/api/links/getAll?user_id=${user_id}`,
        success: function (response) {
            all = response.links.length;
            if (response.status == 0) {
                response.links.forEach((e) => {
                    if (e.link.type == 0) {
                        count++;
                    }
                });
            }
        }
    });

    $('.count-link').text(`Tổng số link quét: ${count}/${all}`);
}

$(document).on("click", ".btn-scan", function () {
    let is_scan = $(this).data("is_scan");
    let user_id = $('#user_id').val();
    let text = is_scan == 0 ? 'tắt' : 'mở';
    if (confirm(`Bạn có muốn ${text} quét link`)) {
        let link_id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: `/api/user/linkscans/changeIsScan`,
            data: {
                link_id,
                is_scan,
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

$(document).on("click", ".btn-follow", function () {
    if (confirm("Bạn có muốn theo dõi link này?")) {
        let id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: `/api/links/update`,
            data: {
                id,
                type: 1,
            },
            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Theo dõi thành công");
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
            url: `/api/linkscans/${id}/destroy`,

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
    }
});
