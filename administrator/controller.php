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
    
    public function getStudent(){
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;
        
        $param['department'] = $input->post->get('department');
        
        $param['course'] = $input->post->get('course');
        
        $param['level'] = $input->post->get('level');
        
        $model_student = $this->getModel('student');
        
        $listStudent = $model_student->getItemsByParam($param);
        
        echo json_encode($listStudent);
        
        JFactory::getApplication()->close();
    }

}
