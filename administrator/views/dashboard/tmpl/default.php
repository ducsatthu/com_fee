<?php
/**
 * @version     1.0.0
 * @package     com_fee
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Tran Xuan Duc <ductranxuan.29710@gmail.com> - http://facebook.com/ducsatthuttd
 */
defined('_JEXEC') or die;
// Import CSS
$document = JFactory::getDocument();
?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        
        var year_alias, level_alias, department_alias, course_alias;
        jQuery('#jform_year_alias').change(function () {
            year_alias = jQuery('#jform_year_alias').val();
            saveSession();
        });
        jQuery('#jform_level_alias').change(function () {
            level_alias = jQuery('#jform_level_alias').val();
            saveSession();
        });
        jQuery('#jform_department_alias').change(function () {
            department_alias = jQuery('#jform_department_alias').val();
            saveSession();
        });
        jQuery('#jform_course_alias').change(function () {
            course_alias = jQuery('#jform_course_alias').val();
            saveSession();
        });
        jQuery('#jform_department_alias option[value="<?php echo @$this->item->department_alias; ?>"]').attr('selected', true);
        jQuery("#jform_department_alias").trigger("liszt:updated");

        jQuery('#jform_level_alias option[value="<?php echo @$this->item->level_alias; ?>"]').attr('selected', true);
        jQuery("#jform_level_alias").trigger("liszt:updated");


        jQuery('#jform_course_alias option[value="<?php echo @$this->item->course_alias; ?>"]').attr('selected', true);
        jQuery("#jform_course_alias").trigger("liszt:updated");

        jQuery('#jform_year_alias option[value="<?php echo @$this->item->year_alias; ?>"]').attr('selected', true);
        jQuery("#jform_year_alias").trigger("liszt:updated");
        
        function saveSession() {
            var data = {
                "year_alias": year_alias,
                "level_alias": level_alias,
                "department_alias": department_alias,
                "course_alias": course_alias
            }
            jQuery.ajax({
                type: "POST",
                url: "index.php?option=com_fee&task=dashboard.saveSession",
                data: data,
                datatype: "json",
                success: function (results) {
                    
                }
            });
        }
        
    });

</script>
<!-- Main content -->
<div class="container">

    <?php if (!empty($this->sidebar)): ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
        <?php else : ?>
            <div id="j-main-container">
            <?php endif; ?>
            <section class="content-header">
                <h1>
                    Bảng Tin
                </h1>
            </section>
            <section class="content">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Chọn Các Mục Muốn Thao Tác</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="span6 form-horizontal">
                            <fieldset class="adminform">

                                <?php
                                echo $this->form->getControlGroup('year_alias');

                                echo $this->form->getControlGroup('level_alias');

                                echo $this->form->getControlGroup('department_alias');

                                echo $this->form->getControlGroup('course_alias');
                                ?>

                            </fieldset>
                        </div>
                        <div class="span6">
                            <table>
                                <tr align='center'>
                                    <td align='center'>
                                        <a href="index.php?option=com_fee&view=fees&task=fees.printsOwed"  target="_blank" class="btn btn-primary btn-flat"><span class="fa fa-print fa-2x"></span><br><?php echo JText::_('COM_FEE_PRINTS_OWED_FEE'); ?></a>
                                    </td>
                                    <td  align='center'>
                                        <a href="index.php?option=com_fee&view=fees&task=fees.printsOwedCourse"  target="_blank" class="btn btn-primary btn-flat"><span class="fa fa-print fa-2x"></span><br><?php echo JText::_('COM_FEE_PRINTS_OWED_FEE_COURSE'); ?></a>
                                    </td>
                                    <td  align='center'>
                                        <a href="index.php?option=com_fee&view=fees&task=fees.printsOwedlevel" target="_blank" class="btn btn-primary btn-flat"><span class="fa fa-print fa-2x"></span><br><?php echo JText::_('COM_FEE_PRINTS_OWED_FEE_LEVEL'); ?></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align='center'>
                                        <a href="index.php?option=com_fee&view=fees&task=fees.printFee" target="_blank" class="btn btn-info btn-flat"><span class="fa fa-print fa-2x"></span><br><?php echo JText::_('COM_FEE_PRINTS_FEE_DEPARTMENT'); ?></a>
                                    </td>
                                    <td href="index.php?option=com_fee&view=fees&task=fees.printTotalFee" target="_blank" align='center'>
                                        <a class="btn btn-info btn-flat"><span class="fa fa-print fa-2x"></span><br><?php echo JText::_('COM_FEE_PRINTS_TOTAL_FEE'); ?></a>
                                    </td>
                                    <td align='center'>
                                        <a href="index.php?option=com_fee&view=fees&task=fees.printTotalFeeLevel" target="_blank" class="btn btn-info btn-flat"><span class="fa fa-print fa-2x"></span><br><?php echo JText::_('COM_FEE_PRINTS_TOTAL_FEE_LEVEL'); ?></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>

                                    </td>
                                    <td>
                                        <a href="index.php?option=com_fee&view=fees&task=fees.printRate" target="_blank" class="btn btn-success btn-flat"><span class="fa fa-print fa-2x"></span><br><?php echo JText::_('COM_FEE_PRINTS_RATE'); ?></a>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </section> 
            <section class="content">
                <div class="row">
                    <div class="span6">
                        <!-- Line CHART -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Tổng Thu (Toàn Bộ)</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="chart">
                                    <div id="totalReceipt" style="height: 250px;"></div>
                                    <script>
                                        new Morris.Line({
                                            // ID of the element in which to draw the chart.
                                            element: 'totalReceipt',
                                            // Chart data records -- each entry in this array corresponds to a point on
                                            // the chart.
                                            data: <?php echo $this->totalReceipts; ?>,
                                            // The name of the data record attribute that contains x-values.
                                            xkey: 'start',
                                            // A list of names of data record attributes that contain y-values.
                                            ykeys: ['total'],
                                            // Labels for the ykeys -- will be displayed when you hover over the
                                            // chart.
                                            labels: ['<?php echo JText::_('COM_FEE_TOTAL'); ?>'],
                                            resize: true,
                                            lineColors: ['#f56954'],
                                            hideHover: 'auto'
                                        });
                                    </script>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->

                    </div><!-- /.col (LEFT) -->
                    <div class="span6">

                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title">Tổng thu theo hệ</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <div class="chart">
                                    <div id="totalbylevel" style="height: 250px;"></div>
                                    <script>
                                        // AREA CHART
                                        var area = new Morris.Area({
                                            element: 'totalbylevel',
                                            resize: true,
                                            data: <?php echo $this->totalReceiptByLevel; ?>,
                                            xkey: 'start',
                                            ykeys: <?php echo $this->keyLevel; ?>,
                                            labels: <?php echo $this->titleLevel; ?>,
                                            lineColors: <?php echo $this->color; ?>,
                                            hideHover: 'auto'
                                        });
                                    </script>
                                </div>
                            </div><!-- /.box-body -->

                        </div><!-- /.box -->

                    </div><!-- /.col (RIGHT) -->
                </div><!-- /.row -->

            </section><!-- /.content -->
        </div>
    </div>
</div>