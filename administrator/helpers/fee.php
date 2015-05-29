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

/**
 * Fee helper.
 */
class FeeHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
        		JHtmlSidebar::addEntry(
			JText::_('COM_FEE_TITLE_STUDENTS'),
			'index.php?option=com_fee&view=students',
			$vName == 'students'
		);

                JHtmlSidebar::addEntry(
            JText::_('COM_FEE_TITLE_COURSES'),
            'index.php?option=com_fee&view=courses',
            $vName == 'courses'
        );
                JHtmlSidebar::addEntry(
            JText::_('COM_FEE_TITLE_DEPARTMENTS'),
            'index.php?option=com_fee&view=departments',
            $vName == 'departments'
        );
                JHtmlSidebar::addEntry(
            JText::_('COM_FEE_TITLE_LEVELS'),
            'index.php?option=com_fee&view=levels',
            $vName == 'levels'
        );

               JHtmlSidebar::addEntry(
			JText::_('COM_FEE_TITLE_FEES'),
			'index.php?option=com_fee&view=fees',
			$vName == 'fees'
		);

        		JHtmlSidebar::addEntry(
			JText::_('COM_FEE_TITLE_SEMESTERS'),
			'index.php?option=com_fee&view=semesters',
			$vName == 'semesters'
		);
        		JHtmlSidebar::addEntry(
			JText::_('COM_FEE_TITLE_YEARS'),
			'index.php?option=com_fee&view=years',
			$vName == 'years'
		);
        		JHtmlSidebar::addEntry(
			JText::_('COM_FEE_TITLE_RECEIPTS'),
			'index.php?option=com_fee&view=receipts',
			$vName == 'receipts'
		);

    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_fee';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


}
