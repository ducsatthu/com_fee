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
 * View to edit
 */
class FeeViewStudent extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');
        if (!$this->item->id) {
            $session = JFactory::getSession();
            $department = $session->get('department', '');
            $course = $session->get('course', '');
            $level = $session->get('level', '');
            if ($department && $course && $level) {
                $this->item->department_alias = $department;
                $this->item->course_alias = $course;
                $this->item->level_alias = $level;
            }
        }


        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar() {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $user = JFactory::getUser();
        $isNew = ($this->item->id == 0);
        if (isset($this->item->checked_out)) {
            $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
        $canDo = FeeHelper::getActions();

        JToolBarHelper::title(JText::_('COM_FEE_TITLE_STUDENT'), 'student.png');

        if ($this->_layout !== 'upload') {

            // If not checked out, can save the item.
            if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create')))) {

                JToolBarHelper::apply('student.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('student.save', 'JTOOLBAR_SAVE');
            }
            if (!$checkedOut && ($canDo->get('core.create'))) {
                JToolBarHelper::custom('student.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            }
            // If an existing item, can save to a copy.
            if (!$isNew && $canDo->get('core.create')) {
                if ($this->state->params->get('save_history', 0) && $user->authorise('core.edit')) {
                    JToolbarHelper::versions('com_fee.student', $this->item->id);
                }
                // JToolBarHelper::custom('student.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
            }
            if (empty($this->item->id)) {
                JToolBarHelper::cancel('student.cancel', 'JTOOLBAR_CANCEL');
            } else {
                JToolBarHelper::cancel('student.cancel', 'JTOOLBAR_CLOSE');
            }
        }
    }

}
