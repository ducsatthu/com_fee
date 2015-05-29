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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_fee.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_fee' . $this->item->id)) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
            <tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_FEE_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_FEE_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_FEE_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_FEE_ALIAS'); ?></th>
			<td><?php echo $this->item->alias; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_FEE_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_FEE_STUDENT_ALIAS'); ?></th>
			<td><?php echo $this->item->student_alias; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_FEE_SEMESTER_ALIAS'); ?></th>
			<td><?php echo $this->item->semester_alias; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_FEE_YEAR_ALIAS'); ?></th>
			<td><?php echo $this->item->year_alias; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_FEE_RATE'); ?></th>
			<td><?php echo $this->item->rate; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_FEE_PAYABLE'); ?></th>
			<td><?php echo $this->item->payable; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_FEE_OWED'); ?></th>
			<td><?php echo $this->item->owed; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_fee&task=fee.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_FEE_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_fee.fee.'.$this->item->id)):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_fee&task=fee.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_FEE_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_FEE_ITEM_NOT_LOADED');
endif;
?>
