<?php

/**
 * @version     1.0.0
 * @package     com_fee
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Linh <mr.lynk92@gmail.com> - http://
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Fee records.
 */
class FeeModelStudents extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     *
     * @see        JController
     * @since      1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'alias', 'a.alias',
                'student_id', 'a.student_id',
                'title', 'a.title',
                'department_alias', 'a.department_alias',
                'course_alias', 'a.course_alias',
                'level_alias', 'a.level_alias',
                'special', 'a.special',
            );
        }
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since    1.6
     */
    protected function populateState($ordering = null, $direction = null) {


        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = $app->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);

        if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array')) {
            foreach ($list as $name => $value) {
                // Extra validations
                switch ($name) {
                    case 'fullordering':
                        $orderingParts = explode(' ', $value);

                        if (count($orderingParts) >= 2) {
                            // Latest part will be considered the direction
                            $fullDirection = end($orderingParts);

                            if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', ''))) {
                                $this->setState('list.direction', $fullDirection);
                            }

                            unset($orderingParts[count($orderingParts) - 1]);

                            // The rest will be the ordering
                            $fullOrdering = implode(' ', $orderingParts);

                            if (in_array($fullOrdering, $this->filter_fields)) {
                                $this->setState('list.ordering', $fullOrdering);
                            }
                        } else {
                            $this->setState('list.ordering', $ordering);
                            $this->setState('list.direction', $direction);
                        }
                        break;

                    case 'ordering':
                        if (!in_array($value, $this->filter_fields)) {
                            $value = $ordering;
                        }
                        break;

                    case 'direction':
                        if (!in_array(strtoupper($value), array('ASC', 'DESC', ''))) {
                            $value = $direction;
                        }
                        break;

                    case 'limit':
                        $limit = $value;
                        break;

                    // Just to keep the default case
                    default:
                        $value = $value;
                        break;
                }

                $this->setState('list.' . $name, $value);
            }
        }

        // Receive & set filters
        if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array')) {
            foreach ($filters as $name => $value) {
                $this->setState('filter.' . $name, $value);
            }
        }

        $ordering = $app->input->get('filter_order');
        if (!empty($ordering)) {
            $list = $app->getUserState($this->context . '.list');
            $list['ordering'] = $app->input->get('filter_order');
            $app->setUserState($this->context . '.list', $list);
        }

        $orderingDirection = $app->input->get('filter_order_Dir');
        if (!empty($orderingDirection)) {
            $list = $app->getUserState($this->context . '.list');
            $list['direction'] = $app->input->get('filter_order_Dir');
            $app->setUserState($this->context . '.list', $list);
        }

        $list = $app->getUserState($this->context . '.list');

        if (empty($list['ordering'])) {
            $list['ordering'] = 'ordering';
        }

        if (empty($list['direction'])) {
            $list['direction'] = 'asc';
        }

        $this->setState('list.ordering', $list['ordering']);
        $this->setState('list.direction', $list['direction']);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return    JDatabaseQuery
     * @since    1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query
                ->select(
                        $this->getState(
                                'list.select', 'DISTINCT a.*'
                        )
        );

        $query->from('`#__fee_student` AS a');


        // Join over the users for the checked out user.
        $query->select('uc.name AS editor');
        $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

        // Join over the created by field 'created_by'
        $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
        // Join over the foreign key 'department_alias'
        $query->select('#__fee_department_1886853.title AS departments_title_1886853');
        $query->join('LEFT', '#__fee_department AS #__fee_department_1886853 ON #__fee_department_1886853.alias = a.department_alias');
        // Join over the foreign key 'course_alias'
        $query->select('#__fee_course_1886860.title AS courses_title_1886860');
        $query->join('LEFT', '#__fee_course AS #__fee_course_1886860 ON #__fee_course_1886860.alias = a.course_alias');
        // Join over the foreign key 'level_alias'
        $query->select('#__fee_level_1886861.title AS levels_title_1886861');
        $query->join('LEFT', '#__fee_level AS #__fee_level_1886861 ON #__fee_level_1886861.alias = a.level_alias');


        if (!JFactory::getUser()->authorise('core.edit.state', 'com_fee')) {
            $query->where('a.state = 1');
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.student_id LIKE ' . $search . '  OR  a.title LIKE ' . $search . ' )');
            }
        }



        //Filtering department_alias
        $filter_department_alias = $this->state->get("filter.department_alias");
        if ($filter_department_alias) {
            $allDepartment = $this->getAllDepartment($filter_department_alias);
            if ($allDepartment) {
                $query->where("a.department_alias IN ('" . implode("','", $allDepartment) . "')");
            } else {
                $query->where("a.department_alias = '" . $db->escape($filter_department_alias) . "'");
            }
        }

        //Filtering course_alias
        $filter_course_alias = $this->state->get("filter.course_alias");
        if ($filter_course_alias) {
            $query->where("a.course_alias = '" . $db->escape($filter_course_alias) . "'");
        }

        //Filtering level_alias
        $filter_level_alias = $this->state->get("filter.level_alias");
        if ($filter_level_alias) {
            $query->where("a.level_alias = '" . $db->escape($filter_level_alias) . "'");
        }

        //Filtering special
        $filter_special = $this->state->get("filter.special");
        if ($filter_special) {
            $query->where("a.special = '" . $db->escape($filter_special) . "'");
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
            
            $query->union($query1)->union($query2)->union($query3);
            
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
        foreach ($items as $item) {


            if (isset($item->department_alias) && $item->department_alias != '') {
                if (is_object($item->department_alias)) {
                    $item->department_alias = JArrayHelper::fromObject($item->department_alias);
                }
                $values = (is_array($item->department_alias)) ? $item->department_alias : explode(',', $item->department_alias);

                $textValue = array();
                foreach ($values as $value) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query
                            ->select($db->quoteName('title'))
                            ->from('`#__fee_department`')
                            ->where($db->quoteName('alias') . ' = ' . $db->quote($db->escape($value)));
                    $db->setQuery($query);
                    $results = $db->loadObject();
                    if ($results) {
                        $textValue[] = $results->title;
                    }
                }

                $item->department_alias = !empty($textValue) ? implode(', ', $textValue) : $item->department_alias;
            }

            if (isset($item->course_alias) && $item->course_alias != '') {
                if (is_object($item->course_alias)) {
                    $item->course_alias = JArrayHelper::fromObject($item->course_alias);
                }
                $values = (is_array($item->course_alias)) ? $item->course_alias : explode(',', $item->course_alias);

                $textValue = array();
                foreach ($values as $value) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query
                            ->select($db->quoteName('title'))
                            ->from('`#__fee_course`')
                            ->where($db->quoteName('alias') . ' = ' . $db->quote($db->escape($value)));
                    $db->setQuery($query);
                    $results = $db->loadObject();
                    if ($results) {
                        $textValue[] = $results->title;
                    }
                }

                $item->course_alias = !empty($textValue) ? implode(', ', $textValue) : $item->course_alias;
            }

            if (isset($item->level_alias) && $item->level_alias != '') {
                if (is_object($item->level_alias)) {
                    $item->level_alias = JArrayHelper::fromObject($item->level_alias);
                }
                $values = (is_array($item->level_alias)) ? $item->level_alias : explode(',', $item->level_alias);

                $textValue = array();
                foreach ($values as $value) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query
                            ->select($db->quoteName('title'))
                            ->from('`#__fee_level`')
                            ->where($db->quoteName('alias') . ' = ' . $db->quote($db->escape($value)));
                    $db->setQuery($query);
                    $results = $db->loadObject();
                    if ($results) {
                        $textValue[] = $results->title;
                    }
                }

                $item->level_alias = !empty($textValue) ? implode(', ', $textValue) : $item->level_alias;
            }
            $item->special = JText::_('COM_FEE_STUDENTS_SPECIAL_OPTION_' . strtoupper($item->special));
        }

        return $items;
    }

    /**
     * Overrides the default function to check Date fields format, identified by
     * "_dateformat" suffix, and erases the field if it's not correct.
     */
    protected function loadFormData() {
        $app = JFactory::getApplication();
        $filters = $app->getUserState($this->context . '.filter', array());
        $error_dateformat = false;
        foreach ($filters as $key => $value) {
            if (strpos($key, '_dateformat') && !empty($value) && !$this->isValidDate($value)) {
                $filters[$key] = '';
                $error_dateformat = true;
            }
        }
        if ($error_dateformat) {
            $app->enqueueMessage(JText::_("COM_FEE_SEARCH_FILTER_DATE_FORMAT"), "warning");
            $app->setUserState($this->context . '.filter', $filters);
        }

        return parent::loadFormData();
    }

    /**
     * Checks if a given date is valid and in an specified format (YYYY-MM-DD)
     *
     * @param string Contains the date to be checked
     *
     */
    private function isValidDate($date) {
        return preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $date) && date_create($date);
    }

}
