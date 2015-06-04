<html>
    <head>
        <style type="text/css" media="screen">
            @screen 
            .page{
                page-break-inside: avoid;
            }
            p{
                margin: 0mm;
            }
            h6{
                margin: 0mm;
                font: "Times New Roman";
                font-size: 10pt;
            }
            body {
                font: 12pt  "Times New Roman";
                line-height: 1.3;
                font-size: 12pt;
                background: #e6e6e6;
                margin-left: 20%;
                
                margin-right: 20%;
            }
            .page{
                background: #FFF;
                box-shadow: 1px 1px 1px 1px #000;
            }
            h1 {
                font-size: 24pt;
            }

            h2 {
                font-size: 14pt;
                margin-top: 25px;
            }
            h3{
                margin: 0mm;
            }
            span{
                font-weight:bold;
            }
        </style>
        <style type="text/css" media="print">
            @page 
            {
                size: auto;   /* auto is the initial value */
                margin: 0mm;  /* this affects the margin in the printer settings */
                margin-left: 1cm;
                margin-right: 1cm;
            }
            .page{
                page-break-inside: avoid;
            }
            p{
                margin: 0mm;
            }
            h6{
                margin: 0mm;
                font: "Times New Roman";
                font-size: 10pt;
            }
            body {
                font: 12pt  "Times New Roman";
                line-height: 1.3;
                font-size: 12pt;
            }
            h1 {
                font-size: 24pt;
            }

            h2 {
                font-size: 14pt;
                margin-top: 25px;
            }
            h3{
                margin: 0mm;
            }

            span{
                font-weight:bold;
            }
        </style>
        <script>
            window.print();
        </script>
    </head>
    <body>
        <div class='page'>
            <div style="padding-top:2mm">

            </div>
            <div align="center">
                <table align="center">
                    <tr>
                        <td align="center">
                            <h6 style="text-transform: uppercase;"><?php echo JComponentHelper::getParams('com_fee')->get('name_school'); ?></h6>
                            <p style="font-size: 10pt;"><?php echo JComponentHelper::getParams('com_fee')->get('address'); ?></p>
                            <p style="font-size: 10pt;"><?php echo JComponentHelper::getParams('com_fee')->get('department_code'); ?></p>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="center">
                            <p>Mẫu số : C38 - BB</p>
                            <p>(Ban hành theo QĐ Số 19/2006/QĐ-BTC</p>
                            <p>Ngày 30/3/2006 của Bộ trưởng BTC)</p>
                        </td>
                    </tr>
                </table>
                <div style="padding-top:3mm">

                </div>
                <table align="center">
                    <tr>
                        <td style="width:85%;">

                        </td>
                        <td align="center">
                            <p>Số chứng từ:</p>
                            <p>C-<?php echo $this->item->title; ?></p>
                        </td>

                    </tr>
                </table>
                <div style="padding-top:0mm">

                </div>
                <h3 style="text-transform: uppercase;">Biên Lai Thu Tiền</h3>
                <p>(Liên 1: Báo soát)</p>

            </div>
            <div style="padding-top:5mm">

            </div>
            <div style="padding-left:15mm">
                <p>Họ, tên người nộp : <span style="text-transform: uppercase;"><?php echo $this->item->student_name; ?><span></p>
                            <p>MSSV : <?php echo $this->item->student_id; ?></p>
                            <p>Địa chỉ : Lớp <?php echo $this->item->department; ?> - K<?php echo $this->item->course; ?></p>
                            <p>
                                Nội dung thu: Học phí 
                                <?php
                                if ($this->item->semester_title) {
                                    echo "kỳ " . $this->item->semester_title;
                                }
                                ?>
                                - Năm học
                                <?php
                                echo $this->item->year_title;
                                ?>
                            </p>
                            <p>Số tiền thu (bằng số) : <span> <?php echo number_format($this->item->paid, 0, " ", " ") ?> đồng </span></p>
                            <p>Số tiền thu (bằng chữ) :<span> <?php echo $this->item->paidString; ?> đồng chẵn</span></p>
                            <p>.....................................................................................................................................................</p>
                            </div>

                            <div align="center">
                                <table align="center">
                                    <tr>
                                        <td align="center">
                                            <p></p>
                                            <p>Người nộp tiền</p>
                                            <p style='font-style: italic;'>(Ký và ghi rõ họ tên)</p>
                                        </td>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td align="center">
                                            <p style='font-style: italic;'>Hà nội , ngày <?php echo $this->item->day; ?> tháng <?php echo $this->item->month; ?> năm <?php echo $this->item->year; ?> </p>
                                            <p>Người thu tiền</p>
                                            <p style='font-style: italic;'>(Ký và ghi rõ họ tên)</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div style="padding-top:30mm">

                            </div>
                            <div align="center">
                                <table align="center">
                                    <tr>
                                        <td align="center">
                                            <h6 style="text-transform: uppercase;"><?php echo JComponentHelper::getParams('com_fee')->get('name_school'); ?></h6>
                                            <p style="font-size: 10pt;"><?php echo JComponentHelper::getParams('com_fee')->get('address'); ?></p>
                                            <p style="font-size: 10pt;"><?php echo JComponentHelper::getParams('com_fee')->get('department_code'); ?></p>
                                        </td>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td align="center">
                                            <p>Mẫu số : C38 - BB</p>
                                            <p>(Ban hành theo QĐ Số 19/2006/QĐ-BTC</p>
                                            <p>Ngày 30/3/2006 của Bộ trưởng BTC)</p>
                                        </td>
                                    </tr>
                                </table>
                                <div style="padding-top:3mm">

                                </div>
                                <table align="center">
                                    <tr>
                                        <td style="width:85%;">

                                        </td>
                                        <td align="center">
                                            <p>Số chứng từ:</p>
                                            <p>C-<?php echo $this->item->title; ?></p>
                                        </td>

                                    </tr>
                                </table>
                                <div style="padding-top:0mm">

                                </div>
                                <h3 style="text-transform: uppercase;">Biên Lai Thu Tiền</h3>
                                <p>(Liên 2: Giao cho sinh viên)</p>

                            </div>
                            <div style="padding-top:5mm">

                            </div>
                            <div style="padding-left:15mm">
                                <p>Họ, tên người nộp : <span style="text-transform: uppercase;"><?php echo $this->item->student_name; ?><span></p>
                                            <p>MSSV : <?php echo $this->item->student_id; ?></p>
                                            <p>Địa chỉ : Lớp <?php echo $this->item->department; ?> - K<?php echo $this->item->course; ?></p>
                                            <p>
                                                Nội dung thu: Học phí 
                                                <?php
                                                if ($this->item->semester_title) {
                                                    echo "kỳ " . $this->item->semester_title;
                                                }
                                                ?>
                                                - Năm học
                                                <?php
                                                echo $this->item->year_title;
                                                ?>
                                            </p>
                                            <p>Số tiền thu (bằng số) : <span> <?php echo number_format($this->item->paid, 0, " ", " ") ?> đồng </span></p>
                                            <p>Số tiền thu (bằng chữ) :<span> <?php echo $this->item->paidString; ?> đồng chẵn</span></p>
                                            <p>.....................................................................................................................................................</p>
                                            </div>
                                            <div align="center">
                                                <table align="center">
                                                    <tr>
                                                        <td align="center">
                                                            <p><b>Chú ý : mất biên lai không cấp lại</b></p>
                                                            <p>Người nộp tiền</p>
                                                            <p style='font-style: italic;'>(Ký và ghi rõ họ tên)</p>
                                                        </td>
                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                        <td align="center">
                                                            <p style='font-style: italic;'>Hà nội , ngày <?php echo $this->item->day; ?> tháng <?php echo $this->item->month; ?> năm <?php echo $this->item->year; ?> </p>
                                                            <p>Người thu tiền</p>
                                                            <p style='font-style: italic;'>(Ký và ghi rõ họ tên)</p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            </body>
                                            </html>