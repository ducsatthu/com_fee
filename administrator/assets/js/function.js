//Biến toàn cục
var http_arr = new Array();
function doUpload() {
    document.getElementById('progress-group').innerHTML = ''; //Reset lại Progress-group
    var files = document.getElementById('myfile').files; //lay toan bo du lieu ra (ở đây nếu là để muti thì sẽ là nhiều file)
    for (i = 0; i < files.length; i++) {
        uploadFile(files[i], i);
    }
    return false;
}

function uploadFile(file, index) {
    var http = new XMLHttpRequest();
    http_arr.push(http);
    /** Khởi tạo vùng tiến trình **/
    //Div.Progress-group
    var ProgressGroup = document.getElementById('progress-group');
    //Div.Progress
    var Progress = document.createElement('div');
    Progress.className = 'progress';
    //Div.Progress-bar
    var ProgressBar = document.createElement('div');
    ProgressBar.className = 'progress-bar';
    //Div.Progress-text
    var ProgressText = document.createElement('div');
    ProgressText.className = 'progress-text';
    //Thêm Div.Progress-bar và Div.Progress-text vào Div.Progress
    Progress.appendChild(ProgressBar);
    Progress.appendChild(ProgressText);
    //Thêm Div.Progress và Div.Progress-bar vào Div.Progress-group	
    ProgressGroup.appendChild(Progress);
    //Biến hỗ trợ tính toán tốc độ
    var oldLoaded = 0;
    var oldTime = 0;
    //Sự kiện bắt tiến trình
    http.upload.addEventListener('progress', function (event) {
        if (oldTime == 0) { //Set thời gian trước đó nếu như bằng không.
            oldTime = event.timeStamp;
        }
        //Khởi tạo các biến cần thiết
        var fileName = file.name; //Tên file
        var fileLoaded = event.loaded; //Đã load được bao nhiêu
        var fileTotal = event.total; //Tổng cộng dung lượng cần load
        var fileProgress = parseInt((fileLoaded / fileTotal) * 100) || 0; //Tiến trình xử lý
        var speed = speedRate(oldTime, event.timeStamp, oldLoaded, event.loaded);
        //Sử dụng biến
        ProgressBar.innerHTML = fileName + ' đang được upload...';
        ProgressBar.style.width = fileProgress + '%';
        ProgressText.innerHTML = fileProgress + '% Upload Speed: ' + speed + 'KB/s';
        //Chờ dữ liệu trả về
        if (fileProgress == 100) {
            ProgressBar.style.background = 'url("components/com_fee/assets/images/progressbar.gif")';
            //Nút thêm dữ liệu

        }
        oldTime = event.timeStamp; //Set thời gian sau khi thực hiện xử lý
        oldLoaded = event.loaded; //Set dữ liệu đã nhận được
    }, false);
    //Bắt đầu Upload
    var data = new FormData();
    data.append('filename', file.name);
    data.append('myfile', file);
    http.open('POST', 'index.php?option=com_fee&view=student&task=student.upload', true);
    http.send(data);
    //Nhận dữ liệu trả về
    http.onreadystatechange = function (event) {
        //Kiểm tra điều kiện
        if (http.readyState == 4 && http.status == 200) {
            ProgressBar.style.background = ''; //Bỏ hình ảnh xử lý
            try { //Bẫy lỗi JSON
                var server = JSON.parse(http.responseText);
                if (server.status) {
                    ProgressBar.className += ' progress-bar-success'; //Thêm class Success
                    ProgressBar.innerHTML = server.message; //Thông báo
                    //Dữ liệu trả về thành công sẽ tạo bảng ở đây;
                    document.getElementById("insert-button").innerHTML = "<button id='btn-insert' class='btn btn-pimary pull-right'>Thêm dữ liệu</button>";
                    var tab = createStudentTable(server.object);
                    document.getElementById("btn-insert").onclick = function () {
                        checkWarningTable(tab);
                    };
                } else {
                    ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
                    ProgressBar.innerHTML = server.message; //Thông báo
                }
            } catch (e) {
                ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
                ProgressBar.innerHTML = 'Có lỗi xảy ra'; //Thông báo
                
            }
        }
        http.removeEventListener('progress'); //Bỏ bắt sự kiện
    }

}

function uploadData(studentList) {
    /* 
     * Hàm này mình sẽ xử lý toàn bộ cái thằng sinh viên
     * @type @exp;Object@call;keys@pro;length
     * dữ liệu đầu vào Object : student
     * dữ liệu trả ra : lỗi ~ tiến trình hiển thị
     * 
     */

    /** Khởi tạo vùng tiến trình **/
//Div.Progress-group
    var ProgressGroup = document.getElementById('progress-insert');
    //Div.Progress
    var Progress = document.createElement('div');
    Progress.className = 'progress';
    //Div.Progress-bar
    var ProgressBar = document.createElement('div');
    ProgressBar.className = 'progress-bar';
    ProgressBar.id = 'progressbar-insert';
    //Div.Progress-text
    var ProgressText = document.createElement('div');
    ProgressText.className = 'progress-text';
    //Thêm Div.Progress-bar và Div.Progress-text vào Div.Progress
    Progress.appendChild(ProgressBar);
    Progress.appendChild(ProgressText);
    //Thêm Div.Progress và Div.Progress-bar vào Div.Progress-group	
    ProgressGroup.appendChild(Progress);
    //giá trị mặc định cho width
    ProgressBar.style.width = "0%";
    var http = new XMLHttpRequest();
    // Bắt đầu Upload đoạn đẩy lên này phải chậm hơn
    var data = new FormData();
    data.append('insertData', JSON.stringify(studentList));
    http.open('POST', 'index.php?option=com_fee&view=student&task=student.insertData', true);
    http.send(data);
    http.onreadystatechange = function (event) {
        //Kiểm tra điều kiện
        if (http.readyState == 4 && http.status == 200) {
            ProgressBar.style.background = ''; //Bỏ hình ảnh xử lý
            try { //Bẫy lỗi JSON
                var server = JSON.parse(http.responseText);
                ProgressBar.style.width = 100 + '%';
                var numError = Object.keys(server).length;
                if (numError > 0) {
                    ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
                    ProgressBar.innerHTML = 'Có lỗi xảy ra'; //Thông báo

                    if (!document.getElementById('error-tab')) {
                        var error = document.getElementById("error");
                        var errorTabBtn = document.createElement("button");
                        errorTabBtn.className = 'btn btn-danger btn-lg btn-block';
                        errorTabBtn.innerHTML = 'Lỗi nhập dữ liệu';
                        var errorTab = document.createElement("table");
                        errorTab.id = 'error-tab';
                        errorTab.className = 'table table-bordered table-hover';
                        errorTabBtn.onclick = function () {
                            if (errorTab.style.display == 'none') {
                                errorTab.style.display = 'table';
                            }
                            else
                                errorTab.style.display = 'none';
                        };
                        error.appendChild(errorTabBtn);
                        error.appendChild(errorTab);

                        var header = errorTab.createTHead();
                        var row = header.insertRow(0);
                        var cell = document.createElement("th");
                        cell.innerHTML = "Số thứ tự";
                        row.appendChild(cell);

                        var cell = document.createElement("th");
                        cell.innerHTML = "Mã sinh viên";
                        row.appendChild(cell);

                        var cell = document.createElement("th");
                        cell.innerHTML = "Họ tên";
                        row.appendChild(cell);

                        var cell = document.createElement("th");
                        cell.innerHTML = "Ngày Sinh";
                        row.appendChild(cell);

                        var cell = document.createElement("th");
                        cell.innerHTML = "Ngành";
                        row.appendChild(cell);

                        var cell = document.createElement("th");
                        cell.innerHTML = "Khóa";
                        row.appendChild(cell);

                        var cell = document.createElement("th");
                        cell.innerHTML = "Lỗi";
                        row.appendChild(cell);


                        for (i = 0; i < numError; i++) {
                            var row = errorTab.insertRow(i + 1);
                            var cell = row.insertCell(0);
                            cell.innerHTML = i + 1;
                            var cell = row.insertCell(1);
                            cell.innerHTML = server[i].idstudent;
                            var cell = row.insertCell(2);
                            cell.innerHTML = server[i].name;
                            var cell = row.insertCell(3);
                            cell.innerHTML = server[i].birthday;
                            var cell = row.insertCell(4);
                            cell.innerHTML = server[i].department;
                            var cell = row.insertCell(5);
                            cell.innerHTML = server[i].course;
                            var cell = row.insertCell(6);
                            cell.innerHTML = "Trùng mã số sinh viên";

                        }

                    }
                } else {
                    ProgressBar.className += ' progress-bar-success'; //Thêm class Success
                    ProgressBar.innerHTML = "Thêm dữ liệu thành công"; //Thông báo
                }


            } catch (e) {
                ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
                ProgressBar.innerHTML = 'Có lỗi xảy ra'; //Thông báo
            }

        }
        http.removeEventListener('progress'); //Bỏ bắt sự kiện
    }


    /*
     
     //    Nhận dữ liệu trả về
     
     http.onreadystatechange = function (event) {
     //Kiểm tra điều kiện
     if (http.readyState == 4 && http.status == 200) { //cái lày là lấy 2 trạng thái của server đã xử xong chưa
     
     try { //Bẫy lỗi JSON
     var server = JSON.parse(http.responseText);  //lấy ra trị trả về từ server
     if (server.status) {
     
     progressWidth = (i / numStudent) * 100;
     Math.round(progressWidth);
     ProgressBar.style.width = Math.round(progressWidth) + '%';
     ProgressBar.innerHTML = 'Đang thêm dữ liệu : ' + Math.round(progressWidth) + '%'; //Thông báo
     if (document.getElementById('error-tab') && Math.round(progressWidth) == 100) {
     ProgressBar.innerHTML = 'Thêm dữ liệu thành công - Tuy nhiên có lỗi xảy ra, vui lòng kiểm tra trong bảng lỗi';
     }
     //Dữ liệu trả về thành công sẽ tạo bảng ở đây;  
     
     } else {
     //o day se loi ra nhung cai loi~ hien thi ra o phia duoi 
     
     if (!document.getElementById('error-tab')) {
     var error = document.getElementById("error");
     var errorTabBtn = document.createElement("button");
     errorTabBtn.className = 'btn btn-danger btn-lg btn-block';
     errorTabBtn.innerHTML = 'Lỗi nhập dữ liệu';
     var errorTab = document.createElement("table");
     errorTab.id = 'error-tab';
     errorTab.className = 'table table-bordered table-hover';
     errorTabBtn.onclick = function () {
     if (errorTab.style.display == 'none') {
     errorTab.style.display = 'table';
     }
     else
     errorTab.style.display = 'none';
     };
     error.appendChild(errorTabBtn);
     error.appendChild(errorTab);
     
     var header = errorTab.createTHead();
     var row = header.insertRow(0);
     var cell = row.insertCell(0);
     cell.innerHTML = "Số thứ tự";
     var cell = row.insertCell(1);
     cell.innerHTML = "Mã sinh viên";
     var cell = row.insertCell(2);
     cell.innerHTML = "Họ tên";
     var cell = row.insertCell(3);
     cell.innerHTML = "Ngày Sinh";
     var cell = row.insertCell(4);
     cell.innerHTML = "Ngành";
     var cell = row.insertCell(5);
     cell.innerHTML = "Khóa";
     
     var row = errorTab.insertRow(errorTab.rows.length);
     var cell = row.insertCell(0);
     cell.innerHTML = c;
     var cell = row.insertCell(1);
     cell.innerHTML = server.value.idstudent;
     var cell = row.insertCell(2);
     cell.innerHTML = server.value.name;
     var cell = row.insertCell(3);
     cell.innerHTML = server.value.birthday;
     var cell = row.insertCell(3);
     cell.innerHTML = server.value.department;
     var cell = row.insertCell(4);
     cell.innerHTML = server.value.course;
     c++;
     }
     ProgressBar.className += ' progress-bar-warning';
     ProgressBar.innerHTML = 'Thêm dữ liệu thành công - Tuy nhiên có lỗi xảy ra, vui lòng kiểm tra trong bảng lỗi';
     
     }
     } catch (e) {
     ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
     ProgressBar.innerHTML = 'Có lỗi xảy ra'; //Thông báo
     }
     }
     }
     */

//    for (i = 0; i < numStudent; i++) {
//        var student = studentList[i];
//        setTimeout(function () {
//            var http = new XMLHttpRequest();
//            // Bắt đầu Upload đoạn đẩy lên này phải chậm hơn
//            var data = new FormData();
//            data.append('insertData', JSON.stringify(student));
//            http.open('POST', 'index.php?option=com_fee&view=student&task=student.insertData', true);
//            http.send(data);
//
//            //Nhận dữ liệu trả về
//
//            http.onreadystatechange = function (event) {
//                //Kiểm tra điều kiện
//                if (http.readyState == 4 && http.status == 200) { //cái lày là lấy 2 trạng thái của server đã xử xong chưa
//
//                    try { //Bẫy lỗi JSON
//                        var server = JSON.parse(http.responseText);  //lấy ra trị trả về từ server
//                        if (server.status) {
//                            // width % bằng 1 thằng bằng 1/tổng số bản ghi * 100
//                            progressWidth = (i / numStudent) * 100;
//                            Math.round(progressWidth);
//                            ProgressBar.style.width = Math.round(progressWidth) + '%';
//                            ProgressBar.innerHTML = 'Đang thêm dữ liệu : ' + Math.round(progressWidth) + '%'; //Thông báo
//                            if (document.getElementById('error-tab') && Math.round(progressWidth) == 100) {
//                                ProgressBar.innerHTML = 'Thêm dữ liệu thành công - Tuy nhiên có lỗi xảy ra, vui lòng kiểm tra trong bảng lỗi';
//                            }
//                            //Dữ liệu trả về thành công sẽ tạo bảng ở đây;  
//
//                        } else {
//                            //o day se loi ra nhung cai loi~ hien thi ra o phia duoi 
//
//                            if (!document.getElementById('error-tab')) {
//                                var error = document.getElementById("error");
//                                var errorTabBtn = document.createElement("button");
//                                errorTabBtn.className = 'btn btn-danger btn-lg btn-block';
//                                errorTabBtn.innerHTML = 'Lỗi nhập dữ liệu';
//                                var errorTab = document.createElement("table");
//                                errorTab.id = 'error-tab';
//                                errorTab.className = 'table table-bordered table-hover';
//                                errorTabBtn.onclick = function () {
//                                    if (errorTab.style.display == 'none') {
//                                        errorTab.style.display = 'table';
//                                    }
//                                    else
//                                        errorTab.style.display = 'none';
//                                };
//                                error.appendChild(errorTabBtn);
//                                error.appendChild(errorTab);
//
//                                var header = errorTab.createTHead();
//                                var row = header.insertRow(0);
//                                var cell = row.insertCell(0);
//                                cell.innerHTML = "Số thứ tự";
//                                var cell = row.insertCell(1);
//                                cell.innerHTML = "Mã sinh viên";
//                                var cell = row.insertCell(2);
//                                cell.innerHTML = "Họ tên";
//                                var cell = row.insertCell(3);
//                                cell.innerHTML = "Ngày Sinh";
//                                var cell = row.insertCell(4);
//                                cell.innerHTML = "Ngành";
//                                var cell = row.insertCell(5);
//                                cell.innerHTML = "Khóa";
//
//                                var row = errorTab.insertRow(errorTab.rows.length);
//                                var cell = row.insertCell(0);
//                                cell.innerHTML = c;
//                                var cell = row.insertCell(1);
//                                cell.innerHTML = server.value.idstudent;
//                                var cell = row.insertCell(2);
//                                cell.innerHTML = server.value.name;
//                                var cell = row.insertCell(3);
//                                cell.innerHTML = server.value.birthday;
//                                var cell = row.insertCell(3);
//                                cell.innerHTML = server.value.department;
//                                var cell = row.insertCell(4);
//                                cell.innerHTML = server.value.course;
//                                c++;
//                            }
//                            ProgressBar.className += ' progress-bar-warning';
//                            ProgressBar.innerHTML = 'Thêm dữ liệu thành công - Tuy nhiên có lỗi xảy ra, vui lòng kiểm tra trong bảng lỗi';
//
//                        }
//                    } catch (e) {
//                        ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
//                        ProgressBar.innerHTML = 'Có lỗi xảy ra'; //Thông báo
//                    }
//                }
//            }
//
//        }, 3000, student);
//
//
//    }

}

function cancleUpload() {
    for (i = 0; i < http_arr.length; i++) {
        http_arr[i].removeEventListener('progress');
        http_arr[i].abort();
    }

    var ProgressBar = document.getElementsByClassName('progress-bar');
    for (i = 0; i < ProgressBar.length; i++) {
        ProgressBar[i].innerHTML = "Đã hủy tải lên";
        ProgressBar[i].style.background = ''; //Bỏ hình ảnh xử lý
        ProgressBar[i].className = 'progress-bar progress-bar-danger';
    }
}


function speedRate(oldTime, newTime, oldLoaded, newLoaded) {
    var timeProcess = newTime - oldTime; //Độ trễ giữa 2 lần gọi sự kiện
    if (timeProcess != 0) {
        var currentLoadedPerMilisecond = (newLoaded - oldLoaded) / timeProcess; // Số byte chuyển được 1 Mili giây
        return parseInt((currentLoadedPerMilisecond * 1000) / 1024); //Trả về giá trị tốc độ KB/s
    } else {
        return parseInt(newLoaded / 1024); //Trả về giá trị tốc độ KB/s
    }
}

function createStudentTable(objStudent) {
    var objStudent = JSON.parse(objStudent);
    var number = Object.keys(objStudent).length;
    var objInvalid = [];
    var tab = [];
    var data = "";
    for (i = 0; i < number * 2; i = i + 2) {
        var tabInfo = {};
        tabInfo['id'] = "tbl_" + i;
        tabInfo['course'] = objStudent[i].course;
        tabInfo['department'] = objStudent[i].department;
        tab.push(tabInfo);
        data += "<div class='student-list'>";
        data += "<button onclick='showTable(" + i + ")' id='class_" + i + "' class='btn btn-primary btn-lg btn-block'>Khóa " + objStudent[i].course + " - " + objStudent[i].department + "</button>";
        data += "<table id= 'tbl_" + i + "' class='table table-bordered table-hover'>";
        data += "<tr>";
        data += "<th class='span2'>Số thứ tự</th><th class='span4'>Mã số sinh viên</th><th class='span4'>Họ tên</th><th class='span2'>Ngày sinh</th>";
        data += "</tr>";
        var nStudent = Object.keys(objStudent[i].student).length;
        for (j = 0; j < nStudent; j++) {
            if (!validDate(objStudent[i].student[j].birthday)) {
                var student = {};
                student['idstudent'] = objStudent[i].student[j].idstudent;
                student['name'] = objStudent[i].student[j].name;
                student['birthday'] = objStudent[i].student[j].birthday;
                student['row'] = i.toString() + j.toString();
                objInvalid.push(student);
            }

            if (j % 2 == 0) {
                if (!validDate(objStudent[i].student[j].birthday))
                    data += "<tr id='" + i.toString() + j.toString() + "' class='warning'>";
                else
                    data += "<tr id='" + i.toString() + j.toString() + "'>";
            }
            else {
                if (!validDate(objStudent[i].student[j].birthday))
                    data += "<tr id='" + i.toString() + j.toString() + "' class='warning'>";
                else
                    data += "<tr id='" + i.toString() + j.toString() + "' class='success'>";
            }
            data += "<td >" + (j + 1) + "</td><td>" + objStudent[i].student[j].idstudent + "</td><td>" + objStudent[i].student[j].name + "</td><td id='birthday1_" + i.toString() + j.toString() + "'>" + objStudent[i].student[j].birthday + "</td>";
            data += "</tr>";
        }
        data += "</table>";
        data += "</div>";
    }
    document.getElementById("student").innerHTML = data;
    warningTable(objInvalid);
    return tab;
}

function validDate(date) {
    try {
        var date = new Date(date.replace(/(\d{2})[-/](\d{2})[-/](\d{4})/, "$2/$1/$3"));
    }
    catch (err) {
        var date = new Date(date);
    }

    var mDY = (date.getMonth() + 1) + "/" + date.getDate() + "/" + date.getFullYear();
    var mDY = new Date(mDY);
    var pattern = new Date("1/1/1970");
    if (+mDY === +pattern || mDY == "Invalid Date") {
        return false;
    }
    else
        return true;
}
function warningTable(objInvalid) {
    var number = Object.keys(objInvalid).length;
    var data = "";
    if (number > 0) {
        data += "<button onclick='showTable(null)' id='warning_btn' class='btn btn-danger btn-lg btn-block'>Dữ liệu cần kiểm tra lại</button>";
        data += "<table id='warning_tbl' class='table table-bordered table-hover'>";
        data += "<tr>";
        data += "<th class='span2'>Số thứ tự</th><th class='span2'>Mã số sinh viên</th><th class='span2'>Họ tên</th><th class='span2'>Ngày sinh</th><th class='span2'>Lý do</th><th class='span2'>Sửa</th>";
        data += "</tr>";
        for (i = 0; i < number; i++) {
            if (i % 2 == 0)
                data += "<tr id='" + objInvalid[i].row + "'>";
            else
                data += "<tr id='" + objInvalid[i].row + "' class='warning'>";
            data += "<td >" + (i + 1) + "</td>\n\
                    <td>" + objInvalid[i].idstudent + "</td>" +
                    "<td>" + objInvalid[i].name + "</td>" +
                    "<td id='birthday_" + objInvalid[i].row + "'>" + objInvalid[i].birthday + "</td>" +
                    "<td>Sai ngày sinh</td>" +
                    "<td>" +
                    "<div class='form-inline'>" +
                    "<div class='input-append'>" +
                    "<input type='text' name='jform[born]' maxlength='45' class='input-medium hasTooltip' data-original-title='' aria-invalid='false' id='edit_" + objInvalid[i].row + "'>" +
                    "<button type='button' class='btn' id='jform_born_img'>" +
                    '<i class="icon-calendar"></i>' +
                    '</button></div>' +
                    "<button onclick='editDate(" + objInvalid[i].row + ")' class='btn btn-default'>Sửa</button></div></td>";
            data += "</tr>";
        }
        data += "</table>";
        document.getElementById("invalid").innerHTML = data;
    }
}

function showTable(idTab) {
    if (idTab != null) {
        idTab = "tbl_" + idTab;
    }
    else
        idTab = "warning_tbl";
    var table = document.getElementById(idTab);
    if (table.style.display == 'none') {
        table.style.display = 'table';
    }
    else
        table.style.display = 'none';
}

function editDate(rowId) {
    var rowEdit1 = "birthday_" + rowId;
    var rowEdit2 = "birthday1_" + rowId;
    var txtId = "edit_" + rowId;
    if (rowId < 10) {
        rowEdit1 = "birthday_0" + rowId;
        rowEdit2 = "birthday1_0" + rowId;
        txtId = "edit_0" + rowId;
    }

    document.getElementById(rowEdit1).innerHTML = document.getElementById(txtId).value;
    document.getElementById(rowEdit2).innerHTML = document.getElementById(txtId).value;
}

function checkWarningTable(tab) {
    var warningTab = document.getElementById("warning_tbl");
    var warning = "";
    if (!document.getElementById("data-warning"))
        warning = "<div id='data-warning' class='alert alert-danger span10 pull-left'><span class='sr-only'>Kiểm tra lại dữ liệu</span></div>";
    if (warningTab != null) {
        for (i = 1; i < warningTab.rows.length; i++) {
            if (!validDate(warningTab.rows[i].cells[3].innerHTML)) {
                document.getElementById("insert-data").innerHTML += warning;
                break;
            }
            else {
                if (!document.getElementById('progressbar-insert')) {
                    var studentList = getTabData(tab);
                    uploadData(studentList);
                }

            }
        }
    }
    else {
        if (!document.getElementById('progressbar-insert')) {
            var studentList = getTabData(tab);
            uploadData(studentList);
        }
    }

}

function getTabData(tab) {
    var numberTab = Object.keys(tab).length;
    var insertObj = [];
    if (numberTab > 0) {
        for (i = 0; i < numberTab; i++) {
            var data = document.getElementById(tab[i].id);
            for (j = 1; j < data.rows.length; j++) {
                var student = {};
                student['idstudent'] = data.rows[j].cells[1].innerHTML;
                student['name'] = data.rows[j].cells[2].innerHTML;
                student['birthday'] = data.rows[j].cells[3].innerHTML;
                student['course'] = tab[i].course;
                student['department'] = tab[i].department;
                insertObj.push(student);
            }
        }
        return insertObj;
    }
}



