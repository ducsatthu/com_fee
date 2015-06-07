<?php
require_once JPATH_COMPONENT . '/helpers/convert.php';
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
        <div align="center" class="page">
            <table>
                <tr>
                    <td align="center" colspan="2"><?php echo JComponentHelper::getParams('com_fee')->get('name_school', 'TRƯỜNG ĐẠI HỌC MỎ - ĐỊA CHẤT') ?><br>
                        <h6><?php echo JComponentHelper::getParams('com_fee')->get('department', 'PHÒNG TÀI VỤ') ?></h6></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td align="center" style="text-transform: uppercase"><?php echo JText::_('COM_FEE_LIST_TOTAL_FEE') ?>
                        <?php echo $this->info->year; ?>
                        <br>HỆ <?php echo $this->info->level; ?> - KHÓA: <?php echo $this->info->course; ?></td>
                    <td align="center"><br>Trang: 1</td>
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
                $i = 1;
                $total_hundpercent =0;
                $total_decrease = 0;
                $total_free=0;
                $total_all_student = 0;
                $total_owed_ago = 0;
                $total_payable = 0;
                $total_total =0;
                $total_paid =0;
                $total_owed = 0;
                foreach ($this->items as $item) {
                    ?>
                    <tr>
                        <td align="center"><?php echo $i++ ?></td>
                        <td><?php echo $item->title ?></td>
                        <td align="center"><?php echo $this->info->course; ?></td>
                        <td align="center"><?php 
                            echo number_format($item->hundpercent, '0', ' ', ' '); 
                            $total_hundpercent += $item->hundpercent;
                        ?></td>
                        <td align="center"><?php 
                            echo number_format($item->decrease, '0', ' ', ' ');
                            $total_decrease += $item->decrease;
                        ?></td>
                        <td align="center"><?php 
                            echo number_format($item->free, '0', ' ', ' '); 
                             $total_free += $item->free;
                        ?></td>
                        <td align="center"><?php 
                            echo number_format($item->hundpercent + $item->decrease + $item->free, '0', ' ', ' '); 
                            $total_all_student += $item->hundpercent + $item->decrease + $item->free;
                        ?></td>
                        <td align="center"><?php 
                            echo number_format($item->totalOwed, '0', ' ', ' '); 
                            $total_owed_ago += $item->totalOwed;
                            ?></td>
                        <td align="right"><?php 
                            echo number_format($item->pay, '0', ' ', ' '); 
                            $total_payable += $item->pay;
                        ?></td>
                        <td align="right"><?php 
                            echo number_format($item->pay + $item->totalOwed, '0', ' ', ' '); 
                            $total_total += $item->pay + $item->totalOwed;
                        ?></td>
                        <td align="right"><?php 
                            echo number_format($item->paid, '0', ' ', ' ');
                            $total_paid += $item->paid;
                        ?></td>
                        <td align="right"> </td>
                        <td align="right"><?php 
                            echo number_format($item->paid, '0', ' ', ' '); 
                            $total_paid += $item->paid;
                            ?></td>
                        <td align="right"></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>

                <tr style="border-top: 1px solid black;border-bottom: 1px solid black;border-right: 0px solid black;">
                    <td align="center" colspan="3">Cộng:</td>
                    <td align="center"><?php echo $total_hundpercent; ?> </td>
                    <td align="center"><?php echo $total_decrease; ?></td>
                    <td align="center"><?php echo $total_free; ?></td>
                    <td align="center"><?php echo $total_all_student; ?></td>
                    <td align="right" ><?php echo number_format($total_owed_ago, '0', ' ', ' '); ?></td>
                    <td align="right"><?php echo number_format($total_payable, '0', ' ', ' '); ?></td>
                    <td align="right"><?php echo number_format($total_total, '0', ' ', ' '); ?></td>
                    <td align="right"><?php echo number_format($total_paid, '0', ' ', ' '); ?></td>
                    <td align="right">0</td>
                    <td align="right"><?php echo number_format($total_paid, '0', ' ', ' '); ?></td>
                    <td align="right"><?php echo number_format($total_paid, '0', ' ', ' '); ?></td>
                </tr>
            </table>

            <div style="padding-top:2mm">
            </div>

            <table>
                <tr>
                    <td align="center" colspan="3">Cộng: (Bằng chữ số tiền thực thu)</td>
                    <td align="center"> <?php echo number_format($total_paid) ?></td>
                    <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                    <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                    <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                </tr>
            </table>

            <table>
                <tr>
                    <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                    <td align="center"><br><h4>Duyệt của BGH</h4></td>
                    <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                    <td align="center"><br><h4>Phụ trách đơn vị<h4></td>
                                <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                                <td align="center"><br><h4>Phụ trách kế toán<h4></td>
                                            <td align="center">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
                                            <td align="center"><i>Hà Nôi, ngày 19 tháng 5 năm 2015</i><br><h4>Người lập bảng</h4></td>			
                                            </tr>
                                            </table>

                                            </div>
                                            </body>
                                            </html>

