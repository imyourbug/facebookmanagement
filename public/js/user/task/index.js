var dataTable = null;

function closeModal() {
    $("#modal").css("display", "none");
    $("body").removeClass("modal-open");
    $(".modal-backdrop").remove();
}

$(document).ready(function () {
    dataTable = $("#table").DataTable({
        ajax: {
            url: "/api/tasks/getAll?user_id=" + $('#user_id').val(),
            dataSrc: "tasks",
        },
        columns: [
            // { data: "id" },
            { data: "type.name" },
            {
                data: function (d) {
                    return `${d.contract.name} - ${d.contract.branch ? d.contract.branch.name : ''}`;
                },
            },
            { data: "note" },
            { data: "created_at" },
            {
                data: function (d) {
                    return `<a class="btn btn-success btn-sm" style="padding: 4px 15px" href="/user/tasks/${d.id}">
                                <i class="fa-solid fa-info"></i>
                            </a>`;
                },
            },
        ],
    });
});
