<?php

/**
 * @version     1.0.0
 * @package     com_fee
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Linh <mr.lynk92@gmail.com> - http://
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * Student controller class.
 */
class FeeControllerStudent extends JControllerForm {

    function __construct() {
        $this->view_list = 'students';
        parent::__construct();
    }
    
    public function adds(){
        $url = 'index.php?option=com_fee&view=student&layout=upload';
        $this->setRedirect($url);
    }
    
    function upload() {
        JFactory::getDocument()->setMimeEncoding('application/json');
        $mimes = array(
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        );

        $fieldName = 'myfile';

        $fileStatus = array(
            'status' => 0,
            'message' => '',
            'path' => '',
            'object' => ''
        );
        if (isset($_FILES[$fieldName])) {
            $fileError = $_FILES[$fieldName]['error'];
            if ($fileError > 0) :
                switch ($fileError) {
                    case 1:
                        $fileStatus['message'] = JText::_('Dung lượng bị hạn chế bởi máy chủ');
                        return;

                    case 2:
                        $fileStatus['message'] = JText::_('Tệp lớn hơn dung lương cho phép');
                        return;

                    case 3:
                        $fileStatus['message'] = JText::_('Lỗi không thể tải lên');
                        return;

                    case 4:
                        $fileStatus['message'] = JText::_('Không thể tìm được tệp');
                        return;
                }
            else:
                $fileSize = $_FILES[$fieldName]['size'];

                if ($fileSize > 4000000) :
                    $fileStatus['message'] = JText::_('Dung lượng không vượt quá 4MB');
                else:
                    $fileName = $_FILES[$fieldName]['name'];
                    $fileType = $_FILES[$fieldName]['type'];

                    if (!in_array($fileType, $mimes)) { //Kiểm tra định dạng file
                        $fileStatus['message'] = JText::_('Không cho phép định dạng này');
                        ;
                    } else { //Không có lỗi nào
                        $uploadPath = JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $fileName;
                        $fileTemp = $_FILES[$fieldName]['tmp_name'];
                        if (!JFile::upload($fileTemp, $uploadPath)) {
                            $fileStatus['message'] = "Không thể tải lên : $fileName";
                        } else {
                            $fileStatus['status'] = 1;
                            $fileStatus['message'] = "Bạn đã upload $fileName thành công";
                            $fileStatus['path'] = $uploadPath;
                            //doc file va tra ve o day:
                            require_once JPATH_COMPONENT . '/helpers/excel.php'; // goi cai file vua viet vao
                            $fileStatus['object'] = FeeHelperExcel::readExcel($uploadPath);
                        }
                    }
                endif;
            endif;
        }

        echo json_encode($fileStatus);
        JFactory::getApplication()->close();
    }

    function insertData() {
        /* Gọi model để xử lý */
        $modelStudent = $this->getModel('student');
        $modelDepartment = $this->getModel('department');
        $modelCourse = $this->getModel('course');

        /* Nhận data */
        JFactory::getDocument()->setMimeEncoding('application/json');
        $input = JFactory::getApplication()->input; //lấy ra trị trong cái input
        $data = $input->post->getString('insertData'); //lấy dữ liệu từ Ajax

        /* Xử lý insert */
        $parram = json_decode($data);
        $dataError = array();
        for ($i = 0; $i < count($parram); $i++) {
            $student = $modelStudent->checkStudent($parram[$i]->idstudent);
            if ($student) {
                //Lưu lại dữ liệu lỗi
                $dataError[] = $parram[$i];
            } else {
                $department = $modelDepartment->insertDepartment($parram[$i]->department);
                $course = $modelCourse->insertCourse($parram[$i]->course);
 
                //Thêm sinh viên mới
                $date = DateTime::createFromFormat('d/m/Y', $parram[$i]->birthday);
                $birthday = $date->format('Y-m-d');
                $modelStudent->insertStudent($parram[$i]->idstudent, $parram[$i]->name,$birthday, $department, $course);
                
            }
        }

       echo json_encode($dataError);

        JFactory::getApplication()->close();
    }

}
