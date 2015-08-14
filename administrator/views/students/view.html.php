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
class FeeViewStudents extends JViewLegacy {

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

        FeeHelper::addSubmenu('students');

        $this->addToolbar();

        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/fee.php';

        $state = $this->get('State');
        $canDo = FeeHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_FEE_TITLE_STUDENTS'), 'students.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/student';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('student.add', 'JTOOLBAR_NEW');
                JToolBarHelper::addNew('student.adds', 'COM_FEE_ADDS');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('student.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('students.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('students.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'students.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('students.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('students.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'students.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('students.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_fee');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_fee&view=students');

        $this->extra_sidebar = '';

        JHtmlSidebar::addFilter(
                JText::_('JOPTION_SELECT_PUBLISHED'), 'filter_published', JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)
        );

        //Filter for the field department_alias;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_fee.student', 'student');

        $field = $form->getField('department_alias');

        $query = $form->getFieldAttribute('filter_department_alias', 'query');
        $translate = $form->getFieldAttribute('filter_department_alias', 'translate');
        $key = $form->getFieldAttribute('filter_department_alias', 'key_field');
        $value = $form->getFieldAttribute('filter_department_alias', 'value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items)) {
            foreach ($items as $item) {
                if ($translate == true) {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                } else {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
                '$Đơn vị trực thuộc', 'filter_department_alias', JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.department_alias')), true
        );
        //Filter for the field course_alias;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_fee.student', 'student');

        $field = $form->getField('course_alias');

        $query = $form->getFieldAttribute('filter_course_alias', 'query');
        $translate = $form->getFieldAttribute('filter_course_alias', 'translate');
        $key = $form->getFieldAttribute('filter_course_alias', 'key_field');
        $value = $form->getFieldAttribute('filter_course_alias', 'value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items)) {
            foreach ($items as $item) {
                if ($translate == true) {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                } else {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
                '$Khóa', 'filter_course_alias', JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.course_alias')), true
        );
        //Filter for the field level_alias;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_fee.student', 'student');

        $field = $form->getField('level_alias');

        $query = $form->getFieldAttribute('filter_level_alias', 'query');
        $translate = $form->getFieldAttribute('filter_level_alias', 'translate');
        $key = $form->getFieldAttribute('filter_level_alias', 'key_field');
        $value = $form->getFieldAttribute('filter_level_alias', 'value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items)) {
            foreach ($items as $item) {
                if ($translate == true) {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                } else {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
                '$Loại trình độ', 'filter_level_alias', JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.level_alias')), true
        );
        //Filter for the field special
        $select_label = JText::sprintf('COM_FEE_FILTER_SELECT_LABEL', 'Dự thính');
        $options = array();
        $options[0] = new stdClass();
        $options[0]->value = "0";
        $options[0]->text = "Không dự thính";
        $options[1] = new stdClass();
        $options[1]->value = "1";
        $options[1]->text = "Dự thính";
        JHtmlSidebar::addFilter(
                $select_label, 'filter_special', JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.special'), true)
        );
    }

    protected function getSortFields() {
        return array(
            'a.id' => JText::_('JGRID_HEADING_ID'),
            'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'a.state' => JText::_('JSTATUS'),
            'a.student_id' => JText::_('COM_FEE_STUDENTS_STUDENT_ID'),
            'a.title' => JText::_('COM_FEE_STUDENTS_TITLE'),
            'a.department_alias' => JText::_('COM_FEE_STUDENTS_DEPARTMENT_ALIAS'),
            'a.course_alias' => JText::_('COM_FEE_STUDENTS_COURSE_ALIAS'),
            'a.level_alias' => JText::_('COM_FEE_STUDENTS_LEVEL_ALIAS'),
            'a.special' => JText::_('COM_FEE_STUDENTS_SPECIAL'),
            'a.born' => JText::_('COM_FEE_STUDENTS_BORN'),
        );
    }

}
