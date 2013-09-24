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
        /*else {
		
            // we have a quiz here, so the first one is fully automated !
            $start = 2;
			//Alex :: select only first 4 id's
            $query = "SELECT id,title,doctype,catid,updated,id1,id2,id3,id4 FROM #__featured WHERE id = " . $pageData->id1;
			//die($query);
            $db->setQuery( $query );
            $posData = $db->loadObject();

            $feat = new stdClass();
            $feat->cattitle = $posData->title;
            //$feat->cattitle = "Newest Quizzes"; // DAN::: this may be subject to change ...

            $feat->doctype = 3;

            $feat->catid = null;
            $feat->menuid = $menuid;
            $feat->id1 = null;
            $feat->id2 = null;
            $feat->id3 = null;
            $feat->id4 = null;
            $feat->itemid = null;
            $feat->title = null;
            $feat->img1 = null;
            $feat->img2 = null;
            $feat->img3 = null;
            $feat->img4 = null;
            $feat->intro1 = null;
            $feat->intro2 = null;
            $feat->intro3 = null;
            $feat->intro4 = null;
            $feat->atitle1 = null;
            $feat->atitle2 = null;
            $feat->atitle3 = null;
            $feat->atitle4 = null;
            $feat->custom_code = null;
            $feat->updated  = $maxDate;
            $feat->title = "Newest Quizzes";

            // first get 4 quizzes ids that are quizzes of the day in the DailyQuizCorner
            $date = date('Y-m-d');
            $query = "SELECT dqca.QuizID from `DailyQuizCornerArticles` AS dqca, `DailyQuizCorner` AS dqc \n"
                ."WHERE dqca.QuizCornerID = dqc.QuizCornerID AND Date <= '" . $date . "' \n"
                ."ORDER BY Date DESC LIMIT 4";
            $db->setQuery( $query );
            $ress = $db->loadObjectList();
            //Alex :: check this, not sure what is all this
            $i = 0;
            foreach($ress as $res) {
                $i++;
                $query = "SELECT id, title, created, isLive, introduction FROM quizzes WHERE id = " . $res->QuizID;
                $result = mysql_query( $query );
                $row = mysql_fetch_object( $result );
                $date =  substr($row->created,0,10);
                if($maxDate < $date)
                    $maxDate = $date;
                $feat->updated = $maxDate;
                eval('
				if($pageData->html'.$i.' == "") {
					$feat->catid = ""; // DAN::: no use of this for quizzes ???
					$feat->atitle'.$i.' = $row->title;
					$ii = ' . $i . ';
					if( $ii == 1 ) {
					//Alex :: changed this $feat->id to $feat->id1
						$feat->id1 = $row->id;
					} else {
						$feat->id'.$i.' = $row->id;
					}
					$feat->intro'.$i.'= $row->introduction;
					$feat->img'.$i.' = "quiz/quiz" . $row->id . ".jpg";
				} else {
					$feat->custom_code = $pageData->pos'.$i.'custom_code;
				}');
                $feat->itemid = JFactory::getApplication()->input->get("Itemid"); // DAN::: this might need to be hardcoded
                //mail("dan@ogosense.com", time(), count($ress)."\n" . print_r($row, true) . "\nFEAT:\n" . print_r($feat, true));
            } // foreach
            $featuredch[] = $feat;

        } // else*/

            switch($data->doctype) {
                case 1:
                    $query = "SELECT id,introtext,images,title FROM `#__content` WHERE id IN ( " . $data->id2 . ", " . $data->id3 . ", " . $data->id4 . ", " . $data->id5 . ", " 
                                                                                              . $data->id6 . ", " . $data->id7 . ", " . $data->id8 . ", " . $data->id9 . ", " 
                                                                                              . $data->id10 . ", " . $data->id11 . ", " . $data->id12 . ", " . $data->id13 . ", " 
                                                                                              . $data->id14 . ", " . $data->id15 . ", " . $data->id16 . ", " . $data->id17. ")";
                    $db->setQuery( $query );
                    $results = $db->loadObjectList();
					//die('<pre>'.print_r($results, true).'</pre>');
                    foreach($results as $result)
                    {
                       
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
                    }

                    break;
                case 2:
                    /*$query = "SELECT recipe_id AS id, title, introtext, created, image FROM #__rr_recipes WHERE recipe_id IN ( " . $posData->id1 . ", " . $posData->id2 . ", " . $posData->id3 . ", " . $posData->id4 . " )";
                    $db->setQuery( $query );
                    $results = $db->loadObjectList();
                    $results = FeaturedchModelFeaturedch::arrSort( $results, $posData->id1, $posData->id2, $posData->id3, $posData->id4 );
                    $query = "SELECT title FROM #__rr_categories WHERE category_id = " . $posData->catid;
                    $db->setQuery( $query );
                    $title = $db->loadResult();*/
                    break;
                case 3:
                    /*$query = "SELECT id, title, created, isLive, introduction AS introtext FROM quizzes WHERE id IN ( " . $posData->id1 . ", " . $posData->id2 . ", " . $posData->id3 . ", " . $posData->id4 . " )";
                    $result = mysql_query( $query );
                    $results = array();
                    while( $row = mysql_fetch_object( $result ))
                        $results[] = $row;
                    $query = "SELECT title FROM targets WHERE target_id = " . $posData->catid;
                    $result = mysql_query( $query );
                    $results = FeaturedchModelFeaturedch::arrSort( $results, $posData->id1, $posData->id2, $posData->id3, $posData->id4 );
                    $title = mysql_result( $result, 0 );*/
            }
		//die('<pre>'.print_r($query, true).'</pre>');
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
