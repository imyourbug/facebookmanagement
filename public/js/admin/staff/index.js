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
                            columns: [0, 2, 3, 4, 5, 6],
                        },
                    },
                    "colvis",
                ],
            },
        },
        ajax: {
            url: "/admin/staffs",
            dataSrc: "staff",
        },
        columns: [
            // {
            //     data: "id",
            // },
            {
                data: "name",
            }, {
                data: "position",
            }, {
                data: "identification",
            },
            {
                data: "tel",
            }, {
                data: function (d) {
                    return `<img style="width: 50px;height:50px" src="${d.avatar}" alt="avatar" />`;
                },
            },

            {
                data: function (d) {
                    return `${d.user.email ? d.user.email : d.user.name}`;
                },
            },

            {
                data: function (d) {
                    return getActive(d.active);
                },
            },
            {
                data: function (d) {
                    let btnDelete = `<button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>`;
                    return `<a class="btn btn-primary btn-sm" href='/admin/staffs/update/${d.id
                        }'>
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
            url: `/api/staffs/${id}/destroy`,
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
