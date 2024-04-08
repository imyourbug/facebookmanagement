var dataTable = null;

function closeModal() {
    $("#modal").css("display", "none");
    $("body").removeClass("modal-open");
    $(".modal-backdrop").remove();
}

$(document).ready(function () {
    // solution
    dataTable = $("#table").DataTable({
        ajax: {
            url: "/api/taskdetails?id=" + $("#task_id").val(),
            dataSrc: "taskDetails",
        },
        columns: [
            // { data: "id" },
            { data: "task.type.name" },
            // { data: function (d) {
            //     return `${formatDate(d.plan_date)}`
            // } },
            { data: "plan_date" },
            { data: "actual_date" },
            { data: "time_in" },
            { data: "time_out" },
            { data: "created_at" },
            {
                data: function (d) {
                    return `<a class="btn btn-success btn-sm" style="padding: 4px 15px" href="/customer/taskdetails/${d.id}">
                                <i class="fa-solid fa-info"></i>
                            </a>`;
                },
            },
        ],
    });
});

function formatDate(date) {
    const dateObj = new Date(date);
    const formattedDate = dateObj.toLocaleDateString();

    console.log(formattedDate);

    return formattedDate;
}
