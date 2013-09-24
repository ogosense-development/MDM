<?php
/**
 * @version     1.0.0
 * @package     com_featuredogo
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Alex <abjelosevic@ogosense.com> - http://www.ogosense.com
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Featuredogo helper.
 */
class FeaturedogoHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_FEATUREDOGO_TITLE_FEATUREDOGOS'),
			'index.php?option=com_featuredogo&view=featuredogos',
			$vName == 'featuredogos'
		);

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_featuredogo';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
