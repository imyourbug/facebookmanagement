function getStatusContract(endTime = "") {
    const endDate = new Date(endTime);
    const now = new Date();
    const days = Math.ceil((endDate - now) / (1000 * 60 * 60 * 24));
    let renderStatus = "";
    switch (true) {
        case days > 0 && days <= 30:
            renderStatus =
                '<span class="btn btn-warning">Hết hạn trong ' +
                days +
                " ngày</span>";
            break;
        case days <= 0:
            renderStatus = '<span class="btn btn-danger">Hết hạn</span>';
            break;
        case days > 30:
            renderStatus = '<span class="btn btn-success">Còn hạn</span>';
            break;
        default:
            break;
    }

    return renderStatus;
}

function getActive(active = "") {
    let renderActive = "";
    switch (active) {
        case 0:
            renderActive = '<span class="btn btn-danger">Không</span>';
            break;
        case 1:
            renderActive = '<span class="btn btn-success">Có</span>';
            break;
        default:
            break;
    }

    return renderActive;
}

$(document).on("click", ".btn-restore-db", function () {
    $("#file-restore-db").click();
});

$(document).on("change", "#file-restore-db", function () {
    const form = new FormData();
    form.append("file", $(this)[0].files[0]);
    $.ajax({
        processData: false,
        contentType: false,
        type: "POST",
        data: form,
        url: "/api/restore",
        success: function (response) {
            if (response.status == 0) {
                $("#file-restore-db").val("");
                toastr.success(response.message, "Thông báo");
            } else {
                toastr.error(response.message, "Thông báo");
            }
        },
    });
});

function getDateDiffInHours(date1, date2) {
    // Convert dates to milliseconds since epoch
    const timeDiffInMs = date2.getTime() - date1.getTime();

    // Convert milliseconds to days (divide by 1000 milliseconds/second, 60 seconds/minute, 60 minutes/hour, 24 hours/day)
    const dayDiff = timeDiffInMs / (1000 * 60 * 60);

    return Math.floor(dayDiff); // Round down to whole days
}
