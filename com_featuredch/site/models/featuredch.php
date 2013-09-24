<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

include_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/selectQuizzesDB.php");
if(!$status)
    die("Error accessing DB. Please check scripts/selectQuizzesDB.php if all the info is correct!");

jimport( 'joomla.application.component.model' );

class FeaturedchModelFeaturedch extends JModelLegacy
{
    function arrSort( $arr, $id0, $id1, $id2, $id3 ) {
        $r = array();
        for( $i = 0; $i < count( $arr ); $i++ ) {
            switch( $arr[$i]->id )  {
                case $id0:
                    $r[0] = $arr[$i];
                    break;
                case $id1:
                    $r[1] = $arr[$i];
                    break;
                case $id2:
                    $r[2] = $arr[$i];
                    break;
                case $id3:
                    $r[3] = $arr[$i];
            }
        }
        return $r;
    }

    function getContent()
    {
        $db = JFactory::getDBO();
        $menu = JMenu::getInstance('site');
        $item = $menu->getActive();

        $maxDate = ""; // keep this for getting the 'updated' fields calculated

        $document = JFactory::getDocument();
        $document->setTitle( $item->title );

        $params = $menu->getParams($item->id);

        $menuid = $params->get('menuid', '0');
        $page = $params->get('page', '0');

		//die($page);
		
        $query = 'SELECT * FROM #__featuredch WHERE id = \'' . $page . '\'';
        $db->setQuery( $query );

        $pageData = $db->loadObject();

        if(!$pageData)
            return NULL;



        $featuredch = array(); // this will containt data for pos1 .. pos4

        if($pageData->doctype != 3)
            $start = 1;
        else {
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
        } // else

        for($i = $start; $i <= 4; $i++) {
			//Alex :: select only first 4 id's
            eval('$query = "SELECT id,title,doctype,catid,updated,id1,id2,id3,id4 FROM `#__featured` WHERE id = " . $pageData->id'.$i.';');
            //echo $query; die();
			//die($query);
            $db->setQuery( $query );
            $posData = $db->loadObject();
			
            switch($posData->doctype) {
                case 1:
                    $query = "SELECT * FROM `#__content` WHERE id IN ( " . $posData->id1 . ", " . $posData->id2 . ", " . $posData->id3 . ", " . $posData->id4 . " )";
                    $db->setQuery( $query );
                    $results = $db->loadObjectList();
                    $results = FeaturedchModelFeaturedch::arrSort( $results, $posData->id1, $posData->id2, $posData->id3, $posData->id4 );
                    $query = "SELECT title FROM `#__categories` WHERE id = " . $posData->catid;
                    $db->setQuery( $query );
                    $title = $db->loadResult();
                    break;
                case 2:
                    $query = "SELECT recipe_id AS id, title, introtext, created, image FROM #__rr_recipes WHERE recipe_id IN ( " . $posData->id1 . ", " . $posData->id2 . ", " . $posData->id3 . ", " . $posData->id4 . " )";
                    $db->setQuery( $query );
                    $results = $db->loadObjectList();
                    $results = FeaturedchModelFeaturedch::arrSort( $results, $posData->id1, $posData->id2, $posData->id3, $posData->id4 );
                    $query = "SELECT title FROM #__rr_categories WHERE category_id = " . $posData->catid;
                    $db->setQuery( $query );
                    $title = $db->loadResult();
                    break;
                case 3:
                    $query = "SELECT id, title, created, isLive, introduction AS introtext FROM quizzes WHERE id IN ( " . $posData->id1 . ", " . $posData->id2 . ", " . $posData->id3 . ", " . $posData->id4 . " )";
                    $result = mysql_query( $query );
                    $results = array();
                    while( $row = mysql_fetch_object( $result ))
                        $results[] = $row;
                    $query = "SELECT title FROM targets WHERE target_id = " . $posData->catid;
                    $result = mysql_query( $query );
                    $results = FeaturedchModelFeaturedch::arrSort( $results, $posData->id1, $posData->id2, $posData->id3, $posData->id4 );
                    $title = mysql_result( $result, 0 );
            }

            $feat = new stdClass();
            $feat->cattitle = $pageData->title;
            $feat->doctype = $posData->doctype;
            $date =  $posData->updated;
            if($maxDate < $date)
                $maxDate = $date;
            $feat->updated = $maxDate;


            $feat->catid = $posData->catid; // was: null; .. but this can't hurt
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
            $feat->title = $title;

            if(!$feat->title)
                return NULL;
            $ccode = null;
            eval('$ccode = $pageData->html' . $i . ';');
            if($ccode == "") {
                $feat->catid = $posData->catid;
                for($j = 0; $j < count($results); $j++) {
                    eval('$feat->atitle' . ($j + 1) . ' = $results[$j]->title;');
                    eval('$feat->id' . ($j + 1) . ' = $results[$j]->id;');
                    if($posData->doctype == 1) {
                        if(strlen($results[$j]->introtext) > 0) {
                            $pullImagesFrom = $results[$j]->introtext;
                            $str = strip_tags($results[$j]->introtext);
                        } else {
                            $pullImagesFrom = $results[$j]->fulltext;
                            $str = strip_tags($results[$j]->fulltext);
                        }
                        $titleLen = strlen($results[$j]->title)*1.3;
                        if(strlen($str)+$titleLen > 120 ){
                            $s = explode(" ", $str);
                            $myS = "";
                            $danC = 0;
                            while(strlen($myS)+$titleLen < 120) {
                                $myS .= $s[$danC] . " ";
                                $danC++;
                            }
                            $myS = trim($myS);
                            $str = $myS . '...';
                        }

                        eval('$feat->intro' . ($j + 1) . ' = $str;');

                        $img = "";
                        $imgs = explode("|",$results[$j]->images);
                        eval('$feat->img' . ($j + 1) . ' = $imgs[0];');
                        eval('$img = $feat->img' . ($j + 1) .';');
                        if($img == "" || (($img != "") && (!(substr( $tmp[0], -4 ) == (".[jpgnfit]{3}"))))) {
                            //$danREG = "/<img[ ]*src=\"[a-zA-Z0-9\._\/]*\"[ ]*[a-zA-Z=\"\/\._]*>/";
                            $danREG = "/<img[^\>]+\>/";
                            preg_match_all($danREG, $pullImagesFrom, $danMatches, PREG_PATTERN_ORDER);
                            if(count($danMatches[0])>0) { // ok ... at least one image is found in text (intro OR full)
								//if($j==1)die('dsasssssssssssdssssssssssssssss');
                                $danREG = "/src=[\'\"][a-zA-Z0-9\._\/]*[\'\"]/";
                                preg_match_all($danREG, $danMatches[0][0], $danMatches, PREG_PATTERN_ORDER);
								$danImg = substr($danMatches[0][0],5, -1);
                                //$danImg = str_replace("src=\"", "", $danMatches[0][0]);
                                //$danImg = str_replace("\"", "", $danImg);
                                // edited by staiano 2012-02-24 to remove images/stories
                                // $danImg = str_replace("images/stories/", "", $danImg);
                                eval('$feat->img' . ($j + 1) . ' = $danImg;');
                            }
                        }
                    //Alex :: check this later
                    } else if($posData->doctype == 2) {
                        //$feat->intro = $results[$j]->introtext;
                        eval('$feat->intro' . ($j + 1) . ' = $results[$j]->introtext;');
                        //$feat->img 	 = "/images/stories/food/recipe/recipe".$results[$j]->id.".jpg";
                        eval('$feat->img' . ($j + 1) . ' = /images/stories/food/recipe/recipe' . $results[$j]->id . '.jpg;');

                        $img = "";
                        eval('$img = $feat->img' . ($j + 1) . ';');
                        $file = $GLOBALS['mosConfig_absolute_path'] . $img;
                        if(!file_exists($file))  // edited by staiano 2012-02-24 to add images/stories
                        {
                            //$feat->img = "/images/stories/food/recipe/recipeGeneric.jpg";
                            eval('$feat->img' . ($j + 1) . ' = /images/stories/food/recipe/recipeGeneric.jpg;');
                        }
                    } else {
                        //$feat->intro = $results[$j]->introtext;
                        eval('$feat->intro' . ($j + 1) . ' = $results[$j]->introtext;');
                        // edited by staiano 2012-02-24 to add images/stories
                        //$feat->img   = "/images/stories/quiz/quiz" . $results[$j]->id . ".jpg";
                        eval('$feat->img' . ($j + 1) . ' = /images/stories/quiz/quiz' . $results[$j]->id . '.jpg;');

                        $img = "";
                        eval('$img = $feat->img' . ($j + 1) . ';');
                        $file = $GLOBALS['mosConfig_absolute_path'] . $img;
                        if(!file_exists($file))
                        {
                            //$feat->img = "/images/stories/quiz/quizGeneric.jpg";
                            eval('$feat->img' . ($j + 1) . ' = /images/stories/quiz/quizGeneric.jpg;');
                        }
                    }
                } // for
            } else {
                eval('$feat->custom_code = $pageData->html'.$i.';');
            }
            $feat->itemid = JFactory::getApplication()->input->get("Itemid");
            $featuredch[] = $feat;
			

        }
        return $featuredch;
    }
}
