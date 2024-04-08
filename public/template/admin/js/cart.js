        function openForm() {
            // if (document.getElementById("ten").value == "") {
            //     alert("Chưa nhập họ tên!");
            // } else if (document.getElementById("email").value == "") {
            //     alert("Chưa Enter email!");
            // } else if (document.getElementById("sonha").value == "") {
            //     alert("Chưa nhập địa chỉ!");
            // } else if (document.getElementById("sdt").value == "") {
            //     alert("Chưa nhập số điện thoại!");
            // } else {
            var select = document.getElementById('select');
            var d = select.options[select.selectedIndex].value;
                document.getElementById("ten2").value = document.getElementById("ten").value;
                document.getElementById("email2").value = document.getElementById("email").value;
                document.getElementById("sonha2").value = document.getElementById("sonha").value;
                document.getElementById("sdt2").value = document.getElementById("sdt").value;
                document.getElementById("mota2").value = document.getElementById("mota").value;
                document.getElementById("total2").value = document.getElementById("total").value;
                document.getElementById("voucher_id").value = d;
                document.getElementById("myForm").style.display = "block";
            // }
            document.getElementById("myForm").style.display = "block";
        }

        function closeForm() {
            document.getElementById("myForm").style.display = "none";
        }

        function tru(price, id) {
            var sl = document.getElementById('quantity' + id).value;
            if (sl == 1) {
                document.getElementById('quantity' + id).value = 1;
            } else {
                sl--;
                document.getElementById('quantity' + id).value = sl;
            }
            setPrice(price, id);
        }

        function cong(price, id) {
            var sl = document.getElementById('quantity' + id).value;
            sl++;
            document.getElementById('quantity' + id).value = sl;
            setPrice(price, id);
        }

        function setPrice(price, id) {
            var sl = document.getElementById('quantity' + id).value;
            document.getElementById('money' + id).value = formatCash(String(sl * price));
            // total(sl * price);
        }

        // function total(money) {
        //     var total = document.getElementById('tongtien').value;
        //     document.getElementById('tongtien').value = total;
        // }

        function formatCash(str) {
            return str.split('').reverse().reduce((prev, next, index) => {
                return ((index % 3) ? next : (next + '.')) + prev
            })
        }
