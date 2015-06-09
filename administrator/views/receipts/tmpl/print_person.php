<?php
require_once JPATH_COMPONENT . '/helpers/convert.php';
?>
<html>
    <head>
        <style type="text/css" media="screen">
            @screen 
            .page{
                page-break-inside: avoid;
            }
            p{
                margin: 0mm;
                display: inline;

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
            h3,h4,h5{
                margin: 2mm;
            }

            span{
                font-weight:bold;
            }

            table.table-border{
                border-collapse: collapse;
                width: 95%;
            }
            table.table-border td
            {
                border-left:1px solid black;
                border-bottom: 1px dotted black;
            }
            table.table-border th
            {
                border:1px solid black;
            }

        </style>
        <style type="text/css" media="print">
            @page 
            {
                size: auto;   /* auto is the initial value */
                margin: 0mm;  /* this affects the margin in the printer settings */
            }
            .page{
                page-break-inside: avoid;
            }

            p{
                margin: 1mm;

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
            h3,h4,h5{
                margin: 2mm;
            }
            span{
                font-weight:bold;
            }
            table.table-border{
                border-collapse: collapse;
                width: 95%;
            }
            table.table-border td
            {
                border-left:1px solid black;
                border-bottom:1px solid black;
                display: inline-block;
                margin: 0mm;
                padding: 1mm;
                vertical-align: central;
            }
            table.table-border th
            {
                border:1px solid black;
            }
        </style>
        <script>
            window.print();
        </script>
    </head>
    <body>

        <?php
        for ($i = 0; $i < count($this->items); $i++) {
            ?>
            <div class = 'page' align = "center">
                <table>
                    <tr>
                        <td align = "center" rowspan = "2" >
                            <p style = "text-transform: uppercase;font-size: 9pt;"><?php echo JComponentHelper::getParams('com_fee')->get('name_school', 'Trường Đại học Mỏ - Địa chất') ?></p>
                            <h6 style="text-transform: uppercase"><?php echo JComponentHelper::getParams('com_fee')->get('department', 'Phòng tài vụ'); ?></h6>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <h4 style="text-transform: uppercase">BẢNG KÊ ĐÓNG HỌC PHÍ NĂM HỌC: <?php echo $this->info->year; ?></h4>
                            <p style="text-transform: uppercase">lớp: <?php echo $this->items[$i]->receipt[0]->level . " " . $this->items[$i]->receipt[0]->department . " - K" . $this->items[$i]->receipt[0]->course ?></p>
                            <h5 style="text-transform: uppercase">Họ và tên: <?php echo $this->items[$i]->receipt[0]->name; ?></h5>
                        </td>
                    </tr>

                </table>
                <div style="padding-top:5mm">

                </div>
                <table style="width:95%;">
                    <tr>
                        <td>- Số tiền phải đóng trong năm học:</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="right">
                            <b> 
                                <?php
                                $totalPay = $this->items[$i]->receipt[0]->payable + $this->items[$i]->receipt[0]->owed_ago;
                                echo number_format($totalPay, 0, " ", " ")
                                ?> đồng
                            </b>
                        </td>
                    </tr>
                </table>
                <table align="left">
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td><i>+ Nợ năm học trước:</i></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="right"><i><?php echo number_format($this->items[$i]->receipt[0]->owed_ago, 0, " ", " ") ?> đồng</i></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td><i>+ Học phí năm học này:</i></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="right"><i><?php echo number_format($this->items[$i]->receipt[0]->payable, 0, " ", " ") ?>  đồng</i></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                </table>
                <table style="width:95%;">
                    <tr>
                        <td>- Số tiền đã đóng:</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="right">
                            <b>
                                <?php
                                $num_receipt = count($this->items[$i]->receipt);

                                if ($num_receipt > 1) {
                                    $total_paid1 = 0;
                                    for ($j = 0; $j < $num_receipt; $j++) {
                                        $total_paid1 += $this->items[$i]->receipt[$j]->paid;
                                    }
                                    $total_paid = $total_paid1;
                                } else
                                    $total_paid = $this->items[$i]->receipt[0]->paid;
                                echo number_format($total_paid, 0, " ", " ")
                                ?> đồng
                            </b>
                        </td>
                    </tr>
                </table>
                <div style="padding-top:5mm">

                </div>
                <div>

                </div>
                <table class="table-border">
                    <tr>
                        <th align="center" >Thứ tự<br>bản ghi</th>
                        <th align="center" >Ngày đóng</th>
                        <th align="center" >Bảng kê<br>nộp số tiền</th>
                        <th align="center"> Số<br>Chứng từ</th>
                        <th  align="center">Số tiền</th>
                        <th  align="center">Ghi chú</th>			
                    </tr>
                    <!-- gop dong -->
                    <?php
                    for ($j = 0; $j < count($this->items[$i]->receipt); $j++) {
                        ?>
                        <tr>
                            <td align="center"><?php echo $this->items[$i]->receipt[$j]->id ?></td>
                            <td align='center'><?php
                                $date = new DateTime($this->items[$i]->receipt[$j]->date);
                                echo $date->format("d-m-Y");
                                ?></td>
                            <td align='center'>  </td>
                            <td align="center"> <?php echo $this->items[$i]->receipt[$j]->title ?></td>
                            <td align="center" style="border-right: 1px solid black;"><?php echo number_format($this->items[$i]->receipt[$j]->paid, 0, " ", " ") ?></td>
                            <td align="right" style="border-right: 1px solid black;border-left: 0px;"></td>			
                        </tr>
                        <?php
                    }
                    ?>

                </table>
                <div style="padding-top:2mm;">

                </div>
                <div style="padding-left: 5mm" align="left">
                    <p>
                        <i>- Số tiền còn nợ :</i>
                        <span><?php echo number_format($totalPay - $total_paid, 0, " ", " ") ?> đồng</span> 
                    </p>
                </div>
                <table>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td><p>&nbsp;</p><b>Phụ trách kế toán</b></td>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td align="center">
                            <?php
                            $now = new DateTime();
                            ?>
                            <p><i>Hà nội, ngày  <?php echo date_format($now, 'd'); ?> tháng  <?php echo date_format($now, 'm'); ?> năm  <?php echo date_format($now, 'Y'); ?></i></p>
                            <p><b> Cán bộ thu tiền</b></p>
                        </td>
                    </tr>
                </table>
                <br>
            </div>
            <?php
        }
        ?>

    </body>
</html>
