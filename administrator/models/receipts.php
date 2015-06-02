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
        $query->select('#__fee_student_1887542.student_id AS students_alias_1887542');
        $query->join('LEFT', '#__fee_student AS #__fee_student_1887542 ON #__fee_student_1887542.alias = a.student_alias');
        // Join over the foreign key 'semester_alias'
        $query->select('#__fee_semester_1887545.title AS semesters_title_1887545');
        $query->join('LEFT', '#__fee_semester AS #__fee_semester_1887545 ON #__fee_semester_1887545.alias = a.semester_alias');
        // Join over the foreign key 'year_alias'
        $query->select('#__fee_year_1887547.start AS years_title_1887547');
        $query->join('LEFT', '#__fee_year AS #__fee_year_1887547 ON #__fee_year_1887547.alias = a.year_alias');



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
                $query->where('( a.title LIKE ' . $search . '  OR  #__fee_student_1887542.student_id  LIKE ' . $search . '  OR  #__fee_semester_1887545.title LIKE ' . $search . '  OR  #__fee_year_1887547.start LIKE ' . $search . '  OR  a.date LIKE ' . $search . '  OR  a.paid LIKE ' . $search . ' )');
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


        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
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
                            ->select($db->quoteName('student_id'))
                            ->from('`#__fee_student`')
                            ->where($db->quoteName('alias') . ' = ' . $db->quote($db->escape($value)));
                    $db->setQuery($query);
                    $results = $db->loadObject();
                    if ($results) {
                        $textValue[] = $results->student_id;
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
                            ->select($db->quoteName("CONCAT(CAST(`start` AS CHAR), ' - ',CAST(`end` AS CHAR)) AS start"))
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

}
