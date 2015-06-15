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
                margin-left: 1cm;
                margin-right: 1cm;
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
        <div class='page' align="center">
            <table>
                <tr>
                    <td align="center">
                        <p style="text-transform: uppercase;font-size: 9pt;"><?php echo JComponentHelper::getParams('com_fee')->get('name_school', 'Trường đại học Mỏ - Địa Chất') ?></p>
                        <h6 style="text-transform: uppercase"><?php echo JComponentHelper::getParams('com_fee')->get('department', 'PHÒNG TÀI VỤ') ?></h6>
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td align="center">
                        <h4 style="text-transform: uppercase">DANH SÁCH SINH VIÊN MIỄN GIẢM HỌC PHÍ</h4>
                        <p style="text-transform: uppercase">Năm Học <?php echo $this->info->year ?></p>
                        <h5 style="text-transform: uppercase">LỚP: <?php echo $this->info->level . " " . $this->info->department . " - K" . $this->info->course ?></h5>
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
                    <th align="center" rowspan = "2">
                        TT
                    </th>
                    <th align="center" rowspan = "2">Họ và tên</th>
                    <th align="center" rowspan = "2">Mức<br>học phí<br>quy định</th>
                    <th align="center" colspan="2"> Học phí phải đóng trong năm </th>
                    <th align="center" rowspan = "2">
                <p >Số tiền<br>còn phải đóng</p>   
                </th>
                </tr>
                <!-- gop dong -->
                <tr>
                    <th  align="center">Cho cả năm</th>
                    <th  align="center"><p>Được xét cho 1 số tháng<br>( Diễn giai)</p></th>

                </tr>
                <?php
                $i = 1;
                foreach ($this->items as $item) {
                    ?>
                    <tr>
                        <td align="center"><?php echo $i++ ?></td>
                        <td align='center'><?php echo $item->name ?></td>
                        <td align='right'><?php echo number_format($item->totalPay, '0', ' ', ' ') ?></td>
                        <td align="center"> <?php echo number_format($item->rate, '0', ' ', ' ') ?> </td>
                        <td align="center" style="border-right: 1px solid black;">CBT</td>
                        <td align="right" style="border-right: 1px solid black;border-left: 0px;"><?php
                            $payable = $item->totalPay - $item->totalPay * $item->rate / 100;
                            echo number_format($payable, '0', ' ', ' ')
                            ?></td>			
                    </tr>
                    <?php
                }
                ?>

            </table>
            <div style="padding-top:2mm">
            </div>

            <table>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

                    <td align="center">
                        <?php
                        $time = JComponentHelper::getParams('com_fee')->get('time', '30-12-2015');
                        $now = new DateTime($time);
                        ?>
                        <p>Hà nội, ngày <?php echo date_format($now, 'd'); ?> tháng <?php echo date_format($now, 'm'); ?> năm <?php echo date_format($now, 'Y'); ?></p>

                    </td>
                </tr>
            </table>
            <br>
        </div>




    </body>
</html>
