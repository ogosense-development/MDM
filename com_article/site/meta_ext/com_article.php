<?php

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

global 	$shCustomTitleTag;

$db = JFactory::getDBO();

$jinput = JFactory::getApplication()->input;
$articleID = $jinput->getInt('id', '0', 'cmd');

//get article alias and catid
$query = "SELECT title,catid FROM #__content WHERE id = ".$articleID;
$db->setQuery($query);
$row = $db->loadObject();
$catID = $row->catid;
$articleTitle = $row->title;

//get category alias
$query = "SELECT title FROM #__categories WHERE id = $catID";
$db->setQuery($query);
$catTitle = $db->loadResult();

//get section alias
$query = "SELECT title FROM #__categories WHERE id = (SELECT parent_id FROM #__categories WHERE id=$catID)";
$db->setQuery($query);
$sectTitle = $db->loadResult();

$shCustomTitleTag = "$articleTitle | $catTitle | $sectTitle";

?>
