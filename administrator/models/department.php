<?php

/**
 * @version     1.0.0
 * @package     com_fee
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Linh <mr.lynk92@gmail.com> - http://
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Fee model.
 */
class FeeModelDepartment extends JModelAdmin {

    /**
     * @var		string	The prefix to use with controller messages.
     * @since	1.6
     */
    protected $text_prefix = 'COM_FEE';
    public $typeAlias = 'com_fee.department';

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Department', $prefix = 'FeeTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param	array	$data		An optional array of data for the form to interogate.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	JForm	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm($data = array(), $loadData = true) {
        // Initialise variables.
        $app = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_fee.department', 'department', array('control' => 'jform', 'load_data' => $loadData));


        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData() {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_fee.edit.department.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Method to get a single record.
     *
     * @param	integer	The id of the primary key.
     *
     * @return	mixed	Object on success, false on failure.
     * @since	1.6
     */
    public function getItem($pk = null) {
        if ($item = parent::getItem($pk)) {

            //Do any procesing on fields here if needed
        }

        return $item;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @since	1.6
     */
    protected function prepareTable($table) {
        jimport('joomla.filter.output');

        if (empty($table->id)) {

            // Set ordering to the last item if not set
            if (@$table->ordering === '') {
                $db = JFactory::getDbo();
                $db->setQuery('SELECT MAX(ordering) FROM #__fee_department');
                $max = $db->loadResult();
                $table->ordering = $max + 1;
            }
        }
    }

    public function checkDepartment($title) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
                ->select('alias')
                ->from('`#__fee_department`')
                ->where("`title` LIKE '%" . $db->escape($title) . "%'");
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    public function insertDepartment($title) {
        $table = $this->getTable();
        $user = JFactory::getUser();

        $bind = array(
            'id' => 0,
            'state' => 0,
            'created_by' => $user->id, //Lay id nguoi tao
            'title' => $title,
        );

        if ($table->bind($bind)) {
            if ($table->check()) {
                return $table->store();
            }else{
                return $this->checkDepartment($title);
            }
        } 
        return FALSE;
        
    }

}
