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

    

// start by inserting the menu element title (just an idea, this is not required at all)
$task = isset($task) ? @$task : null;
$Itemid = isset($Itemid) ? @$Itemid : null;
$view = isset($view) ? @$view : null;

if($Itemid) {
	$query = "SELECT title FROM #__menu WHERE id = '" . $Itemid . "'";
	$database->setQuery( $query );
	$d = $database->loadObject();
	$arr = explode("&",$d->name);
	
	$d = $arr[0];
	for($i = 1; $i < count($arr); $i++)
		$d .= "_and_" . $arr[$i];
	
	$title[] = $d;
}
$title[]="";

$title = str_replace("-", "_", $title);
  
// ------------------  standard plugin finalize function - don't change ---------------------------  
if ($dosef){
   $string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString, 
      (isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null), 
      (isset($shLangName) ? @$shLangName : null));
}      
// ------------------  standard plugin finalize function - don't change ---------------------------
  
?>
