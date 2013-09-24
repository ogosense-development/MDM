<?php
/**
 * @version     1.0.0
 * @package     com_featuredogo
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Alex <abjelosevic@ogosense.com> - http://www.ogosense.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

$db = JFactory::getDBO();
include($_SERVER['DOCUMENT_ROOT']."/scripts/selectQuizzesDB.php");

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_featuredogo/assets/css/featuredogo.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function(){
        
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'featuredogo.cancel'){
            Joomla.submitform(task, document.getElementById('featuredogo-form'));
        }
        else{
            
            if (task != 'featuredogo.cancel' && document.formvalidator.isValid(document.id('featuredogo-form'))) {
                
                Joomla.submitform(task, document.getElementById('featuredogo-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_featuredogo&layout=edit&id=' . (int) $this->featured->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="featuredogo-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
        <tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Page Title' ); ?>:
				</label>
			</td>
			<td>
				<div class="control-group">
					<div class="controls">
						<?php echo $this->form->getInput('title'); ?>
					</div>
				</div>
			</td>
		</tr>
        <tr>
			<td width="100" align="right" class="key">
				<label for="doctype">
					<?php echo JText::_( 'Document type' ); ?>:
				</label>
			</td>
			<td>
            	<?php // determine which document is selected (if any)
					$aChecked = "";
					$rChecked = "";
					$qChecked = "";
					switch($this->featured->doctype) {
						case 1: $aChecked = "checked=\"checked\"";break; // $table needed later 
						case 2: $rChecked = "checked=\"checked\"";break;
						case 3: $qChecked = "checked=\"checked\"";break;
						//default: $achecked = "checked=\"checked\"";
					}
				?>
				
			<div class="control-group">
				<div class="controls">
					<fieldset class="radio" id="jform_doctype">
						<input type="radio" value="1" <?php echo $aChecked; ?> name="jform[doctype]" id="jform_doctype0" <?php if($this->featured->id != 0) echo "onchange=\"Joomla.submitbutton('featuredogo.apply')\""; ?>>
							<label for="jform_doctype0" class="">Article</label>
						<input type="radio" value="2" <?php echo $rChecked; ?> name="jform[doctype]" id="jform_doctype1" <?php if($this->featured->id != 0) echo "onchange=\"Joomla.submitbutton('featuredogo.apply')\""; ?>>
							<label for="jform_doctype1">Recipe</label>
						<input type="radio" value="3" <?php echo $qChecked; ?> name="jform[doctype]" id="jform_doctype2" <?php if($this->featured->id != 0) echo "onchange=\"Joomla.submitbutton('featuredogo.apply')\""; ?>>
							<label for="jform_doctype2">Quizz</label>
					</fieldset>
				</div>
			</div>			
			</td>
		</tr>
		<!--<tr>
			<td colspan="2">
				<?php //echo JText::_('Please choose one document for each ID1 - ID4.');?>
			</td>
		</tr>-->
		<tr>
			<td width="100" align="right" class="key">
				<label for="category">
					<?php echo JText::_( 'Category' ); ?>:
				</label>
			</td>
            <td>
				<div class="control-group">
					<div class="controls">
						<select name="jform[catid]" id="jform_catid" onchange="Joomla.submitbutton('featuredogo.apply')">
							<?php if($this->featured->doctype == 1 || $this->featured->doctype == 2) {
								if($this->featured->doctype == 1) {
									$query = "SELECT id, title FROM #__categories \n"
											. "WHERE published = 1 ORDER BY title";
									$db->setQuery( $query );
									$results = $db->loadObjectList();
								} else {
									// recipes
									$query = "SELECT category_id AS id, title FROM #__rr_categories \n"
											. "WHERE published = 1 ORDER by title";
									$db->setQuery( $query );
									$results = $db->loadObjectList();
								}
								foreach($results as $result) {?>
									<option value="<?php echo $result->id; ?>" <?php if($result->id == $this->featured->catid) echo 'selected="selected"'; ?>><?php echo $result->title; ?></option>
							<?php
								} // foreach
							} else {
								// ok, let's categorize quizzes as well ... 
								$query = "SELECT title, target_id FROM targets";
								$result = mysql_query( $query );
								if(!$result) 
									die( "Error retrieving quizzes categories!" );
								$results = array();
								while($r = mysql_fetch_ARRAY($result, MYSQL_ASSOC)) {
									echo "<option value=\"" . $r['target_id'] . "\"";
									if($r['target_id'] == $this->featured->catid)
										echo 'selected="selected"';
									echo ">" . $r['title'] . "</option>\n";
								}
							}
							?>
						</select>
					</div>
				</div>
			 </td>
         </tr>
		 <?php // this sets id1 - id4 as multiple select ... 
			$date = JFactory::getDate();
			$now = $date->toSQL();
			$nullDate = $db->getNullDate();
			switch($this->featured->doctype) {
				case 1: // articles
					$query = "SELECT id, title FROM #__content \n"
							."WHERE catid = " . $this->featured->catid . " AND \n"
							."( publish_up = " . $db->Quote($nullDate) . " OR publish_up <= " . $db->Quote($now) . ") AND \n"
							."(publish_down = ".$db->Quote($nullDate)." OR publish_down >= ".$db->Quote($now)." OR publish_down = '0000-00-00 00:00:00') ORDER BY title";
					$db->setQuery($query);
					$results = $db->loadAssocList();
					break;
				case 2: // recipes
					$query = "SELECT r.recipe_id AS id, r.title AS title FROM #__rr_recipes AS r, #__rr_categories AS c, #__rr_recipecategory AS rc \n"
							."WHERE r.published = 1 AND c.category_id = " . $this->featured->catid . " AND r.recipe_id = rc.recipe_id \n"
							."AND rc.category_id = c.category_id ORDER BY title";
					$db->setQuery($query);
					$results = $db->loadAssocList();
					break;
				case 3: // quizzes
					$query = "SELECT title FROM targets WHERE target_id = " . $this->featured->catid;
					$result = mysql_query( $query );
					if(!$result)
						die( "Error getting quiz category title!" );
					$category = mysql_result($result, 0);
					$query = "SELECT id, title FROM quizzes WHERE isLive = 1 AND target like '%" . $category . "%' ORDER BY title";
					$result = mysql_query( $query );
					if(!$result) 
						die("Something went wrong... Please check scripts/selectQuizzesDB.php if all the info is correct!");
					$results = array();
					while($r = mysql_fetch_array($result, MYSQL_ASSOC))
						$results[] = $r;
					//mail("dan@ogosense.com", time(), $query . "\n----\n" . print_r($results[0], true));
			} // now we have the results
			
					
			for($i = 1; $i <= 17; $i++) {
				switch($i) {
					case 1: $idCheck = $this->featured->id1; break;
					case 2: $idCheck = $this->featured->id2; break;
					case 3: $idCheck = $this->featured->id3; break;
					case 4: $idCheck = $this->featured->id4; break;
					case 5: $idCheck = $this->featured->id5; break;
					case 6: $idCheck = $this->featured->id6; break;
					case 7: $idCheck = $this->featured->id7; break;
					case 8: $idCheck = $this->featured->id8; break;
					case 9: $idCheck = $this->featured->id9; break;
					case 10: $idCheck = $this->featured->id10; break;
					case 11: $idCheck = $this->featured->id11; break;
					case 12: $idCheck = $this->featured->id12; break;
					case 13: $idCheck = $this->featured->id13; break;
					case 14: $idCheck = $this->featured->id14; break;
					case 15: $idCheck = $this->featured->id15; break;
					case 16: $idCheck = $this->featured->id16; break;
					case 17: $idCheck = $this->featured->id17; 
				}
		 ?>
		 <tr>
			<td width="100" align="right" class="key">
				<label for="document<?php echo $i;?>">
                	<?php echo JText::_( 'Doc. ' . $i ); ?>:
                </label>
			</td>
            <td>
				<div class="control-group">
					<div class="controls">
						<select name="jform[id<?php echo $i;?>]" id="jform_id<?php echo $i;?>">
							<option value="0"></option>
						<?php
							foreach($results as $result) { ?>
							<option value="<?php echo $result['id']; ?>" 
								<?php if( $result['id'] == $idCheck ) echo 'selected="selected"';?>><?php echo $result['title'];?>
							</option>
					   <?php } ?>
						</select>
					</div>
				</div>
           	</td>
           </tr>
       <?php
		} // end of for loop
		?>
	</table>	
            </fieldset>
        </div>
        
		<input type="hidden" id="jform_updated" name="jform[updated]" value="<?php echo date('Y-m-d'); ?>" />
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>