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
 * Fee controller class.
 */
class FeeControllerFee extends JControllerForm
{

    function __construct() {
        $this->view_list = 'fees';
        parent::__construct();
    }
    
    function adds(){
        $url = 'index.php?option=com_fee&view=fee&layout=adds';
        $this->setRedirect($url);
    }
}