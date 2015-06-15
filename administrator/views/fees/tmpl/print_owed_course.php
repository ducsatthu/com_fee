<?php
require_once JPATH_COMPONENT . '/helpers/convert.php';
;
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
            $page = (int) ($lenght / $itemPerPage + 1);
        }
        $item = 0;
        for ($i = 1; $i <= $page; $i++) {
            $itemPage = 0;
            $totalOwedNow = 0;
            ?>
            <div class='page' align="center">
                <table>
                    <tr>
                        <td align="center">
                            <p style="text-transform: uppercase;font-size: 9pt;"><?php echo JComponentHelper::getParams('com_fee')->get('name_school', 'Trường đại học mỏ - địa chất'); ?></p>
                            <h6 style="text-transform: uppercase"><?php echo JComponentHelper::getParams('com_fee')->get('department', 'Phòng tài vụ'); ?></h6>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="center">
                            <h3 style="text-transform: uppercase"><?php echo JText::_('COM_FEE_LIST_OWED'); ?></h3>
                            <p style="text-transform: uppercase">Năm Học: <?php echo $this->info->year; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Khóa : <?php echo $this->info->course; ?> </p>
                            <h5 style="text-transform: uppercase">Hệ : <?php echo $this->info->level; ?> </h5>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                </table>
                <div style="padding-top:5mm">

                </div>
                <div>

                </div>
                <table class="table-border">
                    <tr>
                        <th align="center">
                            TT
                        </th>
                        <th align="center">
                            Họ và tên
                        </th>
                        <th align="center">
                            Lớp
                        </th>
                        <th align="center">
                            Nợ năm cũ
                        </th>
                        <th align="center">
                            Học phí phải  <br>đóng trong năm
                        </th>
                        <th align="center">
                            số tiền đã đóng
                        </th>
                        <th align="center">
                            còn nợ
                        </th>
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
                            <td align='left'><?php echo $this->items[$item]->department; ?></td>
                            <td align="center"><?php
                                echo number_format($this->items[$item]->totalOwedAgo, 0, " ", " ");
                                ?>
                            </td>
                            <td align="center">
                                <?php
                                echo number_format($this->items[$item]->totalPayable, 0, " ", " ");
                                ?>
                            </td>
                            <td align="center">
                                <?php
                                echo number_format($this->items[$item]->totalPaid, 0, " ", " ");
                                ?>
                            </td>
                            <td align="center" style="border-right: 1px solid black;">
                                <?php
                                $totalOwedNow += $this->items[$item]->totalOwed;
                                echo number_format($this->items[$item]->totalOwed, 0, " ", " ");
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
                    <!-- Cộng -->
                    <tr>
                        <td colspan="3" style="border-top: 1px solid black;border-bottom: 1px solid black;border-right: 0px solid black;"></td>
                        <td align="right" colspan="2" style="border-right:0px;border-left:0px;border-top: 1px solid black;border-bottom: 1px solid black;">
                            <b>Tổng số tiền còn nợ:</b>
                        </td>
                        <td align="right" colspan="2" style="border-right: 1px solid black;border-left: 0px;border-top: 1px solid black;border-bottom: 1px solid black;"  > 
                            <b><?php
                                echo number_format($totalOwedNow, 0, " ", " ");
                                ?>
                            </b>
                        </td>
                    </tr>
                </table>
                <div style="padding-top:2mm">
                </div>
                <div style="padding-left: 5mm" align="left">
                    <p>
                        <i>Cộng số tiền nộp ( Bằng chữ ):</i>
                        <span><?php echo FeeHelperConvert::convert_number_to_words($totalOwedNow); ?> đồng chẵn</span> 
                    </p>
                </div>
                <table>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="center">
                            <p>&nbsp;</p>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

                        <td align="center">
                            <?php
                            $time = JComponentHelper::getParams('com_fee')->get('time', '30-12-2015');
                            $now = new DateTime($time);
                            ?>
                            <p>Hà nội, ngày <?php echo date_format($now, 'd'); ?> tháng <?php echo date_format($now, 'm'); ?> năm <?php echo date_format($now, 'Y'); ?></p>
                            <h4><b>Người lập bảng</b></h4>
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