<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


require_once( JPATH_COMPONENT.'/controller.php' );

// Require specific controller if requested
if ($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';
	die($path);
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

// Create the controller
$classname	= 'GossipController'.$controller;
$controller	= new $classname();
//$controller = JControllerLegacy::getInstance('GossipController');

// Perform the Request task
$controller->execute( JFactory::getApplication()->input->get( 'task' ) );

// Redirect if set by the controller
$controller->redirect();