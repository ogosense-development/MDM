<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class AllgossipModelAllgossip extends JModelItem
{
	var $_articles = null;
	var $_catId = null;
	var $_itemId = null;

	
	function setSearch($search) {
		$this->_search = $search;
		}
	
	function getSearch() {
		return $this->_search;
		}
	
	
	function getArticleList() {
		$db = JFactory::getDBO();
        $jinput = JFactory::getApplication()->input;
		
		/* Get category id */
		
		$this->_catId = 12;
		$this->_itemId = $jinput->getInt('Itemid','0','cmd');

		/* Get articles for the dates */
		$res = array();

		// 2011-05-16 :: IVAN :: restructured code to provide arbitrary number
		// of dates in query
		$query = "SELECT id,title,introtext,images,DATE(created) AS date "
				."FROM #__content "
				."WHERE "
				."catid={$this->_catId} AND ".
                'state=1 ORDER BY created DESC LIMIT 16';

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach($rows as $row) {
			$res[] = $this->prepareData($row);
		}

		return $res;
	}

	function getItemid() {
		$db = JFactory::getDBO();
		$query = "SELECT * FROM `#__menu` WHERE menutype='TopNav' AND title='Gossip Article'";
		$db->setQuery( $query);
		return $db->loadResult();
		}
		
	/* Extracts usefull information from table rows */
	function prepareData($row) {
		$res = array();
		
		$res['id'] = $row->id;
		$res['image'] = $this->getImgPath($row->images, $row->introtext);
		$res['title'] = $row->title;
		//$res['introtext'] = $row->introtext;
		$tmp = explode('-',$row->date);
		$date = mktime(0,0,0,$tmp[1],$tmp[2],$tmp[0]);	
		$date =	date('M d, Y',$date);
		$res['date'] = $date;

		return $res;
	}
		
	function getImgPath($field, $intro) {
		$tmp = explode("|",$field);
		if (empty($tmp[0])) {
			//matching <img> tag
			preg_match('/src=[\'"]?([^\'" >]+)[\'" >]/', $intro, $matches);
			return (substr($matches[0],5,-1));	
			}
		else
			return ("images/stories/".$tmp[0]);
	}
}
