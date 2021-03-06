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
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_fee/assets/css/fee.css');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canOrder = $user->authorise('core.edit.state', 'com_fee');
$saveOrder = $listOrder == 'a.ordering';
if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_fee&task=fees.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'feeList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
    Joomla.orderTable = function () {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>') {
            dirn = 'asc';
        } else {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }

    jQuery(document).ready(function () {
        jQuery('#clear-search-button').on('click', function () {
            jQuery('#filter_search').val('');
            jQuery('#adminForm').submit();
        });
    });

</script>

<?php
//Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar)) {
    $this->sidebar .= $this->extra_sidebar;
}
$totalRate = 0;
$totalPayable = 0;
$totalOwed = 0;
?>

<form action="<?php echo JRoute::_('index.php?option=com_fee&view=fees'); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (!empty($this->sidebar)): ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
        <?php else : ?>
            <div id="j-main-container">
            <?php endif; ?>
            <div class="btn-toolbar">
                <div class="btn-wrapper" id="toolbar-print">
                    <a onclick="Joomla.submitbutton('fees.printsOwed')" class="btn btn-small">
                        <span class="icon-print"></span>
                        <?php
                        echo JText::_('COM_FEE_PRINTS_OWED_FEE');
                        ?>
                    </a>
                </div>
                <div class="btn-wrapper" id="toolbar-print">
                    <button onclick="Joomla.submitbutton('fees.printsOwedcourse')" class="btn btn-small">
                        <span class="icon-print"></span>
                        <?php
                        echo JText::_('COM_FEE_PRINTS_OWED_FEE_COURSE');
                        ?>
                    </button>
                </div>

                <div class="btn-wrapper" id="toolbar-print">
                    <button onclick="Joomla.submitbutton('fees.printsOwedlevel')" class="btn btn-small">
                        <span class="icon-print"></span>
                        <?php
                        echo JText::_('COM_FEE_PRINTS_OWED_FEE_LEVEL');
                        ?>
                    </button>
                </div>
                <div class="btn-wrapper" id="toolbar-print">
                    <button onclick="Joomla.submitbutton('fees.printFee')" class="btn btn-small">
                        <span class="icon-print"></span>
                        <?php
                        echo JText::_('COM_FEE_PRINTS_FEE_DEPARTMENT');
                        ?>
                    </button>
                </div>

                <div class="btn-wrapper" id="toolbar-print">
                    <button onclick="Joomla.submitbutton('fees.printTotalFee')" class="btn btn-small">
                        <span class="icon-print"></span>
                        <?php
                        echo JText::_('COM_FEE_PRINTS_TOTAL_FEE');
                        ?>
                    </button>
                </div>
                <div class="btn-wrapper" id="toolbar-print">
                    <button onclick="Joomla.submitbutton('fees.printTotalFeeLevel')" class="btn btn-small">
                        <span class="icon-print"></span>
                        <?php
                        echo JText::_('COM_FEE_PRINTS_TOTAL_FEE_LEVEL');
                        ?>
                    </button>
                </div>
                <div class="btn-wrapper" id="toolbar-print">
                    <button onclick="Joomla.submitbutton('fees.printRate')" class="btn btn-small">
                        <span class="icon-print"></span>
                        <?php
                        echo JText::_('COM_FEE_PRINTS_RATE');
                        ?>
                    </button>
                </div>
            </div>
            <div id="filter-bar" class="btn-toolbar">
                <div class="filter-search btn-group pull-left">
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER'); ?></label>
                    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
                </div>
                <div class="btn-group pull-left">
                    <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                    <button class="btn hasTooltip" id="clear-search-button" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
                    <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
                        <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
                        <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
                    </select>
                </div>
                <div class="btn-group pull-right">
                    <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
                    <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
                        <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                    </select>
                </div>
            </div>        
            <div class="clearfix"> </div>
            <table class="table table-striped" id="feeList">
                <thead>
                    <tr>
                        <?php if (isset($this->items[0]->ordering)): ?>
                            <th width="1%" class="nowrap center hidden-phone">
                                <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                            </th>
                        <?php endif; ?>
                        <th width="1%" class="hidden-phone">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        </th>
                        <?php if (isset($this->items[0]->state)): ?>
                            <th width="1%" class="nowrap center">
                                <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                            </th>
                        <?php endif; ?>

                        <th class='left'>
                            <?php echo JHtml::_('grid.sort', 'COM_FEE_FEES_TITLE', 'a.title', $listDirn, $listOrder); ?>
                        </th>
                        <th class='left'>
                            <?php echo JHtml::_('grid.sort', 'COM_FEE_FEES_STUDENT_ALIAS', 'a.student_alias', $listDirn, $listOrder); ?>
                        </th>
                        <th class='left'>
                            <?php echo JHtml::_('grid.sort', 'COM_FEE_FEES_SEMESTER_ALIAS', 'a.semester_alias', $listDirn, $listOrder); ?>
                        </th>
                        <th class='left'>
                            <?php echo JHtml::_('grid.sort', 'COM_FEE_FEES_YEAR_ALIAS', 'a.year_alias', $listDirn, $listOrder); ?>
                        </th>
                        <th class='left'>
                            <?php echo JHtml::_('grid.sort', 'COM_FEE_FEES_RATE', 'a.rate', $listDirn, $listOrder); ?>
                        </th>
                        <th class='left'>
                            <?php echo JHtml::_('grid.sort', 'COM_FEE_FEES_PAYABLE', 'a.payable', $listDirn, $listOrder); ?>
                        </th>
                        <th class='left'>
                            <?php echo JHtml::_('grid.sort', 'COM_FEE_FEES_OWED', 'a.owed', $listDirn, $listOrder); ?>
                        </th>


                        <?php if (isset($this->items[0]->id)): ?>
                            <th width="1%" class="nowrap center hidden-phone">
                                <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                            </th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tfoot>
                    <?php
                    if (isset($this->items[0])) {
                        $colspan = count(get_object_vars($this->items[0]));
                    } else {
                        $colspan = 10;
                    }
                    ?>
                    <tr>
                        <td colspan="<?php echo $colspan ?>">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    foreach ($this->items as $i => $item) :
                        $ordering = ($listOrder == 'a.ordering');
                        $canCreate = $user->authorise('core.create', 'com_fee');
                        $canEdit = $user->authorise('core.edit', 'com_fee');
                        $canCheckin = $user->authorise('core.manage', 'com_fee');
                        $canChange = $user->authorise('core.edit.state', 'com_fee');
                        ?>
                        <tr class="row<?php echo $i % 2; ?>">

                            <?php if (isset($this->items[0]->ordering)): ?>
                                <td class="order nowrap center hidden-phone">
                                    <?php
                                    if ($canChange) :
                                        $disableClassName = '';
                                        $disabledLabel = '';
                                        if (!$saveOrder) :
                                            $disabledLabel = JText::_('JORDERINGDISABLED');
                                            $disableClassName = 'inactive tip-top';
                                        endif;
                                        ?>
                                        <span class="sortable-handler hasTooltip <?php echo $disableClassName ?>" title="<?php echo $disabledLabel ?>">
                                            <i class="icon-menu"></i>
                                        </span>
                                        <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
                                    <?php else : ?>
                                        <span class="sortable-handler inactive" >
                                            <i class="icon-menu"></i>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                            <td class="hidden-phone">
                                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            </td>
                            <?php if (isset($this->items[0]->state)): ?>
                                <td class="center">
                                    <?php echo JHtml::_('jgrid.published', $item->state, $i, 'fees.', $canChange, 'cb'); ?>
                                </td>
                            <?php endif; ?>

                            <td>

                                <?php echo $item->title; ?>
                            </td>
                            <td>

                                <?php echo $item->student_alias; ?>
                            </td>
                            <td>

                                <?php echo $item->semester_alias; ?>
                            </td>
                            <td>

                                <?php echo $item->year_alias; ?>
                            </td>
                            <td>

                                <?php
                                echo $item->rate . '% / ' . number_format($item->payable * $item->rate / 100);
                                $totalRate += $item->payable * $item->rate / 100;
                                ?>
                            </td>
                            <td>

                                <?php
                                echo number_format($item->payable);
                                $totalPayable += $item->payable
                                ?>
                            </td>
                            <td>

                                <?php
                                echo number_format($item->owed);
                                $totalOwed += $item->owed;
                                ?>
                            </td>


                            <?php if (isset($this->items[0]->id)): ?>
                                <td class="center hidden-phone">
                                    <?php echo (int) $item->id; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                    <tr>
                        <td colspan="6">
                        </td>
                        <td>
                            <h6 class="text-info"><?php echo JText::_('COM_FEE_TOTAL'); ?></h6>
                        </td>
                        <td>
                            <h6> <?php echo number_format($totalRate); ?></h6>
                        </td>
                        <td>
                            <h6> <?php echo number_format($totalPayable); ?></h6>
                        </td>
                        <td colspan="2">
                            <h6> <?php echo number_format($totalOwed); ?></h6>
                        </td>
                    </tr>
                    </tr>
                </tbody>
            </table>

            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
</form>        
