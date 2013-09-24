<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class GossipModelGossip extends JModelItem
{
	var $_id = null;	//id of current article
	var $_cid = null;	//category id of current article
	
	function getGossip() {
		/* Get category id */
		$menu = JMenu::getInstance('site');
		$item = $menu->getActive();
		$params = $menu->getParams($item->id);
		
		$this->_cid = $params->get('catid', '-1'); 
		
		/* Get gossip id */
		$jinput = JFactory::getApplication()->input;
		$this->_id = $jinput->getInt('id', '-1', 'cmd');
		//$this->_id = JRequest::getInt('id',-1);
		
		if ($this->_id == -1)
			$this->_id = $this->getFirstGossipID();
		/* Get gossip from db */
		$db = JFactory::getDBO();
		$date = JFactory::getDate();
		$now = $date->toSQL();
		
		$query = "SELECT * FROM #__content WHERE catid=$this->_cid AND id=$this->_id  AND (publish_up='0000-00-00 00:00:00' OR publish_up<".$db->Quote($now).") AND (publish_down='0000-00-00 00:00:00' OR publish_down>".$db->Quote($now).")";
		$db->setQuery($query);
		$res = $db->loadObject();

		$nextGossip = $this->getNextGossip($res->created);
		if (isset($nextGossip->id)) {
			$res->nextID = $nextGossip->id;
			$res->nextTitle = $this->getIntro($nextGossip->title);
			}
		$prevGossip = $this->getPrevGossip($res->created);
		if (isset($prevGossip->id)) {
			$res->prevID = $prevGossip->id;
			$res->prevTitle = $this->getIntro($prevGossip->title);
			}
		/* Get Image */
		$tmp = explode("|",$res->images);
		if (empty($tmp[0])) {
			$query = "SELECT introtext FROM #__content WHERE id=".$res->id;
			$db->setQuery($query);
			//matching <img> tag
			preg_match('/src=[\'"]?([^\'" >]+)[\'" >]/', $db->loadResult(), $matches);
			$res->image = substr($matches[0],5,-1);
			}
		else
			$res->image = "images/stories/".$tmp[0];
		return $res;
		}
		
	function getIntro($text, $len = 25) {
		$text = strip_tags($text);
		$tmp = explode(' ',$text);
		$res = '';
		$i=0;
		while (strlen($res)<$len && $i<count($tmp))
			$res.=$tmp[$i++].' ';
		return $res.'...';
		}
		
	function getCategoryID() {
		$db = JFactory::getDBO();
		$query = "";
		$db->setQuery($query);
		return $db->loadResult();
		}
		
	function getFirstGossipID() {
		$db = JFactory::getDBO();
		$date = JFactory::getDate();
		$now = $date->toMySQL();
		$query = "SELECT id FROM `#__content` WHERE catid=$this->_cid AND state=1 AND (publish_up='0000-00-00 00:00:00' OR publish_up<".$db->Quote($now).") AND (publish_down='0000-00-00 00:00:00' OR publish_down>".$db->Quote($now).") ORDER BY created DESC LIMIT 1";
		$db->setQuery($query);
		return $db->loadResult();
		}

	function getNextGossip($time) {
		$db = JFactory::getDBO();
		$query = "SELECT id,title FROM #__content WHERE catid=$this->_cid AND created>'$time' AND state=1 ORDER BY created ASC LIMIT 1";
		$db->setQuery($query);
		return $db->loadObject();
		}
	
	function getPrevGossip($time) {
		$db = JFactory::getDBO();
		$query = "SELECT id,title FROM #__content WHERE catid=$this->_cid AND created<'$time' AND state=1 ORDER BY created DESC LIMIT 1";
		$db->setQuery($query);
		return $db->loadObject();
		}
	}
