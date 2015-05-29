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
        
	js('input:hidden.student_alias').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('student_aliashidden')){
			js('#jform_student_alias option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_student_alias").trigger("liszt:updated");
	js('input:hidden.semester_alias').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('semester_aliashidden')){
			js('#jform_semester_alias option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_semester_alias").trigger("liszt:updated");
	js('input:hidden.year_alias').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('year_aliashidden')){
			js('#jform_year_alias option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_year_alias").trigger("liszt:updated");
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'fee.cancel') {
            Joomla.submitform(task, document.getElementById('fee-form'));
        }
        else {
            
            if (task != 'fee.cancel' && document.formvalidator.isValid(document.id('fee-form'))) {
                
                Joomla.submitform(task, document.getElementById('fee-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_fee&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="fee-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_FEE_TITLE_FEE', true)); ?>
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
				<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('student_alias'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('student_alias'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->student_alias as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="student_alias" name="jform[student_aliashidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('semester_alias'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('semester_alias'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->semester_alias as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="semester_alias" name="jform[semester_aliashidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('year_alias'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('year_alias'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->year_alias as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="year_alias" name="jform[year_aliashidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('rate'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('rate'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('payable'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('payable'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('owed'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('owed'); ?></div>
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