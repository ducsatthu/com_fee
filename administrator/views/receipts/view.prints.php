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
class FeeViewReceipts extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
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
            'a.title' => JText::_('COM_FEE_RECEIPTS_TITLE'),
            'a.student_alias' => JText::_('COM_FEE_RECEIPTS_STUDENT_ALIAS'),
            'a.semester_alias' => JText::_('COM_FEE_RECEIPTS_SEMESTER_ALIAS'),
            'a.department_alias' => JText::_('COM_FEE_STUDENTS_DEPARTMENT_ALIAS'),
            'a.course_alias' => JText::_('COM_FEE_STUDENTS_COURSE_ALIAS'),
            'a.level_alias' => JText::_('COM_FEE_STUDENTS_LEVEL_ALIAS'),
            'a.year_alias' => JText::_('COM_FEE_RECEIPTS_YEAR_ALIAS'),
            'a.date' => JText::_('COM_FEE_RECEIPTS_DATE'),
            'a.paid' => JText::_('COM_FEE_RECEIPTS_PAID'),
        );
    }

}
