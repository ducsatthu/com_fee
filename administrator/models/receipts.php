<?php

/**
 * @version     1.0.0
 * @package     com_fee
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Tran Xuan Duc <ductranxuan.29710@gmail.com> - http://facebook.com/ducsatthuttd
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Fee records.
 */
class FeeModelReceipts extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'alias', 'a.alias',
                'title', 'a.title',
                'student_alias', 'a.student_alias',
                'semester_alias', 'a.semester_alias',
                'year_alias', 'a.year_alias',
                'date', 'a.date',
                'paid', 'a.paid',
                
                'department_alias', 'a.department_alias',
                'course_alias', 'a.course_alias',
                'level_alias', 'a.level_alias',
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);


        //Filtering student_alias
        $this->setState('filter.student_alias', $app->getUserStateFromRequest($this->context . '.filter.student_alias', 'filter_student_alias', '', 'string'));

        //Filtering semester_alias
        $this->setState('filter.semester_alias', $app->getUserStateFromRequest($this->context . '.filter.semester_alias', 'filter_semester_alias', '', 'string'));

        //Filtering year_alias
        $this->setState('filter.year_alias', $app->getUserStateFromRequest($this->context . '.filter.year_alias', 'filter_year_alias', '', 'string'));
        
         //Filtering department_alias
        $this->setState('filter.department_alias', $app->getUserStateFromRequest($this->context . '.filter.department_alias', 'filter_department_alias', '', 'string'));

        //Filtering course_alias
        $this->setState('filter.course_alias', $app->getUserStateFromRequest($this->context . '.filter.course_alias', 'filter_course_alias', '', 'string'));

        //Filtering level_alias
        $this->setState('filter.level_alias', $app->getUserStateFromRequest($this->context . '.filter.level_alias', 'filter_level_alias', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_fee');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.title', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'DISTINCT a.*'
                )
        );
        $query->from('`#__fee_receipt` AS a');


        // Join over the users for the checked out user
        $query->select("uc.name AS editor");
        $query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
        // Join over the user field 'created_by'
        $query->select('created_by.name AS created_by');
        $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
        // Join over the foreign key 'student_alias'
        $query->select('#__fee_student_1887542.student_id AS students_alias_1887542, #__fee_student_1887542.title AS students_title_1887542');
        $query->join('LEFT', '#__fee_student AS #__fee_student_1887542 ON #__fee_student_1887542.alias = a.student_alias');
        // Join over the foreign key 'semester_alias'
        $query->select('#__fee_semester_1887545.title AS semesters_title_1887545');
        $query->join('LEFT', '#__fee_semester AS #__fee_semester_1887545 ON #__fee_semester_1887545.alias = a.semester_alias');
        // Join over the foreign key 'year_alias'
        $query->select('#__fee_year_1887547.start AS years_title_1887547');
        $query->join('LEFT', '#__fee_year AS #__fee_year_1887547 ON #__fee_year_1887547.alias = a.year_alias');

         // Join over the foreign key 'department_alias'
        $query->select('#__fee_department_1886853.title AS department_title_1886853');
        $query->join('LEFT', '#__fee_department AS #__fee_department_1886853 ON #__fee_department_1886853.alias = #__fee_student_1887542.department_alias');
        // Join over the foreign key 'course_alias'
        $query->select('#__fee_course_1886860.title AS course_title_1886860');
        $query->join('LEFT', '#__fee_course AS #__fee_course_1886860 ON #__fee_course_1886860.alias = #__fee_student_1887542.course_alias');
        // Join over the foreign key 'level_alias'
        $query->select('#__fee_level_1886861.title AS level_title_1886861');
        $query->join('LEFT', '#__fee_level AS #__fee_level_1886861 ON #__fee_level_1886861.alias = #__fee_student_1887542.level_alias');


        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('a.state = ' . (int) $published);
        } else if ($published === '') {
            $query->where('(a.state IN (0, 1))');
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( #__fee_student_1887542.title LIKE ' . $search . '  OR a.title LIKE ' . $search . '  OR  #__fee_student_1887542.student_id  LIKE ' . $search . '  OR  #__fee_semester_1887545.title LIKE ' . $search . '  OR  #__fee_year_1887547.start LIKE ' . $search . ' OR  a.paid LIKE ' . $search . ' )');
            }
        }



        //Filtering student_alias
        $filter_student_alias = $this->state->get("filter.student_alias");
        if ($filter_student_alias) {
            $query->where("a.student_alias = '" . $db->escape($filter_student_alias) . "'");
        }

        //Filtering semester_alias
        $filter_semester_alias = $this->state->get("filter.semester_alias");
        if ($filter_semester_alias) {
            $query->where("a.semester_alias = '" . $db->escape($filter_semester_alias) . "'");
        }

        //Filtering year_alias
        $filter_year_alias = $this->state->get("filter.year_alias");
        if ($filter_year_alias) {
            $query->where("a.year_alias = '" . $db->escape($filter_year_alias) . "'");
        }

         //Filtering department_alias
        $filter_department_alias = $this->state->get("filter.department_alias");
        if ($filter_department_alias) {
            $allDepartment = $this->getAllDepartment($filter_department_alias);
            if ($allDepartment) {
                $query->where("#__fee_student_1887542.department_alias IN ('" . implode("','", $allDepartment) . "')");
            } else {
                $query->where("#__fee_student_1887542.department_alias = '" . $db->escape($filter_department_alias) . "'");
            }
        }

        //Filtering course_alias
        $filter_course_alias = $this->state->get("filter.course_alias");
        if ($filter_course_alias) {
            $query->where("#__fee_student_1887542.course_alias = '" . $db->escape($filter_course_alias) . "'");
        }

        //Filtering level_alias
        $filter_level_alias = $this->state->get("filter.level_alias");
        if ($filter_level_alias) {
            $query->where("#__fee_student_1887542.level_alias = '" . $db->escape($filter_level_alias) . "'");
        }
        
        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }
    
    public function getAllDepartment($filter_department_alias) {
        if ($filter_department_alias) {
            $db = $this->getDbo();
            $query = $db->getQuery(true);
            $query1 = $db->getQuery(true);
            $query2 = $db->getQuery(true);
            $query3 = $db->getQuery(true);
            $query4 = $db->getQuery(true);

            $query
                    ->select($db->quoteName('alias'))
                    ->from('`#__fee_department`')
                    ->where($db->quoteName('department_alias') . ' = ' . $db->quote($db->escape($filter_department_alias)));
            $query1
                    ->select($db->quoteName('alias'))
                    ->from('`#__fee_department`')
                    ->where($db->quoteName('department_alias') . ' IN (' . $query .')');
            $query2
                    ->select($db->quoteName('alias'))
                    ->from('`#__fee_department`')
                    ->where($db->quoteName('department_alias') . ' IN (' . $query1 .')');
            $query3
                    ->select($db->quoteName('alias'))
                    ->from('`#__fee_department`')
                    ->where($db->quoteName('department_alias') . ' IN (' . $query2 .')');
            
            $query4
                    ->select($db->quoteName('alias'))
                    ->from('`#__fee_department`')
                    ->where($db->quoteName('alias') . ' = ' . $db->quote($db->escape($filter_department_alias)));
            
            $query->union($query1)->union($query2)->union($query3)->union($query4);
            
            $db->setQuery($query);
            $results = $db->loadColumn();
            
            if($results){
                
                return $results;
            }
            return FALSE;
        }
        return FALSE;
    }

    public function getItems() {
        $items = parent::getItems();

        foreach ($items as $oneItem) {

            if (isset($oneItem->student_alias)) {
                $values = explode(',', $oneItem->student_alias);

                $textValue = array();
                foreach ($values as $value) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query
                            ->select(array(
                                $db->quoteName('student_id'),$db->quoteName('title')
                            ))
                            ->from('`#__fee_student`')
                            ->where($db->quoteName('alias') . ' = ' . $db->quote($db->escape($value)));
                    $db->setQuery($query);
                    $results = $db->loadObject();
                    if ($results) {
                        $textValue[] = $results->student_id;
                        $oneItem->student_name = $results->title;
                    }
                }

                $oneItem->student_alias = !empty($textValue) ? implode(', ', $textValue) : $oneItem->student_alias;
            }

            if (isset($oneItem->semester_alias)) {
                $values = explode(',', $oneItem->semester_alias);

                $textValue = array();
                foreach ($values as $value) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query
                            ->select($db->quoteName('title'))
                            ->from('`#__fee_semester`')
                            ->where($db->quoteName('alias') . ' = ' . $db->quote($db->escape($value)));
                    $db->setQuery($query);
                    $results = $db->loadObject();
                    if ($results) {
                        $textValue[] = $results->title;
                    }
                }

                $oneItem->semester_alias = !empty($textValue) ? implode(', ', $textValue) : $oneItem->semester_alias;
            }

            if (isset($oneItem->year_alias)) {
                $values = explode(',', $oneItem->year_alias);

                $textValue = array();
                foreach ($values as $value) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query
                            ->select("CONCAT(CAST(`start` AS CHAR),' - ',CAST(`end` AS CHAR)) AS start")
                            ->from('`#__fee_year`')
                            ->where($db->quoteName('alias') . ' = ' . $db->quote($db->escape($value)));
                    $db->setQuery($query);
                    $results = $db->loadObject();
                    if ($results) {
                        $textValue[] = $results->start;
                    }
                }

                $oneItem->year_alias = !empty($textValue) ? implode(', ', $textValue) : $oneItem->year_alias;
            }
        }
        return $items;
    }
    
    /**
     * Get Items for prints persion
     * @return boolean|Object
     */
    public function getItemsPrintsPerson() {

        $arrayCID = json_decode(JFactory::getApplication()->input->getString('cid'));


        $year = $this->getState('filter.year_alias');

        if (!$year || !$arrayCID) {
            $this->setError(JText::_('COM_FEE_RECEIPT_PERSON_ERROR_SELECTED'));
            return FALSE;
        }
        $db = JFactory::getDbo();
        $objectYear = $this->getYearAgo($year);
        $listYearAgo = $objectYear->listAliasAgo;

        for ($i = 0; $i < count($arrayCID); $i++) {
            $queryStudent = $db->getQuery(TRUE);
            $queryStudent
                    ->select('DISTINCT `student_alias` ')
                    ->from('`#__fee_receipt`')
                    ->where('`year_alias` = ' . $db->quote($db->escape($year)))
                    ->where('`id` = ' . $db->quote($db->escape($arrayCID[$i])));
            $db->setQuery($queryStudent);

            $listStudent[$i] = $db->loadResult();
        }
        $listStudent = array_unique($listStudent);


        for ($i = 0; $i < count($listStudent); $i++) {
            //Get Receipt + Student info
            $queryReceipt = $db->getQuery(TRUE);
            $queryReceipt
                    ->select(array(
                        '`#__fee_receipt`.`id`',
                        '`#__fee_receipt`.`title`',
                        '`#__fee_receipt`.`date`',
                        '`#__fee_receipt`.`paid`',
                        '`#__fee_student`.`title` as name',
                        '`#__fee_level`.`title` as level',
                        '`#__fee_course`.`title` as course',
                        '`#__fee_department`.`title` as department',
                    ))
                    ->from('`#__fee_receipt`')
                    ->from('`#__fee_student`')
                    ->from('`#__fee_level`')
                    ->from('`#__fee_course`')
                    ->from('`#__fee_department`')
                    ->where('`#__fee_receipt`.`year_alias` = ' . $db->quote($db->escape($year)))
                    ->where('`#__fee_receipt`.`student_alias` = ' . $db->quote($db->escape($listStudent[$i])))
                    ->where('`#__fee_student`.`alias` = `#__fee_receipt`.`student_alias`')
                    ->where('`#__fee_student`.`level_alias` = `#__fee_level`.`alias`')
                    ->where('`#__fee_student`.`course_alias` = `#__fee_course`.`alias`')
                    ->where('`#__fee_student`.`department_alias` = `#__fee_department`.`alias`');
            $db->setQuery($queryReceipt);

            @$item[$i]->receipt = $db->loadObjectList();


            //get Payable
            $queryPayable = $db->getQuery(TRUE);
            $queryPayable
                    ->select('sum(`payable`) as payable')
                    ->from('`#__fee_fee`')
                    ->where("`student_alias`= " . $db->quote($db->escape($listStudent[$i])))
                    ->where("`year_alias` = " . $db->quote($db->escape($year)));

            $db->setQuery($queryPayable);
            @$item[$i]->receipt[0]->payable = $db->loadResult();

            if ($db->loadResult()) {
                @$item[$i]->receipt[0]->payable = $db->loadResult();
            } else
                @$item[$i]->receipt[0]->payable = 0;

            //get Owed Ago
            $queryOwed = $db->getQuery(TRUE);
            $queryOwed
                    ->select('sum(`owed`) as owed_ago')
                    ->from('`#__fee_fee` as fee')
                    ->where("`year_alias`  IN ('" . implode("','", $listYearAgo) . "')")
                    ->where("`student_alias`= " . $db->quote($db->escape($listStudent[$i])));

            $db->setQuery($queryOwed);
            if ($db->loadResult()) {
                @$item[$i]->receipt[0]->owed_ago = $db->loadResult();
            } else
                @$item[$i]->receipt[0]->owed_ago = 0;
        }
        return $item;
    }
    
    /**
     * Get infomation
     * @return type|object
     */
    public function getInfo() {

        $year = $this->getState('filter.year_alias');
        $department = $this->getState('filter.department_alias');
        $course = $this->getState('filter.course_alias');
        $level = $this->getState('filter.level_alias');


        $item = (object) array();
        $db = JFactory::getDbo();
        if ($year) {
            $queryYear = $db->getQuery(TRUE);

            $queryYear
                    ->select("CONCAT(CAST(`start` AS CHAR), ' - ',CAST(`end` AS CHAR))")
                    ->from('`#__fee_year`')
                    ->where('`alias` = ' . $db->quote($db->escape($year)));

            $db->setQuery($queryYear);

            $item->year = $db->loadResult();
        }
        if ($department) {
            $queryDepartment = $db->getQuery(TRUE);
            $queryDepartment
                    ->select('`title`')
                    ->from('`#__fee_department`')
                    ->where('`alias` = ' . $db->quote($db->escape($department)));
            $db->setQuery($queryDepartment);

            $item->department = $db->loadResult();
        }

        if ($course) {
            $queryCourse = $db->getQuery(TRUE);

            $queryCourse
                    ->select('`title`')
                    ->from('`#__fee_course`')
                    ->where('`alias` = ' . $db->quote($db->escape($course)));
            $db->setQuery($queryCourse);

            $item->course = $db->loadResult();
        }

        if ($level) {
            $queryLevel = $db->getQuery(TRUE);

            $queryLevel
                    ->select('`title`')
                    ->from('`#__fee_level`')
                    ->where('`alias` = ' . $db->quote($db->escape($level)));
            $db->setQuery($queryLevel);

            $item->level = $db->loadResult();
        }
        return $item;
    }
    
    /**
     * Get year ago
     * @param type $year
     * @return int
     */
    private function getYearAgo($year) {

        if ($year) {
            $db = JFactory::getDbo();

            //get year select
            $querySelectYear = $db->getQuery(true);

            $querySelectYear
                    ->select('`start`')
                    ->from('`#__fee_year`')
                    ->where('`alias` = ' . $db->quote($db->escape($year)));
            $db->setQuery($querySelectYear);

            @$item->start = $db->loadResult();

            //get List year alias
            $queryListAlias = $db->getQuery(true);

            $queryListAlias
                    ->select('`alias`')
                    ->from('`#__fee_year`')
                    ->where('`start` <= ' . $db->quote($db->escape($item->start)));
            $db->setQuery($queryListAlias);

            @$item->listAlias = $db->loadColumn();

            //get List year ago
            $queryListAliasAgo = $db->getQuery(true);

            $queryListAliasAgo
                    ->select('`alias`')
                    ->from('`#__fee_year`')
                    ->where('`start` < ' . $db->quote($db->escape($item->start)));
            $db->setQuery($queryListAliasAgo);

            @$item->listAliasAgo = $db->loadColumn();

            return $item;
        }
        return 0;
    }
}
