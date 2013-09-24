<?php
/**
 * @version     1.0.0
 * @package     com_featuredchannel
 * @copyright   OGOSense
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Alex <aaleksic@ogosense.com> - http://
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$db = JFactory::getDBo();
$document = JFactory::getDocument();
$document->addStyleSheet('components/featuredch/assets/css/featuredch.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function(){
        
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'featuredch.cancel'){
            Joomla.submitform(task, document.getElementById('featuredch-form'));
        }
        else{
            
            if (task != 'featuredch.cancel' && document.formvalidator.isValid(document.id('featuredch-form'))) {
                
                Joomla.submitform(task, document.getElementById('featuredch-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_featuredch&layout=edit&id=' . (int) $this->featuredch->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="featuredch-form" class="form-validate">
	<div class="row-fluid">
		<div class="span10 form-horizontal">
		<fieldset class="adminform">

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('doctype'); ?></div>
					<?php
						$aChecked = "";
						$rChecked = "";
						$qChecked = "";
						switch($this->featuredch->doctype) {
							case 1: $aChecked = "checked=\"checked\"";break; // $table needed later 
							case 2: $rChecked = "checked=\"checked\"";break;
							case 3: $qChecked = "checked=\"checked\"";break;
							//default: $achecked = "checked=\"checked\"";
						}
					?>
					<div class="controls">
						<fieldset class="radio" id="jform_doctype">
							<input type="radio" value="1" <?php echo $aChecked; ?> name="jform[doctype]" id="jform_doctype0" <?php if($this->featuredch->id != 0) echo "onchange=\"Joomla.submitbutton('featuredogo.apply')\""; ?>>
								<label for="jform_doctype0" class="">Article</label>
							<input type="radio" value="2" <?php echo $rChecked; ?> name="jform[doctype]" id="jform_doctype1" <?php if($this->featuredch->id != 0) echo "onchange=\"Joomla.submitbutton('featuredogo.apply')\""; ?>>
								<label for="jform_doctype1">Recipe</label>
							<input type="radio" value="3" <?php echo $qChecked; ?> name="jform[doctype]" id="jform_doctype2" <?php if($this->featuredch->id != 0) echo "onchange=\"Joomla.submitbutton('featuredogo.apply')\""; ?>>
								<label for="jform_doctype2">Quizz</label>
						</fieldset>
					</div>
				</div>
			
			<?php // this sets id1 - id4 and html1 - html4... 
				$query = "SELECT id, title FROM #__featured";
				$db->setQuery( $query );
				$results = $db->loadObjectList();
				for($i = 1; $i <= 4; $i++) {
					switch($i) {
						case 1: $html = $this->featuredch->html1; $id = $this->featuredch->id1; break;
						case 2: $html = $this->featuredch->html2; $id = $this->featuredch->id2; break;
						case 3: $html = $this->featuredch->html3; $id = $this->featuredch->id3; break;
						case 4: $html = $this->featuredch->html4; $id = $this->featuredch->id4;
					}
			
			?>
				<div class="control-group">
					<div class="control-label"><?php echo JText::_( 'Box. ' . $i . ' Assoc.' ); ?>:</div>
					<div class="controls">
						<select name="jform[id<?php echo $i;?>]" id="jform_id<?php echo $i;?>">
							<?php
							foreach($results as $result) { ?>
								<option value="<?php echo $result->id; ?>" 
								<?php if( $result->id == $id ) echo ' selected="selected" ';?>><?php echo $result->title;?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo JText::_( 'Custom HTML' ); ?>:</div>
				<div class="controls"><?php echo $this->form->getInput('html'.$i); ?></div>
			</div>
			<?php	
			}
			?>
		</fieldset>
		</div>

        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

	</div>
</form>