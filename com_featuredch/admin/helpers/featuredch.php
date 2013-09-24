<?php
/**
 * @version     1.0.0
 * @package     com_featuredch
 * @copyright   OGOSense
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Alex <aaleksic@ogosense.com> - http://
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Featuredch helper.
 */
class FeaturedchHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_FEATUREDCH_TITLE_FEATUREDCHS'),
			'index.php?option=com_featuredch&view=featuredchs',
			$vName == 'featuredchs'
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

		$assetName = 'com_featuredch';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
