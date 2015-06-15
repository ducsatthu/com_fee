<?php
require_once JPATH_COMPONENT . '/helpers/convert.php';
?>
<html>
    <head>
        <meta charset="utf8"/>
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
                margin-left: 10%;
                margin-right: 10%;
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
                size: landscape;
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
        $itemPerPage = 20;
        $lenght = count($this->items);
        if ($lenght % $itemPerPage == 0) {
            $page = $lenght / $itemPerPage;
        } else {
            $page = (int) ($lenght / $itemPerPage + 1);
        }
        $item = 0;
        $k = 1;

        $totalStudentfree = 0;
        $totalStudent100 = 0;
        $totalStudent50 = 0;
        $totalAgo = 0;
        $totalP = 0;
        $totalPaddO = 0;
        $totalPN = 0;
        $totalON = 0;
        for ($i = 1; $i <= $page; $i++) {
            $itemPage = 0;
            ?>
            <div align="center" class="page">
                <table>
                    <tr>
                        <td align="center" colspan="2"><?php echo JComponentHelper::getParams('com_fee')->get('name_school', 'TRƯỜNG ĐẠI HỌC MỎ - ĐỊA CHẤT') ?><br>
                            <h6><?php echo JComponentHelper::getParams('com_fee')->get('department', 'PHÒNG TÀI VỤ') ?></h6></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="center" style="text-transform: uppercase"><?php echo JText::_('COM_FEE_LIST_TOTAL_FEE') ?>
                            <?php echo $this->info->year; ?>
                            <br>HỆ <?php echo $this->info->level; ?></td>
                        <td align="center"><br>Trang: <?php echo $i; ?></td>
                    </tr>

                </table>

                <div style="padding-top:5mm">

                </div>

                <table border="1" class="table-border">
                    <tr>
                        <th align="center" rowspan="2">TT</th>
                        <th align="center" rowspan="2">Lớp</th>
                        <th align="center" rowspan="2">Khóa</th>
                        <th align="center" colspan="4">Số sinh viên</th>
                        <th align="center" colspan="3">Số tiền phải đóng</th>
                        <th align="center" rowspan="2">Số tiền<br>đã đóng</th>
                        <th align="center" rowspan="2">Số tiền phải<br>chi trả</th>
                        <th align="center" rowspan="2">Số tiền<br>thực thu</th>
                        <th align="center" rowspan="2">Số tiền<br>còn nợ</td>
                    </tr>


                    <tr style="border-bottom: 1px solid black;">
                        <td align="center">Đóng<br>100%</td>
                        <td align="center">Miễn</td>
                        <td align="center">Giảm</td>
                        <td align="center">Cộng</td>
                        <td align="center">Nợ của<br>năm cũ</td>
                        <td align="center">Số tiền phải<br> đóng năm nay</td>
                        <td align="center">Cộng</td>
                    </tr>
                    <?php
                    $total_hundpercent = 0;
                    $total_decrease = 0;
                    $total_free = 0;
                    $total_owed_ago = 0;
                    $total_payable = 0;
                    $total_total = 0;
                    $total_paid = 0;
                    $total_oweds = 0;
                    for ($j = 1; $j <= $itemPerPage; $j++) {
                        if (!@$this->items[$item]) {
                            break;
                        }
                        ?>
                        <tr>
                            <td align="center"><?php echo $k; ?></td>
                            <td><?php echo $this->items[$item]->department ?></td>
                            <td align="center"><?php echo $this->items[$item]->course; ?></td>
                            <td align="center"><?php
                                echo number_format($this->items[$item]->hundpercent, '0', ' ', ' ');
                                $total_hundpercent += $this->items[$item]->hundpercent;
                                ?></td>
                            <td align="center"><?php
                                echo number_format($this->items[$item]->free, '0', ' ', ' ');
                                $total_free += $this->items[$item]->free;
                                ?></td>
                            <td align="center"><?php
                                echo number_format($this->items[$item]->decrease, '0', ' ', ' ');
                                $total_decrease += $this->items[$item]->decrease;
                                ?></td>

                            <td align="center"><?php
                                echo number_format($this->items[$item]->hundpercent + $this->items[$item]->decrease + $this->items[$item]->free, '0', ' ', ' ');
                                ?></td>
                            <td align="right"><?php
                                echo number_format($this->items[$item]->totalOwed, '0', ' ', ' ');
                                $total_owed_ago += $this->items[$item]->totalOwed;
                                ?></td>
                            <td align="right"><?php
                                echo number_format($this->items[$item]->pay, '0', ' ', ' ');
                                $total_payable += $this->items[$item]->pay;
                                ?></td>
                            <td align="right"><?php
                                echo number_format($this->items[$item]->pay + $this->items[$item]->totalOwed, '0', ' ', ' ');
                                $totalOwedNow = $this->items[$item]->pay + $this->items[$item]->totalOwed;
                                $total_total += $this->items[$item]->pay + $this->items[$item]->totalOwed;
                                ?></td>
                            <td align="right"><?php
                                echo number_format($this->items[$item]->paid, '0', ' ', ' ');
                                $total_paid += $this->items[$item]->paid;
                                ?></td>
                            <td align="right"> </td>
                            <td align="right"><?php
                                #quan tam cai nay thuc thu
                                echo number_format($this->items[$item]->paid, '0', ' ', ' ');
                                ?>
                            </td>
                            <td align="right">
                                <?php
                                $totalOwed = $totalOwedNow - $this->items[$item]->paid;
                                $total_oweds += $totalOwed;
                                echo number_format($totalOwed, '0', ' ', ' ');
                                ?>
                            </td>
                        </tr>
                        <?php
                        $k++;
                        $item++;
                        if ($itemPage == $itemPerPage) {
                            break;
                        }
                    }


                    if ($page > 1) {
                        ?>
                        <tr style="border-top: 1px solid black;border-bottom: 1px solid black;border-right: 0px solid black;">
                            <td align="center" colspan="3">Tổng Trang <?php echo $i; ?></td>
                            <td align="center"><span><?php echo $total_hundpercent; ?> </span></td>
                            <td align="center"><span><?php echo $total_free; ?></span></td>
                            <td align="center"><span><?php echo $total_decrease; ?></span></td>
                            <td align="center"><span><?php echo $total_hundpercent + $total_decrease + $total_free; ?></span></td>
                            <td align="right" ><span><?php echo number_format($total_owed_ago, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span><?php echo number_format($total_payable, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span><?php echo number_format($total_total, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span><?php echo number_format($total_paid, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span></span></td>
                            <td align="right"><span><?php echo number_format($total_paid, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span><?php echo number_format($total_oweds, '0', ' ', ' '); ?></span></td>
                        </tr>
                        <?php
                        if ($i != 1 && $page > 1) {
                            ?>
                            <tr style="border-top: 1px solid black;border-bottom: 1px solid black;border-right: 0px solid black;">

                                <td align="center" colspan="3">Mang sang </td>
                                <td align="center"><span><?php echo $totalStudent100; ?> </span></td>
                                <td align="center"><span><?php echo $totalStudentfree; ?></span></td>
                                <td align="center"><span><?php echo $totalStudent50; ?></span></td>
                                <td align="center"><span><?php echo $totalStudent100 + $totalStudentfree + $totalStudent50; ?></span></td>
                                <td align="right" ><span><?php echo number_format($totalAgo, '0', ' ', ' '); ?></span></td>
                                <td align="right"><span><?php echo number_format($totalPN, '0', ' ', ' '); ?></span></td>
                                <td align="right"><span><?php echo number_format($totalAgo + $totalPN, '0', ' ', ' '); ?></span></td>
                                <td align="right"><span><?php echo number_format($totalP, '0', ' ', ' '); ?></span></td>
                                <td align="right"><span></span></td>
                                <td align="right"><span><?php echo number_format($totalP, '0', ' ', ' '); ?></span></td>
                                <td align="right"><span><?php echo number_format($totalAgo + $totalPN - $totalP, '0', ' ', ' '); ?></span></td>
                            </tr>
                            <?php
                        }
                        $totalStudent100 += $total_hundpercent;

                        $totalStudentfree += $total_free;

                        $totalStudent50 += $total_decrease;

                        $totalPN += $total_payable;

                        $totalAgo += $total_owed_ago;

                        $totalP += $total_paid;
                    }
                    if ($page > 1 && $i != 1) {
                        ?>

                        <tr style="border-top: 1px solid black;border-bottom: 1px solid black;border-right: 0px solid black;">
                            <?php
                            if ($i == $page) {
                                echo ' <td align="center" colspan="3">Cộng</td>';
                            } else {
                                echo ' <td align="center" colspan="3">Tổng lũy kế</td>';
                            }
                            ?>

                            <td align="center"><span><?php echo $totalStudent100; ?> </span></td>
                            <td align="center"><span><?php echo $totalStudentfree; ?></span></td>
                            <td align="center"><span><?php echo $totalStudent50; ?></span></td>
                            <td align="center"><span><?php echo $totalStudent100 + $totalStudentfree + $totalStudent50; ?></span></td>
                            <td align="right" ><span><?php echo number_format($totalAgo, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span><?php echo number_format($totalPN, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span><?php echo number_format($totalAgo + $totalPN, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span><?php echo number_format($totalP, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span></span></td>
                            <td align="right"><span><?php echo number_format($totalP, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span><?php echo number_format($totalAgo + $totalPN - $totalP, '0', ' ', ' '); ?></span></td>
                        </tr>
                        <?php
                    }
                    if ($page == 1) {
                        ?>
                        <tr style="border-top: 1px solid black;border-bottom: 1px solid black;border-right: 0px solid black;">
                            <td align="center" colspan="3">Cộng:</td>
                            <td align="center"><span><?php echo $total_hundpercent; ?> </span></td>
                            <td align="center"><span><?php echo $total_free; ?></span></td>
                            <td align="center"><span><?php echo $total_decrease; ?></span></td>
                            <td align="center"><span><?php echo $total_hundpercent + $total_decrease + $total_free; ?></span></td>
                            <td align="right" ><span><?php echo number_format($total_owed_ago, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span><?php echo number_format($total_payable, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span><?php echo number_format($total_total, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span><?php echo number_format($total_paid, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span></span></td>
                            <td align="right"><span><?php echo number_format($total_paid, '0', ' ', ' '); ?></span></td>
                            <td align="right"><span><?php echo number_format($total_oweds, '0', ' ', ' '); ?></span></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <div style="padding-top:2mm">
                </div>
                <?php
                if ($i == $page) {
                    ?>
                    <div style="padding-left: 5mm" align="left">
                        <p>
                            <i>Cộng ( Số tiền thực thu bằng chữ ):</i>
                            <?php
                            if ($page == 1) {
                                ?>
                                <span><i><?php echo FeeHelperConvert::convert_number_to_words($total_paid); ?> đồng chẵn</i></span> 
                                <?php
                            } else {
                                ?>
                                <span><i><?php echo FeeHelperConvert::convert_number_to_words($totalP); ?> đồng chẵn</i></span> 
                                <?php
                            }
                            ?>

                        </p>
                    </div>

                    <table>
                        <tr>
                            <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                            <td align="center"><br><h4>Duyệt của BGH</h4></td>
                            <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                            <td align="center"><br><h4>Phụ trách đơn vị</h4></td>
                            <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                            <td align="center"><br><h4>Phụ trách kế toán</h4></td>
                            <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                            <td align="center">
                                <?php
                                $time = JComponentHelper::getParams('com_fee')->get('time', '30-12-2015');
                                $now = new DateTime($time);
                                ?>
                                <p><i>Hà nội, ngày <?php echo date_format($now, 'd'); ?> tháng <?php echo date_format($now, 'm'); ?> năm <?php echo date_format($now, 'Y'); ?></i></p>
                                <h4>Người lập bảng</h4>
                            </td>			
                        </tr>
                    </table>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </body>
</html>

