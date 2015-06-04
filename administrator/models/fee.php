<?php

/**
 * @version     1.0.0
 * @package     com_fee
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
* @author      Tran Xuan Duc <ductranxuan.29710@gmail.com> - http://facebook.com/ducsatthuttd
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Fee model.
 */
class FeeModelFee extends JModelAdmin {

    /**
     * @var		string	The prefix to use with controller messages.
     * @since	1.6
     */
    protected $text_prefix = 'COM_FEE';
    public $typeAlias = 'com_fee.fee';
    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Fee', $prefix = 'FeeTable', $config = array()) {
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
        $form = $this->loadForm('com_fee.fee', 'fee', array('control' => 'jform', 'load_data' => $loadData));


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
        $data = JFactory::getApplication()->getUserState('com_fee.edit.fee.data', array());

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
                $db->setQuery('SELECT MAX(ordering) FROM #__fee_fee');
                $max = $db->loadResult();
                $table->ordering = $max + 1;
            }
        }
    }

    public function getItemsByStudent($student = NULL) {
        if ($student) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query
                    ->select(array(
                        '`id`', '`semester_alias`', '`year_alias`', '`payable`-(`payable`*`rate`/100) AS payable_rate', '`owed`'
                    ))
                    ->from('`#__fee_fee`')
                    ->where('`student_alias` = ' . $db->quote($db->escape($student)));
            $db->setQuery($query);
            $results = $db->loadObjectList();
            if ($results) {
                return $results;
            }
            return FALSE;
        }
        return FALSE;
    }

    public function checkFeeByParam($param = array()) {
        if (!empty($param) && is_array($param)) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query
                    ->select('`id`')
                    ->from('`#__fee_fee`')
                    ->where('`student_alias` = ' . $db->quote($db->escape($param['student'])))
                    ->where('`semester_alias` = ' . $db->quote($db->escape($param['semester'])))
                    ->where('`year_alias` = ' . $db->quote($db->escape($param['year'])));
            $db->setQuery($query);
            $results = $db->loadResult();
            if ($results) {
                return $results;
            }
            return FALSE;
        }
        return FALSE;
    }

}
