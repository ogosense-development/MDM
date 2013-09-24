<?php // no direct access
	
	defined('_JEXEC') or die('Restricted access'); 
?>
<style>
.span3
{
	width:25%;
	height:25%;
	float:left;
}
.aco {
    width: 90%;
	height: auto;
    max-height: 70%;
    max-width: 90%;
}
</style>
	<h1><?php echo $this->title ?></h1>
	<?php	
	for($i=0; $i<count($this->featuredcontent); $i++){?>
		<div class="span3">
			<a href="<?php echo $this->featuredcontent[$i]->link ?>" >
			<div style="width:100% padding:10px;"><img class="aco" src="<?php echo $this->featuredcontent[$i]->images;?>" height="150px" width="150px" /></div>
			<?php
			echo '</a>';
			?>
			<a href="<?php echo $this->featuredcontent[$i]->link ?>" >
			<?php
				  echo '<div style="weight:90% height:15%;">';
				  echo $this->featuredcontent[$i]->title;
				  echo '</div>';
				  echo '</a>';
				  echo '<div style="weight:90% height:15% height:15%;">';
				  echo $this->featuredcontent[$i]->introtext;
				  echo '</div>';
			?>
		</div>
	<?php } ?>