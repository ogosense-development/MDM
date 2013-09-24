<?php // no direct access
/*defined('_JEXEC') or die('Restricted access'); 
global $mosConfigLiveSite;
$month = "";
// get appropriate Itemids
$menuid = $this->featuredch[0]->menuid;
$db = JFactory::getDBO();
$query = "SELECT title FROM #__menu_types WHERE id = '" . $menuid . "'";
$db->setQuery( $query );
$menutitle = $db->loadResult();
$catItemid = array(); // categories landing pages Itemids
$artItemid = array(); // article landing pages Itemids
for($i=0; $i<count($this->featuredch); $i++) {
	if($this->featuredch[$i]->doctype == 3) { // quiz
		if($i == 0) // this is quiz of the day !!!
			$query = "SELECT id FROM #__menu WHERE menutype = '" . $menutitle . "' AND name = 'Quiz of The Day'"; 
		else {
			$query = "SELECT id FROM #__menu WHERE menutype = '" . $menutitle . "' AND name = 'All' AND parent IN (SELECT id FROM #__menu WHERE menutype = '" . $menutitle . "' AND name = 'Quizzes' AND parent = 0)"; 
		}	
		$db->setQuery( $query );
		$result = $db->loadObject();
		$catItemid[$i] = ( count($result) > 0 ? $result->id : "");
	} else {
		//$query = "SELECT id FROM #__menu WHERE menutype = '" . $menutitle . "' AND link LIKE '%option=com_articlearchives%' AND link LIKE '%cid=".$this->featuredch[$i]->catid."%'";
		//get section name
		$query = "SELECT title FROM #__categories WHERE id = (SELECT parent_id FROM #__categories WHERE id=".$this->featuredch[$i]->catid.')';
		$db->setQuery($query);
		$section = $db->loadResult();
	}

}
for($i=0; $i<count($this->featuredch); $i++) {
	$field="";
	if($i != 0)
		$field = $i + 1;
	//eval('$val = $this->featuredch[$i]->id'. ($field != 1 ? $field : "" ) .';');
	if($this->featuredch[$i]->doctype != 3) {
		if($this->featuredch[$i]->doctype != 2)
			$query = "SELECT id FROM #__menu WHERE menutype = '" . $menutitle . "' AND link LIKE '%index.php?option=com_article&view=article&catid=".$this->featuredch[$i]->catid."%'";
		else 
			$query = "SELECT m.id AS id FROM #__menu m WHERE m.type = 'component' AND m.link = 'index.php?option=com_rapidrecipe' AND m.published >-1;";
		$db->setQuery( $query );
		$result = $db->loadObject();
		$artItemid[$i] = $result->id;
	} else 
		$artItemid[$i] = ""; // no need for this in case of quiz links
}

echo "<table>";
for($i=0; $i<count($this->featuredch); $i++) {
	//echo "<div class=\"ftrdbox\" id=\"pos$i\">";
	if($this->featuredch[$i]==NULL)
		echo "There was an error with getting content. Please check your choice of articles.";
	else {
		if($this->featuredch[$i]->custom_code == "") {
			# Boris commented out line bellow
			# $catlink = str_replace(array(' & ',' '),'_',strtolower($section.'/'.$this->featuredch[$i]->title)).'.php';
			# We need section id of this category
			$query = 'SELECT parent_id FROM #__categories WHERE id = "' . $this->featuredch[$i]->catid . '"';
			$db->setQuery($query);
			# require content helper
			require_once( JPATH_SITE . '/components' . '/com_content' . '/helpers/'  . 'route.php' );
			# generate category link
            //Alex :: cjeck this, this function is changed in Joomla 3.x
            $catlink = JRoute::_(ContentHelperRoute::getCategoryRoute($this->featuredch[$i]->catid));

            echo "<tr>";
                echo "<td colspan='4'>";
			        echo "<h2 class=\"cattitle\"><a href=\"" . $catlink."\">" . $this->featuredch[$i]->title . "</a></h2>";
                echo "</td>";
            echo "</tr>";
			//echo '<div style="height:125px;">';
			//echo '<div">';

            //Alex :: add this for eval funct
            $id = "";
            $img = "";
            $atitle = "";
            $intro = "";
			switch($this->featuredch[$i]->doctype) {
				case 1: // ********************************** simple article  *****************************************

                    echo "<tr>";
                    for($j=1; $j<=4; $j++)
                    {
                        eval('$id = $this->featuredch[$i]->id' . $j . ';');
                        eval('$img = $this->featuredch[$i]->img' . $j . ';');

                        echo "<td style='height: 160px;width: 160px'>";
                            //echo "<a href=\"".JRoute::_("index.php?option=com_article&view=article&id=".$id."&Itemid=".$artItemid[$i])."\"><img class=\"artimg\" src=\"slir/w125-h125/" . $img."\" /></a>";
                            echo "<a href=\"".JRoute::_("index.php?option=com_article&view=article&id=" . $id . "&Itemid=".$artItemid[$i])."\"><img height = \"160px\" width = \"160px\" class=\"artimg\" src=\"" . $img . "\" /></a>";
                        echo "</td>";
                    }
                    echo "</tr>";

                    echo "<tr>";
                        for($j=1; $j<=4; $j++)
                        {
                            eval('$id = $this->featuredch[$i]->id' . $j . ';');
                            eval('$atitle = $this->featuredch[$i]->atitle' . $j . ';');
                            eval('$intro = $this->featuredch[$i]->intro' . $j . ';');

                            echo "<td style='height: 50px;width: 160px'>";
                                echo "<a href=\"".JRoute::_("index.php?option=com_article&view=article&id=" . $id . "&Itemid=".$artItemid[$i])."\"><span class=\"arttitle\">" . $atitle . "</span></a>";
                                echo "<p class=\"introtext\">" . $intro . "</p>";
                            echo "</td>";
                        }
                    echo "</tr>";

					break;
					
				case 2: // ********************************** rapid recipe *****************************************

                    echo "<tr>";
                    for($j=1; $j<=4; $j++)
                    {
                        eval('$id = $this->featuredch[$i]->id' . $j . ';');
                        eval('$img = $this->featuredch[$i]->img' . $j . ';');

                        echo "<td style='height: 160px;width: 160px'>";
                            //echo "<a href=\"".JRoute::_("index.php?option=com_rapidrecipe&page=viewrecipe&recipe_id=" . $id . "&Itemid=".$artItemid[$i])."\"><img class=\"artimg\" src=\"slir/w125-h125/" . $img . "\" /></a>";
                            echo "<a href=\"".JRoute::_("index.php?option=com_rapidrecipe&page=viewrecipe&recipe_id=" . $id . "&Itemid=".$artItemid[$i])."\"><img class=\"artimg\" src=\"" . $img . "\" /></a>";
                        echo "</td>";
                    }
                    echo "</tr>";

                    echo "<tr>";
                    for($j=1; $j<=4; $j++)
                    {
                        eval('$id = $this->featuredch[$i]->id' . $j . ';');
                        eval('$atitle = $this->featuredch[$i]->atitle' . $j . ';');
                        eval('$intro = $this->featuredch[$i]->intro' . $j . ';');

                        echo "<td style='height: 50px;width: 160px'>";
                            echo "<a href=\"".JRoute::_("index.php?option=com_rapidrecipe&page=viewrecipe&recipe_id=" . $id . "&Itemid=".$artItemid[$i])."\"><span class=\"arttitle\">" . $atitle . "</span></a>";
                            echo "<p class=\"introtext\">" . $intro."</p>";
                        echo "</td>";
                    }
                    echo "</tr>";

					break;
				
				case 3: // ********************************** quizzes *****************************************

                    echo "<tr>";
                    for($j=1; $j<=4; $j++)
                    {
                        eval('$id = $this->featuredch[$i]->id' . $j . ';');
                        eval('$img = $this->featuredch[$i]->img' . $j . ';');
                        echo "<td style='height: 50px;width: 160px'>";
					        //echo "<a href=\"/app/quiz/userquiz/takequiz/" . $id . "\"><img class=\"artimg\" src=\"slir/w125-h125/" . $img . "\" /></a>";
                            echo "<a href=\"/app/quiz/userquiz/takequiz/" . $id . "\"><img class=\"artimg\" src=\"" . $img . "\" /></a>";
                        echo "</td>";
                    }
                    echo "</tr>";

                    for($j=1; $j<=4; $j++)
                    {
                        eval('$id = $this->featuredch[$i]->id' . $j . ';');
                        eval('$atitle = $this->featuredch[$i]->atitle' . $j . ';');
                        eval('$intro = $this->featuredch[$i]->intro' . $j . ';');

                        echo "<td style='height: 50px;width: 160px'>";
                            echo "<a href=\"".JRoute::_("index.php?option=com_rapidrecipe&page=viewrecipe&recipe_id=" . $id . "&Itemid=".$artItemid[$i])."\"><span class=\"arttitle\">" . $atitle . "</span></a>";
					        echo "<p class=\"introtext\">" . $intro . "</p>";
                        echo "</td>";
                    }
                    echo "</tr>";
			} // switch
		} else {
			echo "<h2 class=\"cattitle\">". $this->featuredch[$i]->title . "</h2>\n<br />"; //added by Ivan
			echo $this->featuredch[$i]->custom_code ; 
		} 
	}
	//echo "</div>";
}
echo '</table>';*/
?>
