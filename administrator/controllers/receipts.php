<?php

/**
 * @version     1.0.0
 * @package     com_fee
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Tran Xuan Duc <ductranxuan.29710@gmail.com> - http://facebook.com/ducsatthuttd
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Receipts list controller class.
 */
class FeeControllerReceipts extends JControllerAdmin {

    /**
     * Proxy for getModel.
     * @since	1.6
     */
    public function getModel($name = 'receipt', $prefix = 'FeeModel', $config = array()) {
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

    public function prints() {
        $config = $this->input->getArray();
        if (!$config['filter_year_alias'] || !$config['filter_level_alias']) {
            echo "<script>alert('" . JText::_('COM_FEE_ERROR_REQUIRE_SELECTED_YEAR_LEVEL') . "');</script>";
            echo "<script>window.location = 'index.php?option=com_fee&view=receipts'</script>";
            return 0;
        }

        $this->setRedirect('index.php?option=com_fee&view=receipts&format=prints&layout=prints', true);
    }
    
    public function printsPerson() {
        $arrayInput = $this->input->getArray();
        $filter_year_alias = $arrayInput['filter_year_alias'];
        $cid = json_encode($arrayInput['cid']);
        if (!$filter_year_alias || !$cid) {
            echo "<script>alert('" . JText::_('COM_FEE_RECEIPT_PERSON_ERROR_SELECTED') . "');</script>";
            echo "<script>window.location = 'index.php?option=com_fee&view=receipts'</script>";
            return 0;
        }
        $url = 'index.php?option=com_fee&view=receipts&format=prints&layout=print_person&cid='.$cid;
        $this->setRedirect($url);
    }
}
