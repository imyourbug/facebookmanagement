var dataTableMap = null;
var dataTableStaff = null;
var dataTableItem = null;
var dataTableChemistry = null;
var dataTableSolution = null;

function closeModal(type) {
    $("#modal-" + type).css("display", "none");
    $("body").removeClass("modal-open");
    $(".modal-backdrop").remove();
}

$(document).ready(function () {
    // map
    dataTableMap = $("#tableMap").DataTable({
        ajax: {
            url: "/api/taskmaps?id=" + $("#task_id").val(),
            dataSrc: "taskMaps",
        },
        columns: [
            // { data: "id" },
            {
                data: function (d) {
                    return d.code ? d.code : `${d.map.area}-${d.map.id.toString().padStart(3, "0")}`;
                },
            },
            { data: "map.area" },
            { data: "map.target" },
            { data: "unit" },
            { data: "kpi" },
            { data: "result" },
            {
                data: function (d) {
                    return `<img style="width: 50px;height:50px" src="${d.image}" alt="image" />`;
                },
            },
            { data: "detail" },
            // {
            //     data: function (d) {
            //         return `<a class="btn btn-primary btn-sm btn-edit" data-id="${d.id}" data-target="#modal-map" data-toggle="modal">
            //                                         <i class="fas fa-edit"></i>
            //                                     </a>`;
            //         // return `<a class="btn btn-primary btn-sm btn-edit" data-id="${d.id}" data-target="#modal-map" data-toggle="modal">
            //         //                             <i class="fas fa-edit"></i>
            //         //                         </a>
            //         //                         <button data-id="${d.id}"
            //         //                             class="btn btn-danger btn-sm btn-delete">
            //         //                             <i class="fas fa-trash"></i>
            //         //                         </button>`;
            //     },
            // },
        ],
    });
    // staff
    dataTableStaff = $("#tableStaff").DataTable({
        ajax: {
            url: "/api/taskstaff?id=" + $("#task_id").val(),
            dataSrc: "taskStaff",
        },
        columns: [
            // { data: "id" },
            {
                data: function (d) {
                    return `NV${d.user.staff.id >= 10
                            ? d.user.staff.id
                            : "0" + d.user.staff.id
                        }`;
                },
            },
            { data: "user.staff.name" },
            { data: "user.staff.position" },
            { data: "user.staff.identification" },
            { data: "user.staff.tel" },
            // {
            //     data: function (d) {
            //         return `<a class="btn btn-primary btn-sm btn-edit-staff" data-id="${d.id}" data-target="#modal-staff" data-toggle="modal">
            //                                         <i class="fas fa-edit"></i>
            //                                     </a>`;
            //         // return `<a class="btn btn-primary btn-sm btn-edit-staff" data-id="${d.id}" data-target="#modal-staff" data-toggle="modal">
            //         //                             <i class="fas fa-edit"></i>
            //         //                         </a>
            //         //                         <button data-id="${d.id}"
            //         //                             class="btn btn-danger btn-sm btn-delete-staff">
            //         //                             <i class="fas fa-trash"></i>
            //         //                         </button>`;
            //     },
            // },
        ],
    });
    // chemistry
    dataTableChemistry = $("#tableChemistry").DataTable({
        ajax: {
            url: "/api/taskchemistries?id=" + $("#task_id").val(),
            dataSrc: "taskChemistries",
        },
        columns: [
            // { data: "id" },
            { data: "chemistry.name" },
            { data: "unit" },
            { data: "kpi" },
            { data: "result" },
            {
                data: function (d) {
                    return `<img style="width: 50px;height:50px" src="${d.image}" alt="image" />`;
                },
            },
            { data: "detail" },
            // {
            //     data: function (d) {
            //         return `<a class="btn btn-primary btn-sm btn-edit-chemistry" data-id="${d.id}" data-target="#modal-chemistry" data-toggle="modal">
            //                                         <i class="fas fa-edit"></i>
            //                                     </a>`;
            //         // return `<a class="btn btn-primary btn-sm btn-edit-chemistry" data-id="${d.id}" data-target="#modal-chemistry" data-toggle="modal">
            //         //                             <i class="fas fa-edit"></i>
            //         //                         </a>
            //         //                         <button data-id="${d.id}"
            //         //                             class="btn btn-danger btn-sm btn-delete-chemistry">
            //         //                             <i class="fas fa-trash"></i>
            //         //                         </button>`;
            //     },
            // },
        ],
    });
    // item
    dataTableItem = $("#tableItem").DataTable({
        ajax: {
            url: "/api/taskitems?id=" + $("#task_id").val(),
            dataSrc: "taskItems",
        },
        columns: [
            // { data: "id" },
            { data: "item.name" },
            { data: "unit" },
            { data: "kpi" },
            { data: "result" },
            {
                data: function (d) {
                    return `<img style="width: 50px;height:50px" src="${d.image}" alt="image" />`;
                },
            },
            { data: "detail" },
            // {
            //     data: function (d) {
            //         return `<a class="btn btn-primary btn-sm btn-edit-item" data-id="${d.id}" data-target="#modal-item" data-toggle="modal">
            //                                         <i class="fas fa-edit"></i>
            //                                     </a>`;
            //         // return `<a class="btn btn-primary btn-sm btn-edit-item" data-id="${d.id}" data-target="#modal-item" data-toggle="modal">
            //         //                             <i class="fas fa-edit"></i>
            //         //                         </a>
            //         //                         <button data-id="${d.id}"
            //         //                             class="btn btn-danger btn-sm btn-delete-item">
            //         //                             <i class="fas fa-trash"></i>
            //         //                         </button>`;
            //     },
            // },
        ],
    });
    // solution
    dataTableSolution = $("#tableSolution").DataTable({
        ajax: {
            url: "/api/tasksolutions?id=" + $("#task_id").val(),
            dataSrc: "taskSolutions",
        },
        columns: [
            // { data: "id" },
            { data: "solution.name" },
            { data: "unit" },
            { data: "kpi" },
            { data: "result" },
            {
                data: function (d) {
                    return `<img style="width: 50px;height:50px" src="${d.image}" alt="image" />`;
                },
            },
            { data: "detail" },
            // {
            //     data: function (d) {
            //         return `<a class="btn btn-primary btn-sm btn-edit-solution" data-id="${d.id}" data-target="#modal-solution" data-toggle="modal">
            //                                         <i class="fas fa-edit"></i>
            //                                     </a>`;
            //         // return `<a class="btn btn-primary btn-sm btn-edit-solution" data-id="${d.id}" data-target="#modal-solution" data-toggle="modal">
            //         //                             <i class="fas fa-edit"></i>
            //         //                         </a>
            //         //                         <button data-id="${d.id}"
            //         //                             class="btn btn-danger btn-sm btn-delete-solution">
            //         //                             <i class="fas fa-trash"></i>
            //         //                         </button>`;
            //     },
            // },
        ],
    });
});
