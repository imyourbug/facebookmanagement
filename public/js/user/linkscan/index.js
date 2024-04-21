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
            url: `/api/links/getAll?user_id=${$('#user_id').val()}&type=0`,
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
                    return getDateDiffInHours(new Date(d.link.updated_at), new Date()) + "h";
                }
            },
            {
                data: function (d) {
                    return d.link.created_at;
                    return d.link.updated_at;
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
    ["content", ""],
    ["title", ""],
    ["link_or_post_id", ""],
    ["type", ""],
]);

var isFiltering = [];

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
        .url("/api/links/getAll?" + getQueryUrlWithParams())
        .load();

    //
    await $.ajax({
        type: "GET",
        url: `/api/links/getAll?${getQueryUrlWithParams()}`,
        success: function (response) {
            if (response.status == 0) {
                tempAllRecord = response.links;
            }
        }
    });
});

$(document).on("click", ".btn-refresh", function () {
    // Array.from(searchParams).forEach(([key, values], index) => {
    //     if (key != 'type') {
    //         $('#' + key).val('');
    //     }
    // });

    // display filtering
    isFiltering = [];
    displayFiltering();

    // reload table
    dataTable.ajax
        .url(`/api/links/getAll?user_id=${$('#user_id').val()}&type=0`)
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
    let count = 0;
    let all = 0;
    let user_id = $('#user_id').val();

    await $.ajax({
        type: "GET",
        url: `/api/links/getAll?user_id=${user_id}`,
        success: function (response) {
            all = response.links.length;
            if (response.status == 0) {
                allRecord = response.links;
                response.links.forEach((e) => {
                    if (e.link.type == 0) {
                        count++;
                    }
                });
            }
        }
    });

    $('.count-link').text(`Tổng số link quét: ${count}/${all}`);
    //
    tempAllRecord = [];
    reloadAll();
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
                    tempAllRecord = [];
                    reloadAll();
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

$(document).on("click", ".btn-follow-multiple", function () {
    if (confirm("Bạn có muốn theo dõi các link đang hiển thị?")) {
        if (tempAllRecord.length) {
            $.ajax({
                type: "POST",
                url: `/api/links/updateLinkByLinkId`,
                data: {
                    ids: tempAllRecord,
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
        } else {
            toastr.error('Link trống');
        }
    }
});


$(document).on("click", ".btn-scan-multiple", function () {
    let is_scan = $(this).data("is_scan");
    let text = is_scan == 0 ? 'tắt' : (is_scan == 1 ? 'mở' : 'làm mới');
    if (confirm(`Bạn có muốn ${text} quét các link đang hiển thị?`)) {
        if (tempAllRecord.length) {
            $.ajax({
                type: "POST",
                url: `/api/links/updateLinkByLinkId`,
                data: {
                    ids: tempAllRecord,
                    is_scan,
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
