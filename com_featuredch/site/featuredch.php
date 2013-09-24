<?php


// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once (JPATH_COMPONENT.'/controller.php');

// Require specific controller if requested
if($controller = JFactory::getApplication()->input->get('controller')) {
	require_once (JPATH_COMPONENT.'/controllers/'.$controller.'.php');
}

// Create the controller
$classname	= 'FeaturedchController'.$controller;
$controller = new $classname();

// Perform the Request task 
$controller->execute( JFactory::getApplication()->input->get('task') );

// Redirect if set by the controller
$controller->redirect();

?>
