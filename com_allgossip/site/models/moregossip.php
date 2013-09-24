<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class AllgossipModelmoregossip extends JModelItem
{
    var $_articles = null;
    var $_catId = null;
    var $_itemId = null;
    //pagination
    var $total = null;
    var $limitstart = null;
    var $limit = null;
    var $_pageNav = null;
    //search
    var $_search = null;

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

        /* Get pagination values */
        if (! empty($this->_search)) {
            $this->limit = 10;
        }
        else {
            $this->limit = 2;
        }

        $this->limitstart = $jinput->getInt('limitstart', '0', 'cmd');

        /* Get total number of dates that match search criteria */

        $query = $this->getTotal();
        $db->setQuery( $query );
        $this->total = $db->loadResult();


        /* Get list of dates */

        $query = $this->buildQuery()." LIMIT {$this->limitstart},{$this->limit}";
        $db->setQuery($query);
        $dateList = $db->loadColumn();


        /* Get articles for the dates */
        $res = array();

        $query = "SELECT id,title,introtext,images,DATE(created) AS date "
            ."FROM #__content "
            ."WHERE "
            ."catid={$this->_catId} AND "
            ."DATE(created) IN ('".implode("','",$dateList)."') "
            .$this->appendSearch().'ORDER BY created DESC';

        $db->setQuery($query);
        $rows = $db->loadObjectList();

        //die('<pre>'.print_r($query, true).'</pre>');

        foreach($rows as $row) {
            $res[$row->date][] = $this->prepareData($row);
        }

        return $res;
    }

    /* Returns search query */
    function appendSearch() {
        $db = JFactory::getDBO();
        $items = explode(" ",$this->_search);
        $res = '';
        foreach ($items as $item)
            if (!empty($item))
                $res .= " AND introtext LIKE '%".$db->escape($item,true)."%' ";
        $res .= " AND state=1 ";
        return $res;
    }

    function buildQuery() {
        $query = "SELECT DATE(created) FROM `#__content` WHERE catid=$this->_catId ";
        $query .= $this->appendSearch();
        $query .= "GROUP BY DATE(created) ORDER BY DATE(created) DESC";
        return $query;
    }

    function getTotal() {
        $query = "SELECT COUNT(*) FROM (".$this->buildQuery().") AS tbl";
        return $query;
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
        $res['text'] = $this->getIntro($row->introtext);
        $tmp = explode('-',$row->date);
        $date = mktime(0,0,0,$tmp[1],$tmp[2],$tmp[0]);
        $date =	date('M d, Y',$date);
        $res['date'] = $date;

        return $res;
    }

    function getImgPath($field, $intro) {
        $tmp = explode("|",$field);
        if (empty($tmp[0])) {
            preg_match('/src=[\'"]?([^\'" >]+)[\'" >]/', $intro, $matches);
            return (substr($matches[0],5,-1));
        }
        else
            return ("images/stories/".$tmp[0]);
    }

    function getCatName() {
        $db = JFactory::getDBO();
        $query = "SELECT title FROM `#__categories` WHERE id = '$this->_catId'";
        $db->setQuery( $query);
        return $db->loadResult();
    }

    /* Returns text ~ $len characters long */
    function getIntro($text, $len = 80) {
        $text = str_replace("Photo by www.pacificcoastnews.com","",$text);
        $text = strip_tags($text);
        $tmp = explode(" ",$text);
        $res = "";
        $i = 0;
        while (strlen($res)<$len && $i<count($tmp))
            $res .= $tmp[$i++].' ';
        return $res.' ...';
    }

    /*
    *	Returns HTML code for pagination
    */
    function getPagination() {

        $pagination = '<div class="archiveItemNumber" style="float: left;">';

        // Write something like "3 of 5 results in Gossip Wire"
        if ($this->total < $this->limit) {
            $pagination .= $this->total.' of '.$this->total.' ';
        }
        else {
            $pagination .= $this->limit.' of '.$this->total.' ';
        }
        $pagination.= ' results in <b>Gossip Wire</b></div>';

        // actual pagination
        $pagination.= '<div class="archivePagination" style="margin-bottom:15px; float: right;">';

        // create URL with GET params for navigation links (limitstart parameter will be added later)
        $url = 	 'index.php?option=com_allgossip'
            .'&Itemid='.$this->_itemId
            .'&searchword='.$this->_search
            .'&view=moregossip'
            .'&limit='.$this->limit;

        // show previous link
        if ($this->limitstart-$this->limit >= 0) {
            $link = $url.'&limitstart='.($this->limitstart-$this->limit);
            $pagination .= '&lsaquo;&nbsp;<a style="font-weight:bold; text-decoration:underline;" href="'.$link.'">Prev</a>&nbsp;';
        }

        // lon - number of Links Of Navigation on pagination
        $lon = 4;
        // calculate number of first page in pagination
        $start = $this->limitstart/$this->limit - $lon/2;
        $start = $start < 0 ? 0 : $start;
        // calculate number of last page in pagination
        $end = $start+$lon;
        $end = $end*$this->limit > $this->total ? $this->total/$this->limit : $end;

        // display pagionation links
        for ($i=$start; $i<$end; $i++) {

            $link = $url .'&limitstart='.$this->limit*$i;
            $pagination .= "<a href=\"$link\">";

            // Write page number and mark current page with brackets: []
            if ($this->limitstart == $i*$this->limit) {
                $pagination .= '['.($i+1).']';
            }
            else {
                $pagination .= ($i+1);
            }
            $pagination .= '</a> ';
        }

        // show next link
        if ($this->total > $this->limitstart+$this->limit) {

            $link = $url . '&limitstart='.($this->limitstart+$this->limit);
            $pagination .= '<a style="text-decoration:underline; font-weight:bold;" href="'.$link.'">Next</a>&nbsp;&rsaquo;';
        }

        $pagination .='</div>';
        return $pagination;
    }

}
