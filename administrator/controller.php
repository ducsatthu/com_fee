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

class FeeController extends JControllerLegacy {

    /**
     * Method to display a view.
     *
     * @param	boolean			$cachable	If true, the view output will be cached
     * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return	JController		This object to support chaining.
     * @since	1.5
     */
    public function display($cachable = false, $urlparams = false) {
        require_once JPATH_COMPONENT . '/helpers/fee.php';

        $view = JFactory::getApplication()->input->getCmd('view', 'students');
        JFactory::getApplication()->input->set('view', $view);

        parent::display($cachable, $urlparams);

        return $this;
    }

    public function checkStudentId() {
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;

        $student_id = $input->get('student_id');

        $model_Student = $this->getModel('student');

        $check = $model_Student->checkExitsStudent($student_id);

        echo $check;

        JFactory::getApplication()->close();
    }

    public function getStudent() {
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;

        if ($input->post->get('department') && $input->post->get('course') && $input->post->get('level')) {
            $param['department'] = $input->post->get('department');

            $param['course'] = $input->post->get('course');

            $param['level'] = $input->post->get('level');
        } else {
            $param = $input->get('data');
        }
        $model_student = $this->getModel('student');

        $listStudent = $model_student->getItemsByParam($param);
        
        //save session 
        $session = JFactory::getSession();
        
        $session->set('department',$param['department']);
        
        $session->set('course',$param['course']);
        
        $session->set('level',$param['level']);
        
        
        echo json_encode($listStudent);

        JFactory::getApplication()->close();
    }

    public function getFeeStudent() {
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;

        $student_id = $input->get('data');

        $model_fee = $this->getModel('fee');

        $listFee = $model_fee->getItemsByStudent($student_id);

        echo json_encode($listFee);

        JFactory::getApplication()->close();
    }

    public function checkFee() {
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;

        $param['student'] = $input->post->get('student');

        $param['semester'] = $input->post->get('semester');

        $param['year'] = $input->post->get('year');

        $model_fee = $this->getModel('fee');

        $listFee = $model_fee->checkFeeByParam($param);

        echo json_encode($listFee);

        JFactory::getApplication()->close();
    }

}
