var dataTable = null;
$(document).ready(function () {
    dataTable = $("#table").DataTable({
        ajax: {
            url: "/admin/chemistries",
            dataSrc: "chemistries",
        },
        columns: [
            // {
            //     data: "id"
            // },
            {
                data: function (d) {
                    return `HC-${d.id.toString().padStart(3, "0")}`;
                },
            },
            {
                data: "name"
            },
            {
                data: "number_regist"
            },
            {
                data: "description"
            },
            {
                data: function (d) {
                    return `<img style="width: 50px;height:50px" src="${d.image}" alt="image" />`;
                },
            },
            {
                data: "supplier"
            },
            {
                data: function (d) {
                    return getActive(d.active);
                },
            },
            {
                data: function (d) {
                    return `<a class="btn btn-primary btn-sm" href='/admin/chemistries/update/${d.id}'>
                            <i class="fas fa-edit"></i>
                        </a>
                        <button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                        </button>`;
                },
            },
        ],
    });
})

$(document).on("click", ".btn-delete", function () {
    if (confirm("Bạn có muốn xóa")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/chemistries/${id}/destroy`,
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
