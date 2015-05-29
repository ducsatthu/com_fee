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
			<th><?php echo JText::_('COM_FEE_FORM_LBL_RECEIPT_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_RECEIPT_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_RECEIPT_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_RECEIPT_ALIAS'); ?></th>
			<td><?php echo $this->item->alias; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_RECEIPT_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_RECEIPT_STUDENT_ALIAS'); ?></th>
			<td><?php echo $this->item->student_alias; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_RECEIPT_SEMESTER_ALIAS'); ?></th>
			<td><?php echo $this->item->semester_alias; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_RECEIPT_YEAR_ALIAS'); ?></th>
			<td><?php echo $this->item->year_alias; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_RECEIPT_DATE'); ?></th>
			<td><?php echo $this->item->date; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_FEE_FORM_LBL_RECEIPT_PAID'); ?></th>
			<td><?php echo $this->item->paid; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_fee&task=receipt.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_FEE_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_fee.receipt.'.$this->item->id)):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_fee&task=receipt.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_FEE_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_FEE_ITEM_NOT_LOADED');
endif;
?>