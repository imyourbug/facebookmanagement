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
            url: "/admin/accounts",
            dataSrc: "accounts",
        },
        columns: [
            // {
            //     data: "id",
            // },
            {
                data: "name",
            },
            {
                data: "email",
            },
            {
                data: function (d) {
                    return `${d.role == 1
                        ? ""
                        : d.role == 0
                            ? (d.staff ? d.staff.tel : "")
                            : (d.customer ? d.customer.tel : "")
                        }`;
                },
            },
            {
                data: function (d) {
                    return `${d.role == 1
                        ? ""
                        : d.role == 0
                            ? (d.staff ? d.staff.position : "")
                            : ""
                        }`;
                },
            },
            {
                data: function (d) {
                    return `${d.role == 1
                        ? "Quản lý"
                        : d.role == 0
                            ? "Nhân viên"
                            : "Khách hàng"
                        }`;
                },
            },
            {
                data: "created_at",
            },
            {
                data: function (d) {
                    let btnDelete = `<button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>`;
                    let urlUpdate = '';
                    switch (parseInt(d.role)) {
                        case 0: urlUpdate = `/admin/staffs/update/${d.staff.id}`; break;
                        case 1: urlUpdate = `/admin/accounts/update/${d.id}`; break;
                        default: urlUpdate = `/admin/customers/detail/${d.customer.id}`; break;
                    }

                    return `<a class="btn btn-primary btn-sm" href='${urlUpdate}'>
                            <i class="fas fa-edit"></i>
                        </a>
                        ${$("#logging_user_id").val() != d.id &&
                            $("#editing_user_id").val() != d.id
                            ? btnDelete
                            : ""
                        }`;
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
            url: `/api/accounts/${id}/destroy`,

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
