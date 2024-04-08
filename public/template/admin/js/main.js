$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

//xoá
function removeRow(id, url) {
    if (confirm("Bạn có muốn xóa?")) {
        $.ajax({
            type: "DELETE",
            datatype: "JSON",
            data: { id, "_token": $('#csrf-token')[0].content },
            url: url,
            success: function (result) {
                if (result.error === false) {
                    alert(result.message);
                    location.reload();
                } else {
                    alert("Xóa không thành công!");
                }
            },
        });
    }
}

// //Upload ảnh
$("#upload").change(function () {
    const form = new FormData();
    form.append("file", $(this)[0].files[0]);
    form.append("_token", $('#csrf-token')[0].content);
    $.ajax({
        processData: false,
        contentType: false,
        type: "POST",
        datatype: "JSON",
        data: form,
        url: "/admin/upload/services",
        success: function (results) {
            if (results.error === false) {
                //hiển thị ảnh
                $("#image_show").html(
                    '<a href ="' +
                    results.url +
                    '"><img src="' +
                    results.url +
                    '"/ target="_blank"/></a>'
                );
                $("#thumb").val(results.url);
            } else {
                alert("Upload file lỗi!");
            }
        },
    });
});
//Upload ảnh
// $("#upload").change(function () {
//     const form = new FormData();
//     form.append("file", $(this)[0].files[0]);
//     form.append("_token", $('#csrf-token')[0].content);
//     $.ajax({
//         processData: false,
//         contentType: false,
//         type: "POST",
//         datatype: "JSON",
//         data: form,
//         url: "/admin/upload/services",
//         success: function (results) {
//             if (results.error === false) {
//                 //hiển thị ảnh
//                 $("#image_show").html(
//                     '<a href ="' +
//                     results.url +
//                     '"><img src="' +
//                     results.url +
//                     '"/ target="_blank"/></a>'
//                 );
//                 $("#thumb").val(results.url);
//             } else {
//                 alert("Upload file lỗi!");
//             }
//         },
//     });
// });
