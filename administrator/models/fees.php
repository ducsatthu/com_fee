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
class FeeModelFees extends JModelList {

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
                'student_alias', 'a.student_alias',
                'semester_alias', 'a.semester_alias',
                'year_alias', 'a.year_alias',
                'rate', 'a.rate',
                'payable', 'a.payable',
                'owed', 'a.owed',
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

        //Filtering rate
        $this->setState('filter.rate', $app->getUserStateFromRequest($this->context . '.filter.rate', 'filter_rate', '', 'string'));

        // Load the parameters.
        $params = JComponentHelper::getParams('com_fee');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.alias', 'asc');
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
        $query->from('`#__fee_fee` AS a');


        // Join over the users for the checked out user
        $query->select("uc.name AS editor");
        $query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
        // Join over the user field 'created_by'
        $query->select('created_by.name AS created_by');
        $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
        // Join over the foreign key 'student_alias'
        $query->select('#__fee_student_1887487.student_id AS fees_alias_1887487 , #__fee_student_1887487.title AS fees_title_1887487');
        $query->join('LEFT', '#__fee_student AS #__fee_student_1887487 ON #__fee_student_1887487.alias = a.student_alias');
        // Join over the foreign key 'semester_alias'
        $query->select('#__fee_semester_1887491.title AS fees_title_1887491');
        $query->join('LEFT', '#__fee_semester AS #__fee_semester_1887491 ON #__fee_semester_1887491.alias = a.semester_alias');
        // Join over the foreign key 'year_alias'
        $query->select('#__fee_year_1887494.start AS fees_year_1887494');
        $query->join('LEFT', '#__fee_year AS #__fee_year_1887494 ON #__fee_year_1887494.alias = a.year_alias');

        // Join over the foreign key 'department_alias'
        $query->select('#__fee_department_1886853.title AS department_title_1886853');
        $query->join('LEFT', '#__fee_department AS #__fee_department_1886853 ON #__fee_department_1886853.alias = #__fee_student_1887487.department_alias');
        // Join over the foreign key 'course_alias'
        $query->select('#__fee_course_1886860.title AS course_title_1886860');
        $query->join('LEFT', '#__fee_course AS #__fee_course_1886860 ON #__fee_course_1886860.alias = #__fee_student_1887487.course_alias');
        // Join over the foreign key 'level_alias'
        $query->select('#__fee_level_1886861.title AS level_title_1886861');
        $query->join('LEFT', '#__fee_level AS #__fee_level_1886861 ON #__fee_level_1886861.alias = #__fee_student_1887487.level_alias');



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
                $query->where('( #__fee_student_1887487.title LIKE ' . $search . '  OR #__fee_student_1887487.student_id LIKE ' . $search . '  OR  #__fee_semester_1887491.title LIKE ' . $search . '  OR  #__fee_year_1887494.start LIKE ' . $search . '  OR  a.rate LIKE ' . $search . '  OR  a.payable LIKE ' . $search . '  OR  a.owed LIKE ' . $search . ' )');
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
                $query->where("#__fee_student_1887487.department_alias IN ('" . implode("','", $allDepartment) . "')");
            } else {
                $query->where("#__fee_student_1887487.department_alias = '" . $db->escape($filter_department_alias) . "'");
            }
        }

        //Filtering course_alias
        $filter_course_alias = $this->state->get("filter.course_alias");
        if ($filter_course_alias) {
            $query->where("#__fee_student_1887487.course_alias = '" . $db->escape($filter_course_alias) . "'");
        }

        //Filtering level_alias
        $filter_level_alias = $this->state->get("filter.level_alias");
        if ($filter_level_alias) {
            $query->where("#__fee_student_1887487.level_alias = '" . $db->escape($filter_level_alias) . "'");
        }

        //Filtering special
        $filter_rate = $this->state->get("filter.rate");
        if ($filter_rate) {
            if ((int) $filter_rate === 2) {
                $query->where("a.rate > 0");
            } else {
                $query->where("a.rate = 0");
            }
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
                    ->where($db->quoteName('department_alias') . ' IN (' . $query . ')');
            $query2
                    ->select($db->quoteName('alias'))
                    ->from('`#__fee_department`')
                    ->where($db->quoteName('department_alias') . ' IN (' . $query1 . ')');
            $query3
                    ->select($db->quoteName('alias'))
                    ->from('`#__fee_department`')
                    ->where($db->quoteName('department_alias') . ' IN (' . $query2 . ')');

            $query4
                    ->select($db->quoteName('alias'))
                    ->from('`#__fee_department`')
                    ->where($db->quoteName('alias') . ' = ' . $db->quote($db->escape($filter_department_alias)));

            $query->union($query1)->union($query2)->union($query3)->union($query4);

            $db->setQuery($query);
            $results = $db->loadColumn();

            if ($results) {

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
                            ->select($db->quoteName(array('student_id', 'title')))
                            ->from('`#__fee_student`')
                            ->where($db->quoteName('alias') . ' = ' . $db->quote($db->escape($value)));
                    $db->setQuery($query);
                    $results = $db->loadObject();
                    if ($results) {
                        $textValue[] = $results->student_id;
                        $oneItem->title = $results->title;
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
                            ->select($db->quoteName(array('start', 'end')))
                            ->from('`#__fee_year`')
                            ->where($db->quoteName('alias') . ' = ' . $db->quote($db->escape($value)));
                    $db->setQuery($query);
                    $results = $db->loadObject();
                    if ($results) {
                        $textValue[] = $results->start . '-' . $results->end;
                    }
                }

                $oneItem->year_alias = !empty($textValue) ? implode(', ', $textValue) : $oneItem->year_alias;
            }
        }
        return $items;
    }

    public function getItemsPrintsOwed() {
        $year = $this->getState('filter.year_alias');
        $department = $this->getState('filter.department_alias');
        $course = $this->getState('filter.course_alias');
        $level = $this->getState('filter.level_alias');

        if (!$year || !$department || !$course || !$level) {
            $this->setError(JText::_('COM_FEE_ERROR_REQUIRE_SELECTED'));
            return FALSE;
        }

        $db = JFactory::getDbo();


        //get student by department && course && level
        $querygetS = $db->getQuery(true);
        $querygetS
                ->select('`alias`')
                ->from('`#__fee_student`')
                ->where('`department_alias` = ' . $db->quote($db->escape($department)))
                ->where('`course_alias` = ' . $db->quote($db->escape($course)))
                ->where('`level_alias` = ' . $db->quote($db->escape($level)));
        $db->setQuery($querygetS);

        $listStudent = $db->loadColumn();

        if (!$listStudent) {
            $this->setError(JText::_('COM_FEE_ERROR_NOT_EXITS_STUDENTS'));
            return FALSE;
        }

        $objectYear = $this->getYearAgo($year);

        #$yearStart = $objectYear->start;
        $listYearAlias = $objectYear->listAlias;

        $listYearAliasAgo = $objectYear->listAliasAgo;

        $query = $db->getQuery(true);
        #'`student_id`','sum(`owed`) AS allowed','sum(`payable` - `payable`*rate/100) AS Pay','sum(`rate`)/count(`rate`) as rate'
        $query
                ->select(array(
                    'student_alias'
                ))
                ->from('`#__fee_fee` as fee')
                ->select('`#__fee_student`.`title`')
                ->join('LEFT', '`#__fee_student` ON `#__fee_student`.`alias` = `student_alias`')
                ->where("`student_alias` IN ('" . implode("','", $listStudent) . "')")
                ->where("`year_alias`  IN ('" . implode("','", $listYearAlias) . "')")
                ->where('`owed` > 0')
                ->group('`student_alias`');

        $db->setQuery($query);

        $items = $db->loadObjectList();

        foreach ($items as $item) {
            //list owed ago
            $query = $db->getQuery(TRUE);

            $query
                    ->select('sum(`owed`)')
                    ->from('`#__fee_fee`')
                    ->where('`student_alias` = ' . $db->quote($db->escape($item->student_alias)))
                    ->where("`year_alias`  IN ('" . implode("','", $listYearAliasAgo) . "')");

            $db->setQuery($query);

            $item->owedAgo = $db->loadResult();
            //end list owed ago
            //list rate + pay
            $query = $db->getQuery(TRUE);

            $query
                    ->select(array(
                        'sum(`rate`)/count(`rate`) as rate', 'sum(`payable` - `payable`*rate/100) AS pay'
                    ))
                    ->from('`#__fee_fee`')
                    ->where('`student_alias` = ' . $db->quote($db->escape($item->student_alias)))
                    ->where("`year_alias`  = " . $db->quote($db->escape($year)));

            $db->setQuery($query);

            $results = $db->loadObject();
            $item->rate = $results->rate;
            $item->pay = $results->pay;
            //end list rate + pay
            //get paid
            $query = $db->getQuery(TRUE);

            $query
                    ->select(array(
                        'sum(`paid`) as paid'
                    ))
                    ->from('`#__fee_receipt`')
                    ->where('`student_alias` = ' . $db->quote($db->escape($item->student_alias)))
                    ->where("`year_alias`  = " . $db->quote($db->escape($year)));

            $db->setQuery($query);

            $result = $db->loadResult();

            if ($result) {
                $item->paid = $result;
            } else {
                $item->paid = 0;
            }
            //end paid
        }


        if (!$items) {
            $this->setError(JText::_('COM_FEE_ERROR_STUDENTS_NOT_OWED'));
            return FALSE;
        }
        return $items;
    }

    /**
     * Get Item for layout print fee
     * 
     * @return boolean|object
     */
    public function getItemsPrintFee() {
        $year = $this->getState('filter.year_alias');
        $department = $this->getState('filter.department_alias');
        $course = $this->getState('filter.course_alias');
        $level = $this->getState('filter.level_alias');

        if (!$year || !$department || !$course || !$level) {
            $this->setError(JText::_('COM_FEE_ERROR_REQUIRE_SELECTED'));
            return FALSE;
        }

        $db = JFactory::getDbo();


        //get student by department && course && level
        $querygetS = $db->getQuery(true);
        $querygetS
                ->select('`alias`')
                ->from('`#__fee_student`')
                ->where('`department_alias` = ' . $db->quote($db->escape($department)))
                ->where('`course_alias` = ' . $db->quote($db->escape($course)))
                ->where('`level_alias` = ' . $db->quote($db->escape($level)));
        $db->setQuery($querygetS);

        $listStudent = $db->loadColumn();

        if (!$listStudent) {
            $this->setError(JText::_('COM_FEE_ERROR_NOT_EXITS_STUDENTS'));
            return FALSE;
        }

        //get year select
        $querySelectYear = $db->getQuery(true);

        $querySelectYear
                ->select('`start`')
                ->from('`#__fee_year`')
                ->where('`alias` = ' . $db->quote($db->escape($year)));
        $db->setQuery($querySelectYear);

        $yearStart = $db->loadResult();

        //get List year alias
        $queryListAlias = $db->getQuery(true);

        $queryListAlias
                ->select('`alias`')
                ->from('`#__fee_year`')
                ->where('`start` <= ' . $db->quote($db->escape($yearStart)));
        $db->setQuery($queryListAlias);

        $listYearAlias = $db->loadColumn();

        //get List year ago
        $queryListAliasAgo = $db->getQuery(true);

        $queryListAliasAgo
                ->select('`alias`')
                ->from('`#__fee_year`')
                ->where('`start` < ' . $db->quote($db->escape($yearStart)));
        $db->setQuery($queryListAliasAgo);

        $listYearAliasAgo = $db->loadColumn();

        $query = $db->getQuery(true);
        #'`student_id`','sum(`owed`) AS allowed','sum(`payable` - `payable`*rate/100) AS Pay','sum(`rate`)/count(`rate`) as rate'
        $query
                ->select(array(
                    'student_alias'
                ))
                ->from('`#__fee_fee` as fee')
                ->select('`#__fee_student`.`title`')
                ->join('LEFT', '`#__fee_student` ON `#__fee_student`.`alias` = `student_alias`')
                ->where("`student_alias` IN ('" . implode("','", $listStudent) . "')")
                ->where("`year_alias`  IN ('" . implode("','", $listYearAlias) . "')")
                ->group('`student_alias`');

        $db->setQuery($query);

        $items = $db->loadObjectList();

        foreach ($items as $item) {
            //list owed ago
            $query = $db->getQuery(TRUE);

            $query
                    ->select('sum(`owed`)')
                    ->from('`#__fee_fee`')
                    ->where('`student_alias` = ' . $db->quote($db->escape($item->student_alias)))
                    ->where("`year_alias`  IN ('" . implode("','", $listYearAliasAgo) . "')");

            $db->setQuery($query);

            $result = $db->loadResult();

            if ($result) {
                $item->owedAgo = $result;
            } else {
                $item->owedAgo = '0';
            }
            //end list owed ago
            //list rate + pay
            $query = $db->getQuery(TRUE);

            $query
                    ->select(array(
                        'sum(`rate`)/count(`rate`) as rate', 'sum(`payable` - `payable`*rate/100) AS pay'
                    ))
                    ->from('`#__fee_fee`')
                    ->where('`student_alias` = ' . $db->quote($db->escape($item->student_alias)))
                    ->where("`year_alias`  = " . $db->quote($db->escape($year)));

            $db->setQuery($query);

            $results = $db->loadObject();
            $item->rate = $results->rate;
            $item->pay = $results->pay;
            //end list rate + pay
            //get paid
            $query = $db->getQuery(TRUE);

            $query
                    ->select(array(
                        'sum(`paid`) as paid'
                    ))
                    ->from('`#__fee_receipt`')
                    ->where('`student_alias` = ' . $db->quote($db->escape($item->student_alias)))
                    ->where("`year_alias`  = " . $db->quote($db->escape($year)));

            $db->setQuery($query);

            $result = $db->loadResult();

            if ($result) {
                $item->paid = $result;
            } else {
                $item->paid = 0;
            }
            //end paid
        }
        return $items;
    }

    /**
     * Get items for print owed by level
     * 
     * @return boolean|object
     */
    public function getItemsPrintsOwedLevel() {
        $year = $this->getState('filter.year_alias');
        $level = $this->getState('filter.level_alias');


        if (!$year || !$level) {
            $this->setError(JText::_('COM_FEE_ERROR_REQUIRE_SELECTED_YEAR_LEVEL'));
            return FALSE;
        }
        $objectYear = $this->getYearAgo($year);

        $listYearAlias = $objectYear->listAlias;

        $listYearAgo = $objectYear->listAliasAgo;

        $db = JFactory::getDbo();

        $query = $db->getQuery(TRUE);

        $query
                ->select(array(
                    'student_alias'
                ))
                ->from('`#__fee_fee` as fee')
                ->select('`#__fee_student`.`title`')
                ->join('LEFT', '`#__fee_student` ON `#__fee_student`.`alias` = `student_alias`')
                ->select('`#__fee_department`.`title` as department')
                ->join('LEFT', '`#__fee_department` ON `#__fee_department`.alias = `#__fee_student`.`department_alias`')
                ->select('`#__fee_course`.`title` as course')
                ->join('LEFT', '`#__fee_course` ON `#__fee_course`.alias = `#__fee_student`.`course_alias`')
                ->where("`year_alias`  IN ('" . implode("','", $listYearAlias) . "')")
                ->where('`owed` > 0')
                ->group('`student_alias`');

        $db->setQuery($query);

        $items = $db->loadObjectList();


        if ($items) {
            foreach ($items as $item) {
                //TOTAL OWED AGO
                $query = $db->getQuery(TRUE);
                $query
                        ->select('sum(`owed`)')
                        ->from('`#__fee_fee` as fee')
                        ->where("`year_alias`  IN ('" . implode("','", $listYearAgo) . "')")
                        ->where('`owed` > 0')
                        ->where('`student_alias` = ' . $db->quote($db->escape($item->student_alias)))
                        ->group('`student_alias`');

                $db->setQuery($query);

                $result = $db->loadResult();

                if ($result) {
                    $item->totalOwedAgo = $result;
                } else {
                    $item->totalOwedAgo = 0;
                }
                //TOTAL PAYABLE
                $query = $db->getQuery(TRUE);
                $query
                        ->select('sum(`payable` - `payable`*`rate`/100) as totalPay')
                        ->from('`#__fee_fee`')
                        ->where("`student_alias`= " . $db->quote($db->escape($item->student_alias)))
                        ->where("`year_alias` = " . $db->quote($db->escape($year)))
                        ->group("`student_alias`");

                $db->setQuery($query);

                $result = $db->loadResult();
                if ($result) {
                    $item->totalPayable = $result;
                } else {
                    $item->totalPayable = 0;
                }
                //TOTAL PAID
                $query = $db->getQuery(TRUE);
                $query
                        ->select('sum(`paid`) as totalPaid')
                        ->from('`#__fee_receipt`')
                        ->where("`student_alias`= " . $db->quote($db->escape($item->student_alias)))
                        ->where("`year_alias` = " . $db->quote($db->escape($year)))
                        ->group("`student_alias`");

                $db->setQuery($query);

                $result = $db->loadResult();
                if ($result) {
                    $item->totalPaid = $result;
                } else {
                    $item->totalPaid = 0;
                }

                $item->totalOwed = $item->totalOwedAgo + $item->totalPayable - $item->totalPaid;
            }
        }

        return $items;
    }

    /**
     * Get Items for layout Prints Total Fee
     * 
     * @return boolean|object
     */
    public function getItemsPrintsTotalFee() {
        $year = $this->getState('filter.year_alias');
        $course = $this->getState('filter.course_alias');
        $level = $this->getState('filter.level_alias');


        $db = JFactory::getDbo();

        //Get Department + Course By level
        $query = $db->getQuery(true);

        $query
                ->select(array(
                    'count(`#__fee_student`.`id`) AS CountStudent', '`#__fee_student`.`department_alias`', '`#__fee_student`.`course_alias`'
                ))
                ->from('`#__fee_student`')
                ->select('`#__fee_department`.`title` as department')
                ->join('LEFT', '`#__fee_department` ON `#__fee_department`.`alias` = `#__fee_student`.`department_alias`')
                ->select('`#__fee_course`.`title` as course')
                ->join('LEFT', '`#__fee_course` ON `#__fee_course`.`alias` = `#__fee_student`.`course_alias`')
                ->where('`level_alias` = ' . $db->quote($db->escape($level)))
                ->where('`course_alias` =' . $db->quote($db->escape($course)))
                ->group('`department_alias`')
                ->group('`course_alias`')
                ->order('`#__fee_course`.`title`')
        ;

        $db->setQuery($query);

        $items = $db->loadObjectList();

        if (!$items) {
            $this->setError(JText::_('COM_FEE_ERROR_NOT_EXITS_STUDENTS_FROM_LEVEL'));
            return FALSE;
        }
        $objectYear = $this->getYearAgo($year);

        $listYearAliasAgo = $objectYear->listAliasAgo;
        foreach ($items as $item) {
            $query = $db->getQuery(true);

            $query
                    ->select('sum(`payable` - `payable`*`rate`/100) AS pay')
                    ->from('`#__fee_fee`')
                    //       ->select('count(`student_alias`)')
                    ->join('LEFT', '`#__fee_student` ON `#__fee_student`.`alias` = `#__fee_fee`.`student_alias` ')
                    ->where('`#__fee_student`.`course_alias` = ' . $db->quote($db->escape($item->course_alias)))
                    ->where('`#__fee_student`.`department_alias` = ' . $db->quote($db->escape($item->department_alias)))
                    ->where('`#__fee_student`.`level_alias` = ' . $db->quote($db->escape($level)))
                    ->where('`#__fee_fee`.`year_alias` = ' . $db->quote($db->escape($year)))
                    ->group(' `#__fee_student`.`department_alias`')
                    ->group(' `#__fee_student`.`course_alias`')
            ;
            ;

            $db->setQuery($query);

            $result = $db->loadResult();

            $item->pay = $result;

            if (!$result) {
                $item->pay = '0';
            }

            //get payable 100%
            $query = $db->getQuery(true);

            $query
                    ->select('count(DISTINCT `#__fee_student`.`id`) as total100percent')
                    ->from('`#__fee_student`')
                    ->join('LEFT', '`#__fee_fee` ON `#__fee_fee`.`student_alias` = `#__fee_student`.`alias`')
                    ->where("`rate` = 0")
                    ->where('`#__fee_student`.`course_alias` = ' . $db->quote($db->escape($item->course_alias)))
                    ->where('`#__fee_student`.`department_alias` = ' . $db->quote($db->escape($item->department_alias)))
                    ->where('`#__fee_student`.`level_alias` = ' . $db->quote($db->escape($level)))
                    ->where('`#__fee_fee`.`year_alias` = ' . $db->quote($db->escape($year)))
                    ->group(' `#__fee_student`.`department_alias`')
                    ->group(' `#__fee_student`.`course_alias`')
            ;
            $db->setQuery($query);

            if ($db->loadResult()) {
                $item->hundpercent = $db->loadResult();
            } else {
                $item->hundpercent = 0;
            }

            //get payable decrease
            $query = $db->getQuery(true);

            $query
                    ->select('count(DISTINCT `#__fee_student`.`id`) as total100percent')
                    ->from('`#__fee_student`')
                    ->join('LEFT', '`#__fee_fee` ON `#__fee_fee`.`student_alias` = `#__fee_student`.`alias`')
                    ->where("`rate` BETWEEN 1 AND 99")
                    ->where('`#__fee_student`.`course_alias` = ' . $db->quote($db->escape($item->course_alias)))
                    ->where('`#__fee_student`.`department_alias` = ' . $db->quote($db->escape($item->department_alias)))
                    ->where('`#__fee_student`.`level_alias` = ' . $db->quote($db->escape($level)))
                    ->where('`#__fee_fee`.`year_alias` = ' . $db->quote($db->escape($year)))
                    ->group(' `#__fee_student`.`department_alias`')
                    ->group(' `#__fee_student`.`course_alias`')
            ;
            $db->setQuery($query);

            if ($db->loadResult()) {
                $item->decrease = $db->loadResult();
            } else {
                $item->decrease = 0;
            }

            //get payable free
            $query = $db->getQuery(true);

            $query
                    ->select('count(DISTINCT `#__fee_student`.`id`) as total100percent')
                    ->from('`#__fee_student`')
                    ->join('LEFT', '`#__fee_fee` ON `#__fee_fee`.`student_alias` = `#__fee_student`.`alias`')
                    ->where("`rate` = 100")
                    ->where('`#__fee_student`.`course_alias` = ' . $db->quote($db->escape($item->course_alias)))
                    ->where('`#__fee_student`.`department_alias` = ' . $db->quote($db->escape($item->department_alias)))
                    ->where('`#__fee_student`.`level_alias` = ' . $db->quote($db->escape($level)))
                    ->where('`#__fee_fee`.`year_alias` = ' . $db->quote($db->escape($year)))
                    ->group(' `#__fee_student`.`department_alias`')
                    ->group(' `#__fee_student`.`course_alias`')
            ;
            $db->setQuery($query);

            $item->free = $db->loadResult();
            if ($db->loadResult()) {
                $item->free = $db->loadResult();
            } else {
                $item->free = 0;
            }

            //get total Owed ago
            $query = $db->getQuery(true);

            $query
                    ->select('sum(`owed`)')
                    ->from('`#__fee_fee`')
                    ->join('LEFT', '`#__fee_student` ON `#__fee_student`.`alias` = `#__fee_fee`.`student_alias`')
                    ->where("`year_alias` IN('" . implode("','", $listYearAliasAgo) . "')")
                    ->where('`#__fee_student`.`course_alias` = ' . $db->quote($db->escape($item->course_alias)))
                    ->where('`#__fee_student`.`department_alias` = ' . $db->quote($db->escape($item->department_alias)))
                    ->where('`#__fee_student`.`level_alias` = ' . $db->quote($db->escape($level)))
            ;
            $db->setQuery($query);

            $result = $db->loadResult();

            $item->totalOwed = $result;

            if (!$result) {
                $item->totalOwed = 0;
            }

            //get Paid from receipt
            $query = $db->getQuery(TRUE);

            $query
                    ->select('sum(`paid`) as paid')
                    ->from('`#__fee_receipt`')
                    ->join('LEFT', '`#__fee_student` ON `#__fee_student`.`alias` = `#__fee_receipt`.`student_alias`')
                    ->where("`year_alias` = " . $db->quote($db->escape($year)))
                    ->where('`#__fee_student`.`course_alias` = ' . $db->quote($db->escape($item->course_alias)))
                    ->where('`#__fee_student`.`department_alias` = ' . $db->quote($db->escape($item->department_alias)))
                    ->where('`#__fee_student`.`level_alias` = ' . $db->quote($db->escape($level)))
            ;

            $db->setQuery($query);

            $result = $db->loadResult();

            if ($result) {
                $item->paid = $result;
            } else {
                $item->paid = 0;
            }
        }


        return $items;
    }

    /**
     * Get Items for layout Prints Total Fee By Level
     * @return boolean|object
     */
    public function getItemsPrintsTotalFeeLevel() {
        $year = $this->getState('filter.year_alias');
        $level = $this->getState('filter.level_alias');


        if (!$year || !$level) {
            $this->setError('COM_FEE_ERROR_REQUIRE_SELECTED_YEAR_LEVEL');
            return FALSE;
        }

        $db = JFactory::getDbo();


        //Get Department + Course By level
        $query = $db->getQuery(true);

        $query
                ->select(array(
                    'count(`#__fee_student`.`id`) AS CountStudent', '`#__fee_student`.`department_alias`', '`#__fee_student`.`course_alias`'
                ))
                ->from('`#__fee_student`')
                ->select('`#__fee_department`.`title` as department')
                ->join('LEFT', '`#__fee_department` ON `#__fee_department`.`alias` = `#__fee_student`.`department_alias`')
                ->select('`#__fee_course`.`title` as course')
                ->join('LEFT', '`#__fee_course` ON `#__fee_course`.`alias` = `#__fee_student`.`course_alias`')
                ->where('`level_alias` = ' . $db->quote($db->escape($level)))
                ->group('`department_alias`')
                ->group('`course_alias`')
                ->order('`#__fee_course`.`title`')
        ;

        $db->setQuery($query);

        $items = $db->loadObjectList();

        if (!$items) {
            $this->setError(JText::_('COM_FEE_ERROR_NOT_EXITS_STUDENTS_FROM_LEVEL'));
            return FALSE;
        }



        $objectYear = $this->getYearAgo($year);

        #$yearStart = $objectYear->start;

        $listYearAliasAgo = $objectYear->listAliasAgo;

        foreach ($items as $item) {
            $query = $db->getQuery(true);

            $query
                    ->select('sum(`payable` - `payable`*`rate`/100) AS pay')
                    ->from('`#__fee_fee`')
                    //       ->select('count(`student_alias`)')
                    ->join('LEFT', '`#__fee_student` ON `#__fee_student`.`alias` = `#__fee_fee`.`student_alias` ')
                    ->where('`#__fee_student`.`course_alias` = ' . $db->quote($db->escape($item->course_alias)))
                    ->where('`#__fee_student`.`department_alias` = ' . $db->quote($db->escape($item->department_alias)))
                    ->where('`#__fee_student`.`level_alias` = ' . $db->quote($db->escape($level)))
                    ->where('`#__fee_fee`.`year_alias` = ' . $db->quote($db->escape($year)))
                    ->group(' `#__fee_student`.`department_alias`')
                    ->group(' `#__fee_student`.`course_alias`')
            ;
            ;

            $db->setQuery($query);

            $result = $db->loadResult();

            $item->pay = $result;

            if (!$result) {
                $item->pay = '0';
            }

            //get payable 100%
            $query = $db->getQuery(true);

            $query
                    ->select('count(DISTINCT `#__fee_student`.`id`) as total100percent')
                    ->from('`#__fee_student`')
                    ->join('LEFT', '`#__fee_fee` ON `#__fee_fee`.`student_alias` = `#__fee_student`.`alias`')
                    ->where("`rate` = 0")
                    ->where('`#__fee_student`.`course_alias` = ' . $db->quote($db->escape($item->course_alias)))
                    ->where('`#__fee_student`.`department_alias` = ' . $db->quote($db->escape($item->department_alias)))
                    ->where('`#__fee_student`.`level_alias` = ' . $db->quote($db->escape($level)))
                    ->where('`#__fee_fee`.`year_alias` = ' . $db->quote($db->escape($year)))
                    ->group(' `#__fee_student`.`department_alias`')
                    ->group(' `#__fee_student`.`course_alias`')
            ;
            $db->setQuery($query);

            if ($db->loadResult()) {
                $item->hundpercent = $db->loadResult();
            } else {
                $item->hundpercent = 0;
            }

            //get payable decrease
            $query = $db->getQuery(true);

            $query
                    ->select('count(DISTINCT `#__fee_student`.`id`) as total100percent')
                    ->from('`#__fee_student`')
                    ->join('LEFT', '`#__fee_fee` ON `#__fee_fee`.`student_alias` = `#__fee_student`.`alias`')
                    ->where("`rate` BETWEEN 1 AND 99")
                    ->where('`#__fee_student`.`course_alias` = ' . $db->quote($db->escape($item->course_alias)))
                    ->where('`#__fee_student`.`department_alias` = ' . $db->quote($db->escape($item->department_alias)))
                    ->where('`#__fee_student`.`level_alias` = ' . $db->quote($db->escape($level)))
                    ->where('`#__fee_fee`.`year_alias` = ' . $db->quote($db->escape($year)))
                    ->group(' `#__fee_student`.`department_alias`')
                    ->group(' `#__fee_student`.`course_alias`')
            ;
            $db->setQuery($query);

            if ($db->loadResult()) {
                $item->decrease = $db->loadResult();
            } else {
                $item->decrease = 0;
            }

            //get payable free
            $query = $db->getQuery(true);

            $query
                    ->select('count(DISTINCT `#__fee_student`.`id`) as total100percent')
                    ->from('`#__fee_student`')
                    ->join('LEFT', '`#__fee_fee` ON `#__fee_fee`.`student_alias` = `#__fee_student`.`alias`')
                    ->where("`rate` = 100")
                    ->where('`#__fee_student`.`course_alias` = ' . $db->quote($db->escape($item->course_alias)))
                    ->where('`#__fee_student`.`department_alias` = ' . $db->quote($db->escape($item->department_alias)))
                    ->where('`#__fee_student`.`level_alias` = ' . $db->quote($db->escape($level)))
                    ->where('`#__fee_fee`.`year_alias` = ' . $db->quote($db->escape($year)))
                    ->group(' `#__fee_student`.`department_alias`')
                    ->group(' `#__fee_student`.`course_alias`')
            ;
            $db->setQuery($query);

            $item->free = $db->loadResult();
            if ($db->loadResult()) {
                $item->free = $db->loadResult();
            } else {
                $item->free = 0;
            }

            //get total Owed ago
            $query = $db->getQuery(true);

            $query
                    ->select('sum(`owed`)')
                    ->from('`#__fee_fee`')
                    ->join('LEFT', '`#__fee_student` ON `#__fee_student`.`alias` = `#__fee_fee`.`student_alias`')
                    ->where("`year_alias` IN('" . implode("','", $listYearAliasAgo) . "')")
                    ->where('`#__fee_student`.`course_alias` = ' . $db->quote($db->escape($item->course_alias)))
                    ->where('`#__fee_student`.`department_alias` = ' . $db->quote($db->escape($item->department_alias)))
                    ->where('`#__fee_student`.`level_alias` = ' . $db->quote($db->escape($level)))
            ;
            $db->setQuery($query);

            $result = $db->loadResult();

            $item->totalOwed = $result;

            if (!$result) {
                $item->totalOwed = 0;
            }

            //get Paid from receipt
            $query = $db->getQuery(TRUE);

            $query
                    ->select('sum(`paid`) as paid')
                    ->from('`#__fee_receipt`')
                    ->join('LEFT', '`#__fee_student` ON `#__fee_student`.`alias` = `#__fee_receipt`.`student_alias`')
                    ->where("`year_alias` = " . $db->quote($db->escape($year)))
                    ->where('`#__fee_student`.`course_alias` = ' . $db->quote($db->escape($item->course_alias)))
                    ->where('`#__fee_student`.`department_alias` = ' . $db->quote($db->escape($item->department_alias)))
                    ->where('`#__fee_student`.`level_alias` = ' . $db->quote($db->escape($level)))
            ;

            $db->setQuery($query);

            $result = $db->loadResult();

            if ($result) {
                $item->paid = $result;
            } else {
                $item->paid = 0;
            }
        }
        return $items;
    }

    /**
     * Get Items for layout print rate
     * 
     * @return boolean|object
     */
    public function getItemsPrintsRate() {
        $year = $this->getState('filter.year_alias');
        $department = $this->getState('filter.department_alias');
        $course = $this->getState('filter.course_alias');
        $level = $this->getState('filter.level_alias');


        if (!$year || !$department || !$course || !$level) {
            $this->setError(JText::_('COM_FEE_ERROR_REQUIRE_SELECTED'));
            return FALSE;
        }
        $db = JFactory::getDbo();

//get student by department, level, course
        $queryGetStudent = $db->getQuery(TRUE);
        $queryGetStudent
                ->select('`alias`')
                ->from('`#__fee_student`')
                ->where('`department_alias` = ' . $db->quote($db->escape($department)))
                ->where('`level_alias` = ' . $db->quote($db->escape($level)))
                ->where('`course_alias` = ' . $db->quote($db->escape($course)));

        $db->setQuery($queryGetStudent);
        $listStudent = $db->loadColumn();

        if (!$listStudent) {
            $this->setError(JText::_('COM_FEE_ERROR_NOT_EXITS_STUDENTS'));
            return FALSE;
        }

// get total payable of student in selected year
        $queryGetPayable = $db->getQuery(TRUE);
        $queryGetPayable
                ->select('sum(payable) as totalPay,(sum(`rate`)/count(`rate`)) as rate')
                ->from('`#__fee_fee`')
                ->select('`#__fee_student`.`title` AS name')
                ->join('LEFT', '`#__fee_student` ON `#__fee_student`.`alias` = `#__fee_fee`.`student_alias`')
                ->where("`student_alias` IN ('" . implode("','", $listStudent) . "')")
                ->where("`year_alias` = " . $db->quote($db->escape($year)))
                ->where('`rate` > 0')
                ->group("`student_alias`");
        $db->setQuery($queryGetPayable);
        $result = $db->loadObjectList();
        foreach ($result as $key => $value) {
            @$items[$key]->totalPay = $value->totalPay;
            @$items[$key]->rate = $value->rate;
            @$items[$key]->name = $value->name;
        }
        return @$items;
    }

    /**
     * Get Infomation prints
     * 
     * @return boolean|object
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
     * get Year ago
     * 
     * @return int|object
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
