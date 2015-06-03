<?php
/**
 * @version     1.0.0
 * @package     com_fee
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Tran Xuan Duc <ductranxuan.29710@gmail.com> - http://facebook.com/ducsatthuttd
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_fee/assets/css/fee.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function () {

        js('input:hidden.student_alias').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('student_aliashidden')) {
                js('#jform_student_alias option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_student_alias").trigger("liszt:updated");
        js('input:hidden.semester_alias').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('semester_aliashidden')) {
                js('#jform_semester_alias option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_semester_alias").trigger("liszt:updated");
        js('input:hidden.year_alias').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('year_aliashidden')) {
                js('#jform_year_alias option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_year_alias").trigger("liszt:updated");

        var student, semester, year;
        js("#jform_student_alias").change(function () {
            student = js("#jform_student_alias").val();
            data = "data=" + student;
            js('#body-table').remove();
            js('#list-student-form').hide('slow');
            if (student) {
                js.ajax({
                    type: "POST",
                    url: "index.php?option=com_fee&task=getStudent",
                    data: data,
                    datatype: "json",
                    success: function (results) {
                        results = JSON.decode(results);
                        js("#department-show").text(results);
                    }
                });
                js.ajax({
                    type: "POST",
                    url: "index.php?option=com_fee&task=getFeeStudent",
                    data: data,
                    datatype: "json",
                    success: function (results) {
                        var parse = JSON.decode(results);
                        if (parse) {
                            var tbody = '<tbody id="body-table">';
                            js.each(parse, function (k, v) {
                                tbody += '<tr id="form_body_' + k + '">';
                                tbody += "<td><a href='<?php echo JRoute::_('index.php?option=com_fee&view=fee&layout=edit&id=') ?>" + v.id + "'>" + (k + 1) + "</a></td>";
                                tbody += "<td>" + js('#jform_semester_alias option[value=' + v.semester_alias + ']').text() + "</td>";
                                tbody += "<td>" + js('#jform_year_alias option[value=' + v.year_alias + ']').text() + "</td>";
                                tbody += "<td>" + v.payable_rate + "</td>";
                                tbody += "<td id='owed_" + k + "'>" + v.owed + "</td>";
                                tbody += "</tr>";
                            });
                            tbody += "</tbody>";

                            js('#list-student').append(tbody);

                            js('#list-student-form').show('slow');
                        } else {
                            js('#body-table').remove();
                            js('#list-student-form').hide('slow');
                        }
                    }
                });
            }
            checkfee();
        }).trigger("change");
<?php if (!$this->item->id) { ?>
            js("#jform_semester_alias").change(function () {
                semester = js("#jform_semester_alias").val();
                checkfee();
            }).trigger("change");
            js("#jform_year_alias").change(function () {
                year = js("#jform_year_alias").val();
                checkfee();
            }).trigger("change");

            function checkfee() {
                js("#department-show").text("");
                js('#jform_paid').val("");
                js("tr").removeClass('error');
                js('#system-message-container-custom').hide('slow');
                if (student && semester && year) {
                    var data = {
                        "student": student,
                        "semester": semester,
                        "year": year
                    };
                    js.ajax({
                        type: "POST",
                        url: "index.php?option=com_fee&task=checkFee",
                        data: data,
                        datatype: "json",
                        success: function (results) {
                            results = JSON.decode(results);
                            if (results) {
                                var form = '#form_body_' + (results - 1);
                                js('#jform_paid').val(js('td#owed_' + (results - 1)).text());
                                js(form).addClass('error');
                                js('#text-alert-custom').text("<?php echo JText::_('COM_FEE_ERROR_FEE_EXITS'); ?>");
                                js('#system-message-container-custom').show('slow');
                            } else {
                                js('#system-message-container-custom').hide('slow');
                                js("tr").removeClass('error');
                            }
                        }
                    });
                }
            }
<?php } ?>
    });

    Joomla.submitbutton = function (task)
    {
        if (task == 'receipt.cancel') {
            Joomla.submitform(task, document.getElementById('receipt-form'));
        }
        else {

            if (task != 'receipt.cancel' && document.formvalidator.isValid(document.id('receipt-form'))) {

                Joomla.submitform(task, document.getElementById('receipt-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_fee&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="receipt-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_FEE_TITLE_RECEIPT', true)); ?>
        <div class="row-fluid">
            <div class="span7 form-horizontal">
                <fieldset class="adminform">

                    <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
                    <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
                    <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
                    <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
                    <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

                    <?php if (empty($this->item->created_by)) { ?>
                        <input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

                    <?php } else {
                        ?>
                        <input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

                    <?php } ?>				<input type="hidden" name="jform[alias]" value="<?php echo $this->item->alias; ?>" />
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('student_alias'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('student_alias'); ?></div>
                    </div>

                    <?php
                    foreach ((array) $this->item->student_alias as $value):
                        if (!is_array($value)):
                            echo '<input type="hidden" class="student_alias" name="jform[student_aliashidden][' . $value . ']" value="' . $value . '" />';
                        endif;
                    endforeach;
                    ?>			<div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('semester_alias'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('semester_alias'); ?></div>
                    </div>

                    <?php
                    foreach ((array) $this->item->semester_alias as $value):
                        if (!is_array($value)):
                            echo '<input type="hidden" class="semester_alias" name="jform[semester_aliashidden][' . $value . ']" value="' . $value . '" />';
                        endif;
                    endforeach;
                    ?>			<div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('year_alias'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('year_alias'); ?></div>
                    </div>

                    <?php
                    foreach ((array) $this->item->year_alias as $value):
                        if (!is_array($value)):
                            echo '<input type="hidden" class="year_alias" name="jform[year_aliashidden][' . $value . ']" value="' . $value . '" />';
                        endif;
                    endforeach;
                    ?>			<div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('date'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('date'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('paid'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('paid'); ?></div>
                    </div>


                </fieldset>
            </div>
            <div class="span5 form-vertical" id="list-student-form" style="display: none;">
                <legend align="center"><?php echo JText::_('COM_FEE_TITLE_STUDENT_INFO'); ?></legend>
                <h5 align="center" id="department-show"></h5>
                <table class="table table-bordered table-hover" id="list-student">
                    <thead>
                        <tr>
                            <th><?php echo JText::_('COM_FEE_ORDER_TITLE'); ?></th>
                            <th><?php echo JText::_('COM_FEE_FEES_SEMESTER_ALIAS'); ?></th>
                            <th><?php echo JText::_('COM_FEE_FEES_YEAR_ALIAS'); ?></th>
                            <th><?php echo JText::_('COM_FEE_FEES_PAYABLE_RATE'); ?></th>
                            <th><?php echo JText::_('COM_FEE_FEES_OWED'); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php if (JFactory::getUser()->authorise('core.admin', 'fee')) : ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
            <?php echo $this->form->getInput('rules'); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php endif; ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>