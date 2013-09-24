<?php
/**
 * sh404SEF support for com_XXXXX component.
 * Author : 
 * contact :
 * 
 * {shSourceVersionTag: Version x - 2007-09-20}
 * 
 * This is a sample sh404SEF native plugin file
 *    
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG;
$sefConfig = & shRouter::shGetConfig();  
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin( $lang, $shLangName, $shLangIso, $option);
if ($dosef == false) return;
// ------------------  standard plugin initialize function - don't change ---------------------------

// ------------------  load language file - adjust as needed ----------------------------------------
//$shLangIso = shLoadPluginLanguage( 'com_XXXXX', $shLangIso, '_SEF_SAMPLE_TEXT_STRING');
// ------------------  load language file - adjust as needed ----------------------------------------

// remove common URL from GET vars list, so that they don't show up as query string in the URL
shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('lang');
if (!empty($Itemid))
  shRemoveFromGETVarsList('Itemid');
if (!empty($limit))  
shRemoveFromGETVarsList('limit');
if (isset($limitstart)) 
  shRemoveFromGETVarsList('limitstart'); // limitstart can be zero
shRemoveFromGETVarsList('view');
if (isset($id)) 
  shRemoveFromGETVarsList('id'); 
    

$task = isset($task) ? @$task : null;
$Itemid = isset($Itemid) ? @$Itemid : null;
$view = isset($view) ? @$view : null;

$db = JFactory::getDBO();

// NEW
if (isset($id)) {
	$id = (int)$id;
	$query = "SELECT "
			."`cont`.`title` AS `content_title`, "
			."`cats`.`alias` AS `cat_alias`, "
			."`sects`.`alias` AS `sect_alias`, "
			."`cont`.`catid` "
			."FROM #__content AS `cont` "
			."LEFT JOIN `#__categories` AS `cats` ON  `cats`.`id`=`cont`.`catid` "
			."LEFT JOIN `#__categories` AS `sects` ON `sects`.`id`=`cats`.`parent_id` "
			."WHERE `cont`.`id`=$id ";
	$db->setQuery($query);
	$row = $db->loadObject();

	if (isset($row) && !empty($row) ) {

		// 12 is gossip category, links are build differently there
		if ($row->catid == 12) {
			$dosef = false;
			return;
		}
		//build SEF link
		$title[] = str_replace('-','_',$row->sect_alias);
		$title[] = str_replace('-','_',$row->cat_alias);
		$title[] = str_replace('-','_',$row->content_title);
	}
}


	

global 	$shCustomTitleTag;


  
// ------------------  standard plugin finalize function - don't change ---------------------------  
if ($dosef){
   $string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString, 
      (isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null), 
      (isset($shLangName) ? @$shLangName : null));
}      
// ------------------  standard plugin finalize function - don't change ---------------------------
  
?>
