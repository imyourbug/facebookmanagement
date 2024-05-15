var dataTable = null;
var allRecord = [];
var tempAllRecord = [];
$(document).ready(function () {
    reload();

    dataTable = $("#table").DataTable({
        columnDefs: [
            // { visible: false, targets: 0 },
            { visible: false, targets: 1 },
        ],
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
            url: "/api/links/getAll?is_scan[]=1",
            dataSrc: "links",
        },
        columns: [
            {
                data: function (d) {
                    return `<input class="btn-select" type="checkbox" data-id="${d.link.id}" data-link_or_post_id="${d.link.link_or_post_id}" />`;
                }
            },
            {
                data: function (d) {
                    return d.link.link_or_post_id;
                },
            },
            {
                data: function (d) {
                    let commentLink = d.link.comment_links ? d.link.comment_links[0] : '';
                    return commentLink ? getDateDiffInHours(new Date(commentLink.created_at), new Date()) : 'Trống';
                }
            },
            {
                data: function (d) {
                    return d.created_at;
                    return d.updated_at;
                },
            },
            {
                data: function (d) {
                    return getListAccountNameByUserLink(d.accounts);
                },
            },
            {
                data: function (d) {
                    return `<p class="show-title tool-tip" data-id="${d.id}" data-link_or_post_id="${d.link.link_or_post_id}">${d.title || d.link.title}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-title tooltip-title-${d.id}">
                    </div></p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-content tool-tip" data-id="${d.id}" data-link_or_post_id="${d.link.link_or_post_id}" data-content="${d.link.content}">
                    <img style="width: 50px;height:50px" src="${d.link.content}" alt="image" />
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-content tooltip-content-${d.id}">
                    </div></p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="comment" data-id="${d.id}" data-link_or_post_id="${d.link.link_or_post_id}">${d.link.comment}  ${getCountation(d.link.diff_comment)}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-comment tooltiptext-comment-${d.id}"></div></p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="data" data-id="${d.id}" data-link_or_post_id="${d.link.link_or_post_id}">${d.link.data}  ${getCountation(parseInt(d.link.diff_data))}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-data tooltiptext-data-${d.id}"></div></p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="emotion" data-id="${d.id}" data-link_or_post_id="${d.link.link_or_post_id}">${d.link.reaction}  ${getCountation(parseInt(d.link.diff_reaction))}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-emotion tooltiptext-emotion-${d.id}"></div></p>`;
                },
            },
            // {
            //     data: function (d) {
            //         return d.is_scan == 0 ? `<button class="btn btn-danger btn-scan btn-sm" data-is_scan="1" data-id=${d.link.id}>OFF</button>`
            //             : (d.is_scan == 1 ? `<button data-is_scan="0" data-id=${d.link.id} class="btn btn-success btn-scan btn-sm">ON</button>`
            //                 : `<button class="btn btn-warning btn-sm">ERROR</button>`);
            //     }
            // },
            {
                data: function (d) {
                    return d.link.delay;

                }
            },
            {
                data: function (d) {
                    return d.link.status == 1 ? `<button class="btn btn-primary btn-sm btn-status" data-link_id="${d.link.id}" data-status="0">
                                                Running
                                            </button>`
                        : `<button class="btn btn-danger btn-sm  btn-status" data-link_id="${d.link.id}" data-status="1">
                                                Stop
                                            </button>`;
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
                            </button>`;
                },
            },
        ],
    });
});

var searchParams = new Map([
    ["time_from", ""],
    ["time_to", ""],
    ["last_data_from", ""],
    ["last_data_to", ""],
    ["data_from", ""],
    ["data_to", ""],
    ["comment_from", ""],
    ["comment_to", ""],
    ["reaction_from", ""],
    ["reaction_to", ""],
    ["from", ""],
    ["to", ""],
    ["content", ""],
    ["title", ""],
    ["link_or_post_id", ""],
    ["type", ""],
    ["user", ""],
    ["delay_from", ""],
    ["delay_to", ""],
    ["status", ""],
]);

var isFiltering = [];

function getQueryUrlWithParams() {
    let query = 'user_id=';
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
    let link_or_post_id = $(this).data("link_or_post_id");
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
        .url("/api/links/getAll?is_scan[]=1&" + getQueryUrlWithParams())
        .load();
    //
    await $.ajax({
        type: "GET",
        url: `/api/links/getAll?is_scan[]=1&${getQueryUrlWithParams()}`,
        success: function (response) {
            if (response.status == 0) {
                response.links.forEach((e) => {
                    tempAllRecord.push(e.link.id);
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
        .url(`/api/links/getAll?is_scan[]=1`)
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

async function reload() {
    let count = $('#number-link').val();
    let all = 0;
    let user_id = $('#user_id').val();

    await $.ajax({
        type: "GET",
        url: "/api/links/getAll?is_scan[]=1",
        success: function (response) {
            all = response.links.length;
        }
    });

    $('.count-link').text(`Số luồng: ${count}/${all}`);
    //
    tempAllRecord = [];
    reloadAll();
}

$(document).on("click", ".btn-scan", function () {
    let is_scan = $(this).data("is_scan");
    let user_id = $('#user_id').val();
    let text = is_scan == 0 ? 'tắt' : 'mở';
    if (confirm(`Bạn có muốn ${text} quét link`)) {
        let id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: `/api/links/update`,
            data: {
                id,
                is_scan,
                user_id,
            },
            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Cập nhật thành công");
                    dataTable.ajax.reload();
                    tempAllRecord = [];
                    reloadAll();
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
});


$(document).on("click", ".btn-delay-multiple", function () {
    if (confirm("Bạn có muốn cập nhật các link đang hiển thị?")) {
        if (tempAllRecord.length) {
            let delay = $('#delay-edit').val();
            $.ajax({
                type: "POST",
                url: `/api/links/updateLinkByListLinkId`,
                data: {
                    ids: tempAllRecord,
                    delay,
                },
                success: function (response) {
                    if (response.status == 0) {
                        toastr.success("Cập nhật thành công");
                        reload();
                        dataTable.ajax.reload();
                        //
                        closeModal('modalEdit');
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

$(document).on("click", ".btn-stop-multiple", function () {
    if (confirm("Bạn có muốn dừng các link đang hiển thị?")) {
        if (tempAllRecord.length) {
            $.ajax({
                type: "POST",
                url: `/api/links/updateLinkByListLinkId`,
                data: {
                    ids: tempAllRecord,
                    status: 0,
                },
                success: function (response) {
                    if (response.status == 0) {
                        toastr.success("Dừng thành công");
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

$(document).on("click", ".btn-run-multiple", function () {
    if (confirm("Bạn có muốn chạy các link đang hiển thị?")) {
        if (tempAllRecord.length) {
            $.ajax({
                type: "POST",
                url: `/api/links/updateLinkByListLinkId`,
                data: {
                    ids: tempAllRecord,
                    status: 1,
                },
                success: function (response) {
                    if (response.status == 0) {
                        toastr.success("Chạy thành công");
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

$(document).on("click", ".btn-delete-multiple", function () {
    if (confirm("Bạn có muốn xóa các link đang hiển thị?")) {
        if (tempAllRecord.length) {
            $.ajax({
                type: "POST",
                url: `/api/links/deleteAll`,
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

function getListAccountNameByUserLink(userLinks = []) {
    let rs = [];
    userLinks.forEach((e) => {
        rs.push(e.user.email || e.user.name);
    });

    return rs.join('|');
}

$(document).on("click", ".btn-status", function () {
    let status = $(this).data("status");
    let user_id = $('#user_id').val();
    let text = status == 1 ? 'chạy' : 'dừng';
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

$(document).on("click", ".btn-reset", function () {
    if (confirm("Bạn có muốn làm mới link này?")) {
        let id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: `/api/links/update`,
            data: {
                id,
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
