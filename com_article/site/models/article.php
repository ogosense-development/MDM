<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class ArticleModelArticle extends JModelItem
{
	
	//article information
	var $_articleId = null;
	var $_itemId = null;
	//pagination
	var $_splitWord = '<hr class="system-pagebreak" />';
	var $_pageNum = null;		//number of pages in article
	var $_pageCur = null;		//current page to show
	
	function getArticle() {
		$db = JFactory::getDBO();
		$jinput = JFactory::getApplication()->input;
		
		/* Get article id */
		$this->_articleId = $jinput->getInt('id', '0', 'cmd');
		
		/* Get pagination values */
		$this->_pageCur = $jinput->getInt('page', '1', 'cmd');
		
		/* Get article */
		$query = $this->buildQuery();
		$db->setQuery( $query );
		$row = $db->loadObject();

		return $this->prepareData($row);
		}
	
	function buildQuery() {
		$query = "SELECT * FROM `#__content` WHERE id=$this->_articleId AND (publish_down>NOW() OR publish_down='0000-00-00 00:00:00')";
		return $query;
		}

	function prepareData($row) {


		if (!isset($row->introtext)) {
			echo 'invalid';
			return;
			}
		if($row->fulltext != "") {
			$pages = explode($this->_splitWord, $row->fulltext);
		} else {
			$pages = explode($this->_splitWord, $row->introtext);
		}

		if (count($pages) == 1) {
			unset ($pages);
			if($row->fulltext != "") {
				$pages = explode("{mospagebreak}", $row->fulltext);
			} else {
				$pages = explode("{mospagebreak}", $row->introtext);
			}
		}
		
		$this->_pageNum = count($pages);

		$result = new StdClass();

		$result->text = $pages[$this->_pageCur-1];

		$result->title = $row->title;
		$result->dateCreated = $this->getFriendlyDate($row->created);		
		$result->datePublished= $this->getFriendlyDate($row->publish_up);

		$db = JFactory::getDBO();

		$result->author = $row->created_by_alias;

        $query = "SELECT parent_id from `#__categories` WHERE id=" . $row->catid;
        $db->setQuery( $query );
		$result->sectionid = $db->loadObject()->parent_id;

		$result->id = $row->id;
		$result->catid = $row->catid;

		$query = "SELECT rating_count, rating_sum FROM `#__content_rating` WHERE content_id = " . $row->id;
		$db->setQuery( $query );
		$danR = $db->loadObject();

		if ( !isset($danR->rating_sum) || ! isset($danR->rating_count) ) {
			$danR->rating_sum		= 0;
			$result->rating_count	= 0;
			$result->rating			= 0;
		}
		 else {
			$result->rating_sum = $danR->rating_sum;
			$result->rating_count = $danR->rating_count != 0 ? $danR->rating_count : 1;
			$result->rating = round( $danR->rating_sum / $danR->rating_count );
		}

		$result->metakey = $row->metakey;
		$result->metadesc = $row->metadesc;
		$result->metadata = $row->metadata;
        $result->this_is_article = 'yes';
		return $result;
		}
		
	function getFriendlyDate($time) {
		$tmp = explode('-',substr($time,0,-9));
		$date = mktime(0,0,0,$tmp[1],$tmp[2],$tmp[0]);	
		return date('F d, Y',$date);
		}
		
	function getPagination() {

		if (isset($_GET['nmLoc'])) {
			$nmLoc = strtolower($_GET['nmLoc']);
			if ($nmLoc == '') { $nmLoc = 'bot'; }
		} elseif (isset($_GET['nmloc'])) {
			$nmLoc = strtolower($_GET['nmloc']);
			if ($nmLoc == '') { $nmLoc = 'bot'; }
		} else {
			$nmLoc = '';
		}
		if ($nmLoc != '') {
			$nmLocPage = "nmLoc=$nmLoc";
		} else {
			$nmLocPage = "";
		}
		$pagination = '';
		if ($this->_pageCur > 1)
			$pagination .= '<a style="margin-right:15px;color:#a75786;font-size:17px;" href="index.php?option=com_article&id='.$this->_articleId.'&page='.($this->_pageCur-1).'&Itemid='.JFactory::getApplication()->input->get('Itemid').'&'.$nmLocPage.'">&lsaquo;</a>';
			
		$pagination .= '<div class="page-label">Page</div>';
					
		for ($i=1; $i<$this->_pageNum+1; $i++) {
			$pagination .= '<a class="page-num" href="index.php?option=com_article&id='.$this->_articleId.'&page='.$i.'&Itemid='.JFactory::getApplication()->input->get('Itemid').'&'.$nmLocPage.'">';
			if ($i == $this->_pageCur)
				$pagination .= '<span class="active">'.$i.'</span>';
			else
				$pagination .= $i.' ';
			$pagination .= '</a>';
			}
		if ($this->_pageCur<$this->_pageNum)
			$pagination .= '<a style="color:#a75786;font-size:17px;margin-left:15px;" href="index.php?option=com_article&id='.$this->_articleId.'&page='.($this->_pageCur+1).'&Itemid='.JFactory::getApplication()->input->get('Itemid').'&'.$nmLocPage.'">&rsaquo;</a>';

		return $pagination;
		
		}

	function curPageURL() {
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	}
