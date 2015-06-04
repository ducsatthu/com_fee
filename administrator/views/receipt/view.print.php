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

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class FeeViewReceipt extends JViewLegacy {

    protected $item;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->item = $this->get('ItemPrint');
        parent::display($tpl);
    }


}
