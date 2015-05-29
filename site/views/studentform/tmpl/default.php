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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_fee', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/components/com_fee/assets/js/form.js');


?>
</style>
<script type="text/javascript">
    if (jQuery === 'undefined') {
        document.addEventListener("DOMContentLoaded", function(event) { 
            jQuery('#form-student').submit(function(event) {
                
            });

            
			jQuery('input:hidden.department_alias').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('department_aliashidden')){
					jQuery('#jform_department_alias option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_department_alias").trigger("liszt:updated");
			jQuery('input:hidden.course_alias').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('course_aliashidden')){
					jQuery('#jform_course_alias option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_course_alias").trigger("liszt:updated");
			jQuery('input:hidden.level_alias').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('level_aliashidden')){
					jQuery('#jform_level_alias option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_level_alias").trigger("liszt:updated");
        });
    } else {
        jQuery(document).ready(function() {
            jQuery('#form-student').submit(function(event) {
                
            });

            
			jQuery('input:hidden.department_alias').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('department_aliashidden')){
					jQuery('#jform_department_alias option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_department_alias").trigger("liszt:updated");
			jQuery('input:hidden.course_alias').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('course_aliashidden')){
					jQuery('#jform_course_alias option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_course_alias").trigger("liszt:updated");
			jQuery('input:hidden.level_alias').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('level_aliashidden')){
					jQuery('#jform_level_alias option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_level_alias").trigger("liszt:updated");
        });
    }
</script>

<div class="student-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-student" action="<?php echo JRoute::_('index.php?option=com_fee&task=student.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
        
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

	<?php if(empty($this->item->created_by)): ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
	<?php endif; ?>
	<input type="hidden" name="jform[alias]" value="<?php echo $this->item->alias; ?>" />

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
	<?php foreach((array)$this->item->department_alias as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="department_alias" name="jform[department_aliashidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('course_alias'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('course_alias'); ?></div>
	</div>
	<?php foreach((array)$this->item->course_alias as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="course_alias" name="jform[course_aliashidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('level_alias'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('level_alias'); ?></div>
	</div>
	<?php foreach((array)$this->item->level_alias as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="level_alias" name="jform[level_aliashidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('special'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('special'); ?></div>
	</div>				<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','fee')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
				<?php if (!JFactory::getUser()->authorise('core.admin','fee')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-student").appendChild(input);
                    });
                </script>
             <?php endif; ?>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="validate btn btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_fee&task=studentform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
            </div>
        </div>
        
        <input type="hidden" name="option" value="com_fee" />
        <input type="hidden" name="task" value="studentform.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
