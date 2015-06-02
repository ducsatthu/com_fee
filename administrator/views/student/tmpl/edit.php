<?php
/**
 * @version     1.0.0
 * @package     com_fee
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Linh <mr.lynk92@gmail.com> - http://
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
    js(document).ready(function() {
        
	js('input:hidden.department_alias').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('department_aliashidden')){
			js('#jform_department_alias option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_department_alias").trigger("liszt:updated");
	js('input:hidden.course_alias').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('course_aliashidden')){
			js('#jform_course_alias option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_course_alias").trigger("liszt:updated");
	js('input:hidden.level_alias').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('level_aliashidden')){
			js('#jform_level_alias option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_level_alias").trigger("liszt:updated");
        
        //check student_id exits
        js('input#jform_student_id').blur(function(){
            var student_id = "student_id="+js('input#jform_student_id').val();
            js.ajax({
                type: "POST",
                url: "index.php?option=com_fee&task=checkStudentId",
                data: student_id,
                datatype: "json",
                success: function (results) {
                    
                }
            });
        });
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'student.cancel') {
            Joomla.submitform(task, document.getElementById('student-form'));
        }
        else {
            
            if (task != 'student.cancel' && document.formvalidator.isValid(document.id('student-form'))) {
                
                Joomla.submitform(task, document.getElementById('student-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_fee&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="student-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_FEE_TITLE_STUDENT', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>				<input type="hidden" name="jform[alias]" value="<?php echo $this->item->alias; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('student_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('student_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('department_alias'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('department_alias'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->department_alias as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="department_alias" name="jform[department_aliashidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('course_alias'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('course_alias'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->course_alias as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="course_alias" name="jform[course_aliashidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('level_alias'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('level_alias'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->level_alias as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="level_alias" name="jform[level_aliashidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('special'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('special'); ?></div>
			</div>


                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        <?php if (JFactory::getUser()->authorise('core.admin','fee')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>