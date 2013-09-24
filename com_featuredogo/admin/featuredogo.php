<?php
/**
 * @version     1.0.0
 * @package     com_featuredogo
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Alex <abjelosevic@ogosense.com> - http://www.ogosense.com
 */


// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_featuredogo')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('Featuredogo');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
