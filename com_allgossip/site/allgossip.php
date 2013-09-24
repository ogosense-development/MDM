<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JPATH_COMPONENT.'/controller.php' );

// Require specific controller if requested
if ($controller = JFactory::getApplication()->input->get('controller')) {
	$path = JPATH_COMPONENT . '/controllers/' . $controller . '.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

// Create the controller
$classname	= 'AllgossipController'.$controller;
$controller	= new $classname();

// Perform the Request task
$controller->execute( JFactory::getApplication()->input->get( 'task' ) );

// Redirect if set by the controller
$controller->redirect();