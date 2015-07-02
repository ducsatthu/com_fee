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

    function upload() {
        JFactory::getDocument()->setMimeEncoding('application/json');
        $mimes = array(
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        );
        sleep(2);

        $fieldName = 'myfile';

        $fileStatus = array(
            'status' => 0,
            'message' => '',
            'path' => ''
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
                        }
                    }
                endif;
            endif;
        }

        echo json_encode($fileStatus);
        JFactory::getApplication()->close();
    }

}
