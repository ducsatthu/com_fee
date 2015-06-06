<?php
require_once JPATH_COMPONENT . '/helpers/convert.php';;
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
                        <p style="text-transform: uppercase;font-size: 9pt;"><?php echo JComponentHelper::getParams('com_fee')->get('name_school', 'Trường đại học mỏ - địa chất'); ?></p>
                        <h6 style="text-transform: uppercase"><?php echo JComponentHelper::getParams('com_fee')->get('department', 'Phòng tài vụ'); ?></h6>
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td align="center">
                        <h3 style="text-transform: uppercase"><?php echo JText::_('COM_FEE_LIST_OWED'); ?></h3>
                        <p style="text-transform: uppercase">Năm Học: <?php echo $this->info->year; ?></p>
                        <h5 style="text-transform: uppercase">LỚP: <?php echo $this->info->level . " " . $this->info->department . " - K" . $this->info->course; ?></h5>
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
                    <th align="center" colspan="4"> Học phí phải đóng trong năm </th>

                    <th align="center"  rowspan = "2">
                <p >Số tiền<br>đã đóng</p>   
                </th>
                <th align="center"  rowspan = "2">
                    Số tiền<br>nợ
                </th>
                </tr>
                <!-- gop dong -->
                <tr>
                    <th  align="center">Giảm %</th>
                    <th  align="center"><p> Nợ<br>năm cũ</p></th>
                <th  align="center"><p> Số tiền phải đóng<br>trong năm nay</p></th>
                <th  align="center"> Tổng tiền<br>phải đóng</th>
                </tr>

                <?php
                $i = 1;
                $totalOweds = 0;
                foreach ($this->items as $item) {
                    ?>
                    <tr>
                        <td align="center"><?php echo $i++; ?></td>
                        <td align='center'><?php echo $item->title; ?></td>
                        <td align='right'> <?php echo $item->rate; ?> </td>
                        <td align="right">
                            <?php
                                if($item->owedAgo){
                                    echo number_format($item->owedAgo,'0',' ',' ');
                                }else{
                                    echo 0;
                                }
                            ?>
                        </td>
                        <td align="right">  <?php echo number_format($item->pay,'0',' ',' '); ?></td>
                        <td align="right"> 
                        <?php
                            $totalPay = $item->owedAgo + $item->pay;
                            echo number_format($totalPay,'0',' ',' ');
                        ?>
                        </td>
                        <td align="right"> 
                        <?php
                            echo number_format($item->paid,'0',' ',' ');
                        ?>
                        </td>
                        <td align="right" style="border-right: 1px solid black;">
                        <?php
                            $totalOwed = $totalPay - $item->paid;
                            echo number_format($totalOwed,'0',' ',' ');
                            $totalOweds += $totalOwed;
                        ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>

                

                <!-- Cộng -->
                <tr>
                    <td colspan="6" style="border-top: 1px solid black;border-bottom: 1px solid black;border-right: 0px solid black;"></td>
                    <td align="center" style="border-right:0px;border-left:0px;border-top: 1px solid black;border-bottom: 1px solid black;">Tổng nợ : </td>
                    <td align="center" style="border-right: 1px solid black;border-left: 0px;border-top: 1px solid black;border-bottom: 1px solid black;"  align="center">
                    <span>
                        <?php
                        echo number_format($totalOweds,'0',' ',' ');
                        ?>
                     </span>
                    </td>
                </tr>
            </table>
            <div style="padding-top:2mm">
            </div>
            <div style="padding-left: 5mm" align="left">
                <p>
                    <i>Cộng số tiền nộp ( Bằng chữ ):<i>
                            <span><?php echo FeeHelperConvert::convert_number_to_words($totalOweds); ?> đồng chẵn</span> 
                            </p>
                            </div>
                            <table>
                                <tr>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="center">
                                        <p>&nbsp;</p>
                                        <h4>Phụ trách kế toán</h4>
                                    </td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

                                    <td align="center">
                                        <?php
                                        $now = new DateTime();
                                        $day = date_format($now, 'd');
                                        ?>
                                        <p>Hà nội, ngày <?php echo date_format($now, 'd'); ?> tháng <?php echo date_format($now, 'm'); ?> năm <?php echo date_format($now, 'Y'); ?></p>
                                        <h4><b>Cán bộ thu tiên</b></h4>
                                    </td>
                                </tr>
                            </table>
                            <br>
                            </div>




                            </body>
                            </html>