<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

include_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/selectQuizzesDB.php");
if(!$status)
    die("Error accessing DB. Please check scripts/selectQuizzesDB.php if all the info is correct!");

jimport( 'joomla.application.component.model' );

class FeaturedchModelFeaturedcontent extends JModelLegacy
{

    function getContent()
    {
        $db = JFactory::getDBO();
        $menu = JMenu::getInstance('site');
        $item = $menu->getActive();
		
		$params = $menu->getParams($item->id);

        $menuid = $params->get('menuid', '0');
        $page = $params->get('page', '0');

        $query = "SELECT * FROM #__featured WHERE id = " . $page;
        $db->setQuery( $query );

        $data = $db->loadObject();

        //die('<pre>'.print_r($data, true).'</pre>');

        if(!$data)
            return NULL;


        if($data->doctype != 3)
            $start = 1;
        

            switch($data->doctype) {
                case 1:
                    $query = "SELECT id,introtext,images,title, catid FROM `#__content` WHERE id IN ( " . $data->id2 . ", " . $data->id3 . ", " . $data->id4 . ", " . $data->id5 . ", " 
                                                                                              . $data->id6 . ", " . $data->id7 . ", " . $data->id8 . ", " . $data->id9 . ", " 
                                                                                              . $data->id10 . ", " . $data->id11 . ", " . $data->id12 . ", " . $data->id13 . ", " 
                                                                                              . $data->id14 . ", " . $data->id15 . ", " . $data->id16 . ", " . $data->id17. ")";
                    $db->setQuery( $query );
                    $results = $db->loadObjectList();
					//die('<pre>'.print_r($results, true).'</pre>');
                    foreach($results as $result)
                    {
                        $img = Array();
						$img = explode('|', $result->images);
						if(file_exists($img[0]))
							$result->images = $img[0];
						else
							$result->images = null;
                        if(empty($result->images))
                        {
                            preg_match('/<img [^>]+>/',$result->introtext, $matches);
                            if(!empty($matches[0]))
                            {
                                preg_match('/src="[^"]+"/',$matches[0], $match);
                                $result->images = substr($match[0],5,-1);
                            }
                            else
                            {
                                $result->images = 'images/stories/food/recipe/recipeGeneric_thumb.jpg';
                            }
                        }
						$result->introtext = $this->getIntro($result->introtext);
						$query = "SELECT id FROM #__menu WHERE link LIKE '%index.php?option=com_article&view=article&catid=".$result->catid."%'";
						$db->setQuery( $query );
						$itemid = $db->loadResult();
						$result->link = 'index.php?option=com_article&view=article&id='.$result->id.'&Itemid='.$itemid;
                    }

                    break;
                case 2:
                    
                    break;
                case 3:
                    
            }
		
        return $results;
    }

    function getIntro($text, $len = 60) {
        $text = strip_tags($text);
        $tmp = explode(' ',$text);
        $res = '';
        $i=0;
        while ( (strlen($res)<$len) && ($i<count($tmp)) )
            $res.=$tmp[$i++].' ';
        return $res.'...';
    }

    function getTitle()
    {
        $db = JFactory::getDBO();
        $menu = JMenu::getInstance('site');
        $item = $menu->getActive();
		
		$params = $menu->getParams($item->id);
        $page = $params->get('page', '0');

        $query = "SELECT catid FROM #__featured WHERE id = " . $page;
        $db->setQuery( $query );
        $data = $db->loadObject();

        $query = "SELECT title FROM `#__categories` WHERE id = " . $data->catid;
        $db->setQuery( $query );
        return $db->loadResult();
    }
}
