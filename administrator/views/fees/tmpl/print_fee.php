<?php
require_once JPATH_COMPONENT . '/helpers/convert.php';
;
?>
<html>
    <head>
        <title>
        </title>

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
            h3{
                margin: 2mm;
            }
            h4{
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
            h3{
                margin: 2mm;
            }
            h4{
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
        $itemPerPage = 30;
        $lenght = count($this->items);
        if ($lenght % $itemPerPage == 0) {
            $page = $lenght / $itemPerPage;
        } else {
            $page = (int) $lenght / $itemPerPage + 1;
        }
        $item = 0;
        for ($i = 1; $i <= $page; $i++) {
            $itemPage = 0;
            $totalPaidNow = 0;
            $totalOweds = 0;
            $totalOwedsNow = 0;
            $totalPayables = 0;
            $totalPay = 0;
            ?>
            <div align="center" class="page">
                <table>
                    <tr>
                        <td align="center" colspan="2">
                            <p style="font-size: 10pt;text-transform: uppercase;"><?php echo JComponentHelper::getParams('com_fee')->get('name_school', 'Trường đại học mỏ - địa chất'); ?></p>
                            <h6 style="text-transform: uppercase"><?php echo JComponentHelper::getParams('com_fee')->get('department', 'Phòng tài vụ'); ?></h6>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="center">
                            <p style="text-transform: uppercase;">BẢNG KÊ ĐÓNG HỌC PHÍ NĂM HỌC: <?php echo $this->info->year; ?></p>
                            <h6 style="text-transform: uppercase">LỚP : <?php echo $this->info->level . " " . $this->info->department . " - K" . $this->info->course; ?></h6></td>
                        <td align="center">
                            <p>&nbsp;</p>
                            <p><i>Tờ số : <?php echo $i; ?></i></p>
                        </td>
                    </tr>

                </table>

                <div style="padding-top:5mm">

                </div>

                <table border="1" class="table-border">
                    <tr>
                        <th align="center" rowspan="2">TT</th>
                        <th align="center" rowspan="2">Họ và tên</th>
                        <th align="center" colspan="3">Học phí phải đóng trong năm</th>
                        <th align="center" rowspan="2">Số tiền<br>đã đóng</th>
                        <th align="center" rowspan="2">Số tiền đã<br>chi trả</th>
                        <th align="center" rowspan="2">Số tiền<br>thực thu</th>
                        <th align="center" rowspan="2">Số tiền<br>còn nợ</td>
                    </tr>
                    <tr style="border-bottom: 1px solid black;">
                        <td align="center">Tỷ lệ<br>miễn<br>giảm</td>
                        <td align="center">Số tiền phải<br>đóng trong<br>năm nay</td>
                        <td align="center">Cộng<br>cả nợ<br>năm cũ</td>
                    </tr>	

                    <?php
                    for ($j = 1; $j <= $itemPerPage; $j++) {
                        if (!@$this->items[$item]) {
                            break;
                        }
                        ?>
                        <tr>
                            <td align="center"><?php echo $j; ?></td>
                            <td align='left'><?php echo $this->items[$item]->title; ?></td>
                            <td align='left'><?php echo $this->items[$item]->rate; ?></td>
                            <td align="center"><?php
                                $totalPay += $this->items[$item]->pay;
                                echo number_format($this->items[$item]->pay, 0, " ", " ");
                                ?>
                            </td>
                            <td align="center">
                                <?php
                                $totalOwed = $this->items[$item]->pay + $this->items[$item]->owedAgo;
                                $totalOweds += $totalOwed;
                                echo number_format($totalOwed, 0, " ", " ");
                                ?>
                            </td>
                            <td align="center" style="border-right: 1px solid black;">
                                <?php
                                $totalPaidNow += $this->items[$item]->paid;
                                echo number_format($this->items[$item]->paid, 0, " ", " ");
                                ?>
                            </td>
                            <td align="center"></td>
                            <td align="center">
                                <?php
                                echo number_format($this->items[$item]->paid, 0, " ", " ");
                                ?>
                            </td>
                            <td align="center">
                                <?php
                                $totalOwedNow = $totalOwed - $this->items[$item]->paid;
                                echo number_format($totalOwedNow, 0, " ", " ");
                                $totalOwedsNow += $totalOwedNow;
                                ?>
                            </td>
                        </tr>
                        <?php
                        $item++;
                        if ($itemPage == $itemPerPage) {
                            break;
                        }
                    }
                    ?>



                    <tr style="border-top: 1px solid black;border-bottom: 1px solid black;border-right: 0px solid black;">
                        <td align="center" colspan="3">Cộng:</td>
                        <td align="center">
                            <span>
                                <?php
                                echo number_format($totalPay, 0, " ", " ");
                                ?>
                            </span>
                        </td>
                        <td align="center">
                            <span>
                                <?php
                                echo number_format($totalOweds, 0, " ", " ");
                                ?>
                            </span>
                        </td>
                        <td align="center">
                            <span>
                                <?php
                                echo number_format($totalPaidNow, 0, " ", " ");
                                ?>
                            </span>
                        </td>
                        <td align="center">

                        </td>
                        <td align="center">
                            <span>
                                <?php
                                echo number_format($totalPaidNow, 0, " ", " ");
                                ?>
                            </span>
                        </td>
                        <td align="center">
                            <span>
                                <?php
                                echo number_format($totalOwedsNow, 0, " ", " ");
                                ?>
                            </span>
                        </td>
                    </tr>
                </table>

                <div style="padding-top:2mm">
                </div>

                <div style="padding-left: 5mm" align="left">
                    <p><i>Cộng số tiền thực thu ( Bằng chữ ):</i>
                        <span><i><?php echo FeeHelperConvert::convert_number_to_words($totalPaidNow); ?> đồng chẵn</i></span> 
                    </p>
                </div>
                <div style="padding-top:5mm">

                </div>
                <table>
                    <tr>
                        <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                        <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                        <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                        <td align="center"><br><h4>Phụ trách kế toán</h4></td>
                        <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                        <td align="center"><i>Hà Nôi, ngày 19 tháng 5 năm 2015</i><br><h4>Người lập bảng</h4></td>			
                    </tr>
                </table>

            </div>
            <?php
        }
        ?>
    </body>
</html>