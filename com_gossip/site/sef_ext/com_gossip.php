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

$db = JFactory::getDBO();



//get gossip alias and catid
$query = "SELECT title FROM #__content WHERE id = $id";
$db->setQuery($query);
$articleAlias = $db->loadResult();

//get category alias
/*
$query = "SELECT alias FROM #__categories WHERE id = $catID";
$db->setQuery($query);
$row = $db->loadObject();
$catAlias = $row->alias;
*/

//build SEF link
//$title[] = 'gossip';
//$title[] = 'the_gossip_wire';
$title[] = 'Gossip';
$title[] = 'The gossip wire';
if (strlen($articleAlias)>0)
	$title[] = str_replace('-','_',$articleAlias);
  
// ------------------  standard plugin finalize function - don't change ---------------------------  
if ($dosef){
   $string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString, 
      (isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null), 
      (isset($shLangName) ? @$shLangName : null));
}      
// ------------------  standard plugin finalize function - don't change ---------------------------
  
?>
