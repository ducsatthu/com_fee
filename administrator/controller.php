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

        $view = JFactory::getApplication()->input->getCmd('view', 'dashboard');
        JFactory::getApplication()->input->set('view', $view);

        $this->setSession();

        parent::display($cachable, $urlparams);

        return $this;
    }

    public function setSession() {
        $input = JFactory::getApplication()->input;

        $session = JFactory::getSession();
        $session->set('filter_level_alias', "0");
        $session->set('filter_department_alias', "0");
        $session->set('filter_course_alias', "0");
        $session->set('filter_year_alias', "0");

        try {
            if (($session->get('filter_level_alias'))) {
                if (!($input->get('filter_level_alias'))) {
                    JFactory::getApplication()->input->set('filter_level_alias', $session->get('filter_level_alias'));
                }
            }
            if (($session->get('filter_department_alias'))) {
                if (!($input->get('filter_department_alias'))) {
                    JFactory::getApplication()->input->set('filter_department_alias', $session->get('filter_department_alias'));
                }
            }

            if (($session->get('filter_course_alias'))) {
                if (!($input->get('filter_course_alias'))) {
                    JFactory::getApplication()->input->set('filter_course_alias', $session->get('filter_course_alias'));
                }
            }

            if (($session->get('filter_year_alias'))) {
                if (!($input->get('filter_year_alias'))) {
                    JFactory::getApplication()->input->set('filter_year_alias', $session->get('filter_year_alias'));
                }
            }
        } catch (Exception $exc) {
            # echo $exc->getTraceAsString();
        }
    }

    /**
     * Check Student ID exits 
     */
    public function checkStudentId() {
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;

        $student_id = $input->get('student_id');

        $model_Student = $this->getModel('student');

        $check = $model_Student->checkExitsStudent($student_id);

        echo $check;

        JFactory::getApplication()->close();
    }

    /**
     * Get Student 
     */
    public function getStudent() {
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;

        if ($input->post->get('department') && $input->post->get('course') && $input->post->get('level')) {
            $param['department'] = $input->post->get('department');

            $param['course'] = $input->post->get('course');

            $param['level'] = $input->post->get('level');

//save session 
            $session = JFactory::getSession();

            $session->set('department', $param['department']);

            $session->set('course', $param['course']);

            $session->set('level', $param['level']);
        } else {
            $param = $input->get('data');
        }
        $model_student = $this->getModel('student');

        $listStudent = $model_student->getItemsByParam($param);


        echo json_encode($listStudent);

        JFactory::getApplication()->close();
    }

    /**
     * Get Fee Student
     */
    public function getFeeStudent() {
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;

        $student_id = $input->get('data');

        $model_fee = $this->getModel('fee');

        $listFee = $model_fee->getItemsByStudent($student_id);

        echo json_encode($listFee);

        JFactory::getApplication()->close();
    }

    /**
     * Check student fee
     */
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

    /**
     * add all fee for class
     */
    function addsDo() {
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;

        $param['level_alias'] = $input->post->get('level_alias');

        $param['department_alias'] = $input->post->get('department_alias');

        $param['course_alias'] = $input->post->get('course_alias');

        $param['semester_alias'] = $input->post->get('semester_alias');

        $param['year_alias'] = $input->post->get('year_alias');

        $param['payable'] = $input->post->get('payable');

        $model_fee = $this->getModel('fee');

        $save = $model_fee->addsFee($param);

        echo json_encode($save);

        JFactory::getApplication()->close();
    }

    /**
     * Get next Receipt
     */
    public function getReceipt() {
        JFactory::getDocument()->setMimeEncoding('application/json');

        $input = JFactory::getApplication()->input;

        $param['student_alias'] = $input->post->get('student_alias');

        $param['formality'] = $input->post->get('formality');

        $param['year_alias'] = $input->post->get('year_alias');

        $model_receipt = $this->getModel('receipt');

        $receipt = $model_receipt->getReceiptNext($param);

        require_once JPATH_COMPONENT . '/helpers/convert.php';

        $receipt['title'] = FeeHelperConvert::convertVNese($receipt['title']);

        $receipt['title'] = strtoupper($receipt['title'][0]);
        if (strtoupper($receipt['title']) === 'C' && $param['formality']) {
            $receipt['title'] = 'NH';
        }

        echo json_encode($receipt);

        JFactory::getApplication()->close();
    }

}
