<?php

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

global 	$shCustomTitleTag;

$menu = JMenu::getInstance('site');
		$item = $menu->getActive();

$shCustomTitleTag = "$item->title";

?>
