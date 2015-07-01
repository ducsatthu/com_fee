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

jimport('joomla.application.component.view');

require_once JPATH_COMPONENT . '/helpers/convert.php';

/**
 * View to edit
 */
class FeeViewDashboard extends JViewLegacy {

    protected $totalReceipts;
    protected $totalStudent;
    protected $totalReceiptByLevel;
    protected $keyLevel;
    protected $titleLevel;
    protected $color;
    protected $item;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $session = JFactory::getSession();
        if ($session->get('filter_level_alias')) {
            @$this->item->level_alias = $session->get('filter_level_alias');
            
        }
        if ($session->get('filter_department_alias')) {
            @$this->item->department_alias = $session->get('filter_department_alias');
        }

        if ($session->get('filter_course_alias')) {
           @$this->item->course_alias = $session->get('filter_course_alias');
        }

        if ($session->get('filter_year_alias')) {
           @$this->item->year_alias = $session->get('filter_year_alias');
        }
        $this->totalReceipts = $this->get('TotalReceipts');

        #   $this->totalStudent = $this->get('TotalStudent');

        $this->totalReceiptByLevel = $this->get('TotalReceiptByLevel');

        $this->keyLevel = $this->get('KeyLevel');

        $this->titleLevel = $this->get('TitleLevel');

        $numberKey = count(json_decode($this->keyLevel));

        $this->color = FeeHelperConvert::random_color($numberKey);

        $this->form = $this->get('Form');
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        $this->addToolbar();
        FeeHelper::addSubmenu('dashboard');
        $this->sidebar = JHtmlSidebar::render();

        $this->setDocument();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/fee.php';
        JToolBarHelper::title(JText::_('COM_FEE_TITLE_DASHBOARD'), 'Bảng Tin');
        $canDo = FeeHelper::getActions();
    }

    protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_FEE_TITLE_DASHBOARD'));

        JHtml::_('jquery.framework'); // load jquery
        JHtml::_('jquery.ui'); // load jquery ui
        //add mystyle
        $document->addStyleSheet('components/com_fee/assets/css/fee.css');

        //font awesome
        $document->addStyleSheet(JUri::root() . 'administrator/components/com_fee/assets/font-awesome-4.3.0/css/font-awesome.min.css');
        //Add morris chart

        $document->addStyleSheet(JUri::root() . 'administrator/components/com_fee/assets/morris/morris.css');
        $document->addScript(JUri::root() . 'administrator/components/com_fee/assets/morris/raphael-min.js');
        $document->addScript(JUri::root() . 'administrator/components/com_fee/assets/morris/morris.min.js');

        //add Chart js
        $document->addScript(JUri::root() . 'administrator/components/com_fee/assets/chartjs/Chart.min.js');

        //add AdminLTE
        $document->addScript(JUri::root() . 'administrator/components/com_fee/assets/jQuery/jQuery-2.1.4.min.js');
        $document->addScript(JUri::root() . 'administrator/components/com_fee/assets/dist/js/app.min.js');
        $document->addScript(JUri::root() . 'administrator/components/com_fee/assets/dist/js/demo.js');
        $document->addStyleSheet(JUri::root() . 'administrator/components/com_fee/assets/dist/css/AdminLTE.min.css');
        $document->addStyleSheet(JUri::root() . 'administrator/components/com_fee/assets/dist/css/skins/_all-skins.min.css');
    }

}
