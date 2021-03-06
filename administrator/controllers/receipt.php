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

/**
 * Receipt controller class.
 */
class FeeControllerReceipt extends JControllerForm
{

    function __construct() {
        $this->view_list = 'receipts';
        parent::__construct();
    }
    
    function prints(){
        $id = $this->input->getArray()['jform']['id'];
        
        $this->setRedirect('index.php?option=com_fee&view=receipt&format=print&layout=print&id='.$id,false); 
    }
}