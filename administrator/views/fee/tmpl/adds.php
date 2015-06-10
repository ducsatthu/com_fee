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
        js('#system-message-container-custom').hide();
        Joomla.submitbutton = function (task)
        {
            if (task == 'fee.cancel') {
                Joomla.submitform(task, document.getElementById('fee-form'));
            }
            else {
                if (task == 'fee.apply') {
                    if (!js('#jform_level_alias').val()) {
                        js('#jform_level_alias').addClass('invalid');
                    } else
                    if (!js('#jform_department_alias').val()) {
                        js('#jform_department_alias').addClass('invalid');
                    } else
                    if (!js('#jform_course_alias').val()) {
                        js('#jform_course_alias').addClass('invalid');
                    } else
                    if (!js('#jform_semester_alias').val()) {
                        js('#jform_semester_alias').addClass('invalid');
                    } else
                    if (!js('#jform_year_alias').val()) {
                        js('#jform_year_alias').addClass('invalid');
                    } else
                    if (!js('#jform_payable').val()) {
                        js('#jform_payable').addClass('invalid');
                    } else {
                        js('#toolbar-cancel').hide();
                        js('#warning').show();
                        js('#system-message-container-custom').show();
                        var data = {
                            "level_alias": js('#jform_level_alias').val(),
                            "department_alias": js('#jform_department_alias').val(),
                            "course_alias": js('#jform_course_alias').val(),
                            "semester_alias": js('#jform_semester_alias').val(),
                            "year_alias": js('#jform_year_alias').val(),
                            "payable": js('#jform_payable').val(),
                        };
                        js.ajax({
                            type: "POST",
                            url: "index.php?option=com_fee&task=addsDo",
                            data: data,
                            datatype: "json",
                            success: function (results) {
                                js('#warning').hide();
                                js('#system-message-container-custom').hide();
                                js('#toolbar-cancel').show();
                                var error, data;
                                data = JSON.decode(results);
                                if (data.error) {
                                    var texterror = "Lỗi:";
                                    js.each(data.error, function (k, v) {
                                        error = v + "__";
                                        texterror += error;
                                    });
                                    js('#text-alert-error-custom').text(texterror);
                                    js('#error').show('slow');
                                    js('#system-message-container-custom').show('slow');
                                }
                                if(data.save){
                                    var textsuccess = "Lưu Thành công : ";
                                    textsuccess += ('  '+data.save.length +' Bản ghi');
                                    js('#text-alert-success-custom').text(textsuccess);
                                    js('#success').show('slow');
                                    js('#system-message-container-custom').show('slow');
                                }

                            },
                            error: function (xhr, status, error) {
                                var err = eval("(" + xhr.responseText + ")");
                                alert(err.Message);
                            }

                        });
                    }

                }
            }
        }
    });
</script>

<form action="<?php echo JRoute::_('index.php?option=com_fee&layout=adds'); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="fee-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_FEE_TITLE_FEE', true)); ?>
        <div class="row-fluid">
            <div class="span7 form-horizontal">
                <div id="system-message-container-custom" >
                    <div class="alert alert-error" style="display: none;" id="error">
                        <p id="text-alert-error-custom">
                        </p>
                    </div>
                    <div class="alert alert-warning" style="display: none;" id="warning" >
                        <p id="text-alert-warning-custom">Đang Xử lý xin chờ trong giây lát</p>
                    </div>
                     <div class="alert alert-success" style="display: none;" id="success" >
                        <p id="text-alert-success-custom"></p>
                    </div>
                </div>
                <fieldset class="adminform">
                    <input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
                    <?php
                    echo $this->form->getControlGroup('level_alias');
                    echo $this->form->getControlGroup('department_alias');
                    echo $this->form->getControlGroup('course_alias');
                    echo $this->form->getControlGroup('semester_alias');
                    echo $this->form->getControlGroup('year_alias');
                    echo $this->form->getControlGroup('payable');
                    ?>

                </fieldset>
            </div>
            <div class="span5 form-vertical alert alert-info" id="list-student-form" style="display: block;">
                <legend align="center">Chú ý</legend>
                <ul>
                    <li>Toàn bộ sinh viên trong lớp sẽ được cập nhật tiền học phí</li>
                    <li>Những sinh viên đã thêm học phí kỳ này (kỳ đã nhập) sẽ được cập nhật lại theo mức học phí mới</li>
                    <li>Những sinh viên được thêm mới học phí sẽ có mức miễn giảm bằng không </li>
                    <li>Những sinh viên đã được thêm rồi sẽ không cập nhật lại mức miễn giảm</li>
                    
                </ul>
                <div class="alert alert-warning">
                    Cẩn thận:
                    <ol>
                        <li>Không nhấn lưu nhiều lần</li> 
                        <li>Đã nhấn lưu để trình duyệt mở cho đến khi dữ liệu được thêm thành công</li> 
                        <li>Không tắt trình duyệt khi chưa có kết quả</li> 
                    </ol>
                </div>
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