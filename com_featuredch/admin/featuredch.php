<?php
/**
 * @version     1.0.0
 * @package     com_featuredch
 * @copyright   OGOSense
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Alex <aaleksic@ogosense.com> - http://
 */


// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_featuredch')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('Featuredch');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
