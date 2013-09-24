<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

//Alex :: check this!!!
function ArticleBuildRoute($query) {
	$segments = array();
	if (isset($query['Itemid'])) {
		$segments[] = $query['Itemid'];
		unset($query['Itemid']);
		}
	if(isset($query['id'])) {
		$segments[] = $query['id'];
		unset($query['id']);
		}
	return $segments;
}

function ArticleParseRoute($segments) {
	$vars = array();
	$vars['Itemid'] = $segments[0];
	$vars['id'] = $segments[1];
	return $vars;
}


?>