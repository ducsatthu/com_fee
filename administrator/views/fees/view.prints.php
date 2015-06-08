<?php

/**
 * @version     1.0.0
 * @package     com_fee
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Tran Xuan Duc <ductranxuan.29710@gmail.com> - http://facebook.com/ducsatthuttd
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Fee.
 */
class FeeViewFees extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $info;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');

        if ($this->_layout === 'print_owed') {
            $this->items = $this->get('ItemsPrintsOwed');
        }
        if ($this->_layout === 'print_fee') {
            $this->items = $this->get('ItemsPrintFee');
        }
        if ($this->_layout === 'print_rate') {
            $this->items = $this->get('ItemsPrintsRate');
        }
        if ($this->_layout === 'print_total') {
            $this->items = $this->get('ItemsPrintsTotalFee');
        }
        if ($this->_layout === 'print_total_level') {
            $this->items = $this->get('ItemsPrintsTotalFeeLevel');
        }
        if ($this->_layout === 'print_owed_level') {
            $this->items = $this->get('ItemsPrintsOwedLevel');
        }
        $this->info = $this->get('Info');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }


        parent::display($tpl);
    }

    protected function getSortFields() {
        return array(
            'a.id' => JText::_('JGRID_HEADING_ID'),
            'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'a.state' => JText::_('JSTATUS'),
            'a.title' => JText::_('COM_FEE_FEES_TITLE'),
            'a.student_alias' => JText::_('COM_FEE_FEES_STUDENT_ALIAS'),
            'a.semester_alias' => JText::_('COM_FEE_FEES_SEMESTER_ALIAS'),
            'a.year_alias' => JText::_('COM_FEE_FEES_YEAR_ALIAS'),
            'a.department_alias' => JText::_('COM_FEE_STUDENTS_DEPARTMENT_ALIAS'),
            'a.course_alias' => JText::_('COM_FEE_STUDENTS_COURSE_ALIAS'),
            'a.level_alias' => JText::_('COM_FEE_STUDENTS_LEVEL_ALIAS'),
            'a.rate' => JText::_('COM_FEE_FEES_RATE'),
            'a.payable' => JText::_('COM_FEE_FEES_PAYABLE'),
            'a.owed' => JText::_('COM_FEE_FEES_OWED'),
        );
    }

}
