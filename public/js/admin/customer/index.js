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
            url: "/admin/customers",
            dataSrc: "customers",
        },
        columns: [
            // {
            //     data: "id",
            // },
            {
                data: "name",
            },
            {
                data: "representative",
            },
            {
                data: "tax_code",
            },
            {
                data: "address",
            },
            {
                data: "website",
            },
            {
                data: "email",
            },
            {
                data: function (d) {
                    return `<img style="width: 50px;height:50px" src="${d.avatar}" alt="avatar" />`;
                },
            },
            {
                data: "manager",
            },
            {
                data: "tel",
            },
            {
                data: function (d) {
                    return d.user.email ? d.user.email : d.user.name;
                },
            },
            {
                data: function (d) {
                    return `<a class="btn btn-primary btn-sm" href="/admin/customers/detail/${d.id}">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button data-id="${d.user_id}" class="btn btn-danger btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                        </button>`;
                },
            },
        ],
    });
});

$(document).on("click", ".btn-delete", function () {
    if (confirm("Bạn có muốn xóa")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/customers/${id}/destroy`,
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
