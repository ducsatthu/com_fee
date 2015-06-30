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
 * Fees list controller class.
 */
class FeeControllerFees extends JControllerAdmin {

    /**
     * Proxy for getModel.
     * @since	1.6
     */
    public function getModel($name = 'fee', $prefix = 'FeeModel', $config = array()) {
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

    public function printsOwed() {
        $arrayInput = $this->input->getArray();
        $filter_year_alias = $arrayInput['filter_year_alias'];
        $filter_department_alias = $arrayInput['filter_department_alias'];
        $filter_course_alias = $arrayInput['filter_course_alias'];
        $filter_level_alias = $arrayInput['filter_level_alias'];

        if (!$filter_course_alias || !$filter_department_alias || !$filter_level_alias || !$filter_year_alias) {
            echo "<script>alert('" . JText::_('COM_FEE_ERROR_REQUIRE_SELECTED') . "');</script>";
            echo "<script>window.location = 'index.php?option=com_fee&view=fees'</script>";
            return 0;
        }
        $url = 'index.php?option=com_fee&view=fees&format=prints&layout=print_owed';
        echo "<script>window.open('" . $url . "','_self');</script>";
    }

    public function printFee() {
        $arrayInput = $this->input->getArray();
        $filter_year_alias = $arrayInput['filter_year_alias'];
        $filter_department_alias = $arrayInput['filter_department_alias'];
        $filter_course_alias = $arrayInput['filter_course_alias'];
        $filter_level_alias = $arrayInput['filter_level_alias'];

        if (!$filter_course_alias || !$filter_department_alias || !$filter_level_alias || !$filter_year_alias) {
            echo "<script>alert('" . JText::_('COM_FEE_ERROR_REQUIRE_SELECTED') . "');</script>";
            echo "<script>window.location = 'index.php?option=com_fee&view=fees'</script>";
            return 0;
        }
        $url = 'index.php?option=com_fee&view=fees&format=prints&layout=print_fee';
        echo "<script>window.open('" . $url . "','_self');</script>";
    }

    public function printsOwedlevel() {
        $arrayInput = $this->input->getArray();
        $filter_year_alias = $arrayInput['filter_year_alias'];
        $filter_level_alias = $arrayInput['filter_level_alias'];

        if (!$filter_level_alias || !$filter_year_alias) {
            echo "<script>alert('" . JText::_('COM_FEE_ERROR_REQUIRE_SELECTED_YEAR_LEVEL') . "');</script>";
            echo "<script>window.location = 'index.php?option=com_fee&view=fees'</script>";
            return 0;
        }
        $url = 'index.php?option=com_fee&view=fees&format=prints&layout=print_owed_level';
        echo "<script>window.open('" . $url . "','_self');</script>";
    }

    public function printsOwedCourse() {
        $arrayInput = $this->input->getArray();
        $filter_year_alias = $arrayInput['filter_year_alias'];
        $filter_course_alias = $arrayInput['filter_course_alias'];
        $filter_level_alias = $arrayInput['filter_level_alias'];

        if (!$filter_year_alias || !$filter_level_alias || !$filter_course_alias) {
            echo "<script>alert('" . JText::_('COM_FEE_ERROR_REQUIRE_SELECTED_TOTAL_FEE') . "');</script>";
            echo "<script>window.location = 'index.php?option=com_fee&view=fees'</script>";
            return 0;
        }
        $url = 'index.php?option=com_fee&view=fees&format=prints&layout=print_owed_course';
        echo "<script>window.open('" . $url . "','_self');</script>";
    }

    public function printTotalFee() {
        $arrayInput = $this->input->getArray();
        $filter_year_alias = $arrayInput['filter_year_alias'];
        $filter_course_alias = $arrayInput['filter_course_alias'];
        $filter_level_alias = $arrayInput['filter_level_alias'];

        if (!$filter_year_alias || !$filter_level_alias || !$filter_course_alias) {
            echo "<script>alert('" . JText::_('COM_FEE_ERROR_REQUIRE_SELECTED_TOTAL_FEE') . "')</script>";
            echo "<script>window.location = 'index.php?option=com_fee&view=fees'</script>";
            return 0;
        }

        $url = 'index.php?option=com_fee&view=fees&format=prints&layout=print_total';
        echo "<script>window.open('" . $url . "','_self');</script>";
    }

    public function printTotalFeeLevel() {
        $arrayInput = $this->input->getArray();
        $filter_year_alias = $arrayInput['filter_year_alias'];
        $filter_level_alias = $arrayInput['filter_level_alias'];

        if (!$filter_year_alias || !$filter_level_alias) {
            echo "<script>alert('" . JText::_('COM_FEE_ERROR_REQUIRE_SELECTED_YEAR_LEVEL') . "')</script>";
            echo "<script>window.location = 'index.php?option=com_fee&view=fees'</script>";
            return 0;
        }

        $url = 'index.php?option=com_fee&view=fees&format=prints&layout=print_total_level';
        echo "<script>window.open('" . $url . "','_self');</script>";
    }

    public function printRate() {
        $arrayInput = $this->input->getArray();
        $filter_year_alias = $arrayInput['filter_year_alias'];
        $filter_department_alias = $arrayInput['filter_department_alias'];
        $filter_course_alias = $arrayInput['filter_course_alias'];
        $filter_level_alias = $arrayInput['filter_level_alias'];

        if (!$filter_year_alias || !$filter_department_alias || !$filter_level_alias || !$filter_course_alias) {
            echo "<script>alert('" . JText::_('COM_FEE_ERROR_REQUIRE_SELECTED') . "')</script>";
            echo "<script>window.location = 'index.php?option=com_fee&view=fees'</script>";
            return 0;
        }

        $url = 'index.php?option=com_fee&view=fees&format=prints&layout=print_rate';
        echo "<script>window.open('" . $url . "','_self');</script>";
    }

}
