<?php
// no direct access
defined('_JEXEC') or die;
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
                margin-left: 0.5cm;
                margin-right: 0.5cm;
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
                width: 97%;
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
            $total = 0;
            ?>
            <div class='page' align="center">
                <table>
                    <tr>
                        <td align="center">
                            <p style="text-transform: uppercase;font-size: 9pt;"><?php echo JComponentHelper::getParams('com_fee')->get('name_school', 'Trường Đại học Mỏ - Địa chất'); ?></p>
                            <h6 style="text-transform: uppercase"><?php echo JComponentHelper::getParams('com_fee')->get('department', 'Phòng Tài Vụ'); ?></h6>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="center">
                            <h3 style="text-transform: uppercase">Bảng Kê Thu Tiền Học Phí</h3>
                            <p style="text-transform: uppercase">Hệ <?php echo $this->items[0]->level_title_1886861; ?> - Năm Học <?php echo $this->items[0]->year_alias; ?></p>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="center">
                            <p >Số : <?php echo $i; ?></p>
                        </td>
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
                        <th align="center">Họ và tên</th>
                        <th align="center">
                            Lớp
                        </th>
                        <th align="center">Ngày Nộp</th>
                        <th align="center">
                    <p >Số Chứng từ</p>
                    </th>
                    <th align="center">
                    <p >Số tiền</p>
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
                            <td align='left'><?php echo $this->items[$item]->student_name; ?></td>
                            <td align='left'><?php echo $this->items[$item]->department_title_1886853 . ' K' . $this->items[$item]->course_title_1886860; ?></td>
                            <td align="center"><?php
                                $date = new DateTime($this->items[$item]->date);
                                echo date_format($date, "d-m-Y");
                                ?>
                            </td>
                            <td align="center"><?php echo $this->items[$item]->title; ?></td>
                            <td align="right" style="border-right: 1px solid black;">
                                <?php
                                echo number_format($this->items[$item]->paid, 0, " ", " ");
                                ?>
                            </td>
                        </tr>
                        <?php
                        $total += $this->items[$item]->paid;
                        $item++;
                        if ($itemPage == $itemPerPage) {
                            break;
                        }
                    }
                    ?>
                    <!-- Cộng -->
                    <tr>
                        <td colspan="4" style="border-top: 1px solid black;border-bottom: 1px solid black;border-right: 0px solid black;"></td>
                        <td align="center" style="border-right:0px;border-left:0px;border-top: 1px solid black;border-bottom: 1px solid black;">Cộng : </td>
                        <td align="center" style="border-right: 1px solid black;border-left: 0px;border-top: 1px solid black;border-bottom: 1px solid black;"  align="center">
                            <span>
                                <?php
                                echo number_format($total, 0, " ", " ");
                                ?>
                            </span>
                        </td>
                    </tr>
                </table>
                <div style="padding-top:2mm">
                </div>
                <div style="padding-left: 5mm" align="left">
                    <p>
                        <i>Cộng số tiền nộp ( Bằng chữ ):</i>
                        <span><?php echo FeeHelperConvert::convert_number_to_words($total); ?> đồng chẵn</span> 
                    </p>
                </div>
                <table>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="center">
                            <p>&nbsp;</p>
                            <h4>Người nộp tiền</h4>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

                        <td align="center">
                            <?php
                            $datenow = new DateTime();
                            $daynow = date_format($datenow, 'd');
                            $monthnow = date_format($datenow, 'm');
                            $yearnow = date_format($datenow, 'Y');
                            ?>
                            <p>Hà nội,ngày <?php echo $daynow; ?> tháng <?php echo $monthnow; ?> năm <?php echo $yearnow; ?></p>
                            <h4><b>Người nộp tiền</b></h4>
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