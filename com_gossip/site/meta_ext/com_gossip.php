<?php

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

global 	$shCustomTitleTag;

$db = JFactory::getDBO();

$jinput = JFactory::getApplication()->input;
$articleID = $jinput->getInt('id', '0', 'cmd');

//get gossip alias and catid
$query = "SELECT title FROM #__content WHERE id = ".$articleID;
$db->setQuery($query);
$articleTitle = $db->loadResult();

$shCustomTitleTag = "$articleTitle | The Gossip Wire | Gossip";

?>
