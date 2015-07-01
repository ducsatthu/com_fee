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

jimport('joomla.application.component.controlleradmin');

/**
 * Courses list controller class.
 */
class FeeControllerDashboard extends JControllerAdmin {

    /**
     * Proxy for getModel.
     * @since	1.6
     */
    public function getModel($name = 'dashboard', $prefix = 'FeeModel', $config = array()) {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

    /**
     * Method to save the submitted ordering values for records via AJAX.
     *
     * @return  void
     *
     * @since   3.0
     */
    public function saveOrderAjax() {
        // Get the input
        $input = JFactory::getApplication()->input;
        $pks = $input->post->get('cid', array(), 'array');
        $order = $input->post->get('order', array(), 'array');

        // Sanitize the input
        JArrayHelper::toInteger($pks);
        JArrayHelper::toInteger($order);

        // Get the model
        $model = $this->getModel();

        // Save the ordering
        $return = $model->saveorder($pks, $order);

        if ($return) {
            echo "1";
        }

        // Close the application
        JFactory::getApplication()->close();
    }

    public function saveSession() {
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;

        $param['level_alias'] = $input->post->get('level_alias');

        $param['department_alias'] = $input->post->get('department_alias');

        $param['course_alias'] = $input->post->get('course_alias');

        $param['year_alias'] = $input->post->get('year_alias');

        //save session 
        $session = JFactory::getSession();

        if ($param['level_alias']) {
            $session->set('filter_level_alias', $param['level_alias']);
        }else{
            $session->set('filter_level_alias', "0");
        }
        if ($param['department_alias']) {
            $session->set('filter_department_alias', $param['department_alias']);
        }else{
            $session->set('filter_department_alias', "0");
        }
        if ($param['course_alias']) {
             $session->set('filter_course_alias', $param['course_alias']);
        }else{
            $session->set('filter_course_alias', "0");
        }
        if ($param['year_alias']) {
            $session->set('filter_year_alias', $param['year_alias']);
        }else{
            $session->set('filter_year_alias', "0");
        }
        
       

        JFactory::getApplication()->close();
    }

}
