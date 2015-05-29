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
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);


        //Filtering department_alias
        $this->setState('filter.department_alias', $app->getUserStateFromRequest($this->context . '.filter.department_alias', 'filter_department_alias', '', 'string'));

        //Filtering course_alias
        $this->setState('filter.course_alias', $app->getUserStateFromRequest($this->context . '.filter.course_alias', 'filter_course_alias', '', 'string'));

        //Filtering level_alias
        $this->setState('filter.level_alias', $app->getUserStateFromRequest($this->context . '.filter.level_alias', 'filter_level_alias', '', 'string'));

        //Filtering special
        $this->setState('filter.special', $app->getUserStateFromRequest($this->context . '.filter.special', 'filter_special', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_fee');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.student_id', 'asc');
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
        $query->from('`#__fee_student` AS a');


        // Join over the users for the checked out user
        $query->select("uc.name AS editor");
        $query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
        // Join over the user field 'created_by'
        $query->select('created_by.name AS created_by');
        $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
        // Join over the foreign key 'department_alias'
        $query->select('#__fee_department_1886853.title AS department_title_1886853');
        $query->join('LEFT', '#__fee_department AS #__fee_department_1886853 ON #__fee_department_1886853.alias = a.department_alias');
        // Join over the foreign key 'course_alias'
        $query->select('#__fee_course_1886860.title AS course_title_1886860');
        $query->join('LEFT', '#__fee_course AS #__fee_course_1886860 ON #__fee_course_1886860.alias = a.course_alias');
        // Join over the foreign key 'level_alias'
        $query->select('#__fee_level_1886861.title AS level_title_1886861');
        $query->join('LEFT', '#__fee_level AS #__fee_level_1886861 ON #__fee_level_1886861.alias = a.level_alias');



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
                $query->where('( a.student_id LIKE ' . $search . '  OR  a.title LIKE ' . $search . '  OR  #__fee_department_1886853.title LIKE ' . $search . '  OR  #__fee_course_1886860.title LIKE ' . $search . '  OR #__fee_level_1886861.title LIKE ' . $search . '  OR  a.special LIKE ' . $search . ' )');
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

        foreach ($items as $oneItem) {

            if (isset($oneItem->department_alias)) {
                $values = explode(',', $oneItem->department_alias);

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

                $oneItem->department_alias = !empty($textValue) ? implode(', ', $textValue) : $oneItem->department_alias;
            }

            if (isset($oneItem->course_alias)) {
                $values = explode(',', $oneItem->course_alias);

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

                $oneItem->course_alias = !empty($textValue) ? implode(', ', $textValue) : $oneItem->course_alias;
            }

            if (isset($oneItem->level_alias)) {
                $values = explode(',', $oneItem->level_alias);

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

                $oneItem->level_alias = !empty($textValue) ? implode(', ', $textValue) : $oneItem->level_alias;
            }
            $oneItem->special = JText::_('COM_FEE_STUDENTS_SPECIAL_OPTION_' . strtoupper($oneItem->special));
        }
        return $items;
    }

}
