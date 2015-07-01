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

jimport('joomla.application.component.modeladmin');


require_once JPATH_COMPONENT . '/helpers/convert.php';

/**
 * Fee model.
 */
class FeeModelDashboard extends JModelAdmin {

    public function getForm($data = array(), $loadData = true) {
        // Initialise variables.
        $app = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_fee.dashboard', 'dashboard', array('control' => 'jform', 'load_data' => $loadData));


        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getTotalReceipts() {
        $db = JFactory::getDbo();

        $query = $db->getQuery(TRUE);

        $query
                ->select('sum(`paid`) as total')
                ->from('`#__fee_receipt`')
                ->select("CONCAT(CAST(`#__fee_year`.`start` AS CHAR), ' - ',CAST(`#__fee_year`.`end` AS CHAR)) AS start")
                ->join('LEFT', '`#__fee_year` ON `#__fee_year`.`alias` = `#__fee_receipt`.`year_alias`')
                ->order('`#__fee_year`.`start` DESC')
                ->group('start')
        ;
        $db->setQuery($query, 0, 5);

        return json_encode($db->loadObjectList());
    }

    public function getTotalReceiptByLevel() {
        $db = JFactory::getDbo();

        //get level
        $query = $db->getQuery(TRUE);

        $query
                ->select(array(
                    '`title`', '`alias`'
                ))
                ->from('`#__fee_level`')
                ->order('`title` DESC')
        ;
        $db->setQuery($query);

        $levels = $db->loadObjectList();

        //get year

        $query = $db->getQuery(TRUE);

        $query
                ->select(array(
                    '`alias`', "CONCAT(CAST(`#__fee_year`.`start` AS CHAR), ' - ',CAST(`#__fee_year`.`end` AS CHAR)) AS start"
                ))
                ->from('`#__fee_year`')
                ->order('`start` DESC')
        ;
        $db->setQuery($query);

        $items = $db->loadObjectList();


        foreach ($items as $item) {
            foreach ($levels as $level) {
                $query = $db->getQuery(TRUE);

                $query
                        ->select('sum(`paid`) as total')
                        ->from('`#__fee_receipt`')
                        ->select("`#__fee_level`.`title`")
                        ->join('LEFT', '`#__fee_level` ON `#__fee_level`.`alias` = `#__fee_receipt`.`level_alias`')
                        ->where('`year_alias` = ' . $db->quote($item->alias, TRUE))
                        ->where('`#__fee_level`.`alias` = ' . $db->quote($level->alias, TRUE))
                        ->group('year_alias')
                        ->order('`#__fee_level`.`title`')
                ;

                $db->setQuery($query);

                $results = $db->loadObject();

                if ($results) {
                    $item->{FeeHelperConvert::convertVNese($results->title)} = $results->total;
                } else {
                    $item->{FeeHelperConvert::convertVNese($level->title)} = 0;
                }
            }


            unset($item->alias);
        }

        return json_encode($items);
    }

    public function getKeylevel() {
        $db = JFactory::getDbo();

        //get level
        $query = $db->getQuery(TRUE);

        $query
                ->select('`title`')
                ->from('`#__fee_level`')
                ->order('`title` DESC')
        ;
        $db->setQuery($query);

        $levels = $db->loadAssocList();

        if ($levels) {
            foreach ($levels as $key => $level) {
                $levels[$key] = FeeHelperConvert::convertVNese($level['title']);
            }
            return json_encode($levels);
        }

        return 0;
    }

    public function getTitleLevel() {
        $db = JFactory::getDbo();

        //get level
        $query = $db->getQuery(TRUE);

        $query
                ->select('`title`')
                ->from('`#__fee_level`')
                ->order('`title` DESC')
        ;
        $db->setQuery($query);

        $levels = $db->loadAssocList();

        if ($levels) {
            foreach ($levels as $key => $level) {
                $levels[$key] = $level['title'];
            }
            return json_encode($levels);
        }

        return 0;
    }

}
