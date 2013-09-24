<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="gossipTitle">
	<?php echo $this->gossip->title;?>
</div>
<div>
<!-- <div id="gossipContent"> -->

	<div id="gossipImage" >
		<!--<img src="<?php //echo 'slir/w350-h350/'.$this->gossip->image; ?>"/>-->
		<img src="<?php echo $this->gossip->image; ?>"/>
	</div>

	<div style="line-height:22px;" id="gossipText">
		<?php //echo strip_tags($this->gossip->introtext,'<p><a>'); 
				//strip images from content
			  echo preg_replace("/<img[^>]+\>/i", "", $this->gossip->introtext);
		?>

		<div id="mdmSwoop">
			<!-- +SWOOP -->
			<script type="text/javascript"
			  src="http://ardrone.swoop.com/js/spxw.js"
			  data-domain="SW-57430661-6"
			  data-serverbase="http://ardrone.swoop.com/">
			</script>
			<!-- -SWOOP -->
		</div>

		<!-- Show sharing links -->
		<?php
		include_once($_SERVER["DOCUMENT_ROOT"] . "/scripts/addThisJS.php");
	    include_once($_SERVER["DOCUMENT_ROOT"] . "/scripts/getMySEFURL.php");
		?>
		<div style="height:20px;margin:10px 0 10px 0;overflow:hidden;">
			<?php
			include_once($_SERVER["DOCUMENT_ROOT"] . "/scripts/addThisCodeArticles.php");
			include_once($_SERVER["DOCUMENT_ROOT"] . "/scripts/addThisCodeInit.php");
			?>
		</div>
		<!-- end Sharing links -->
	</div>
	
<?php include_once($_SERVER["DOCUMENT_ROOT"] . "scripts/taboola.php"); ?>
</div>
<div id="gossipNavigation" style="clear: both; border-left:none; border-right:none;border-top:1px solid #E3E3E3;">
	<?php if(isset($this->gossip->prevID)) { ?>
		<div id="gossipNavigationLeft" style="float: left; width:45%; padding:15px;">
			<a href="index.php?option=com_gossip&id=<?php echo $this->gossip->prevID?>"><img src="/components/com_gossip/images/prev.png" style="float:left; margin-right:5px;" /><span style="float:left;font-size:15px;font-weight:bold;">Older Gossip</span></a> <br>
			<span style="float:left; color:#666; font-size:90%;"><?php echo $this->gossip->prevTitle?></span>
		</div>
		<?php
		}
	if(isset($this->gossip->nextID)) { ?>
		<div id="gossipNavigationRight" style="float: right; width:45%; padding:15px;">
			<a href="index.php?option=com_gossip&id=<?php echo $this->gossip->nextID?>"><img src="/components/com_gossip/images/next.png" style="float:right; margin-left:5px;" /><span style="float:right;font-size:15px;font-weight:bold;">Newer Gossip</span></a> <br>
			<span style="float:right; color:#666; font-size:90%;"><?php echo $this->gossip->nextTitle?></span>
		</div>
		<?php
		}
	?>
</div>
<?php
/*
?>
<!-- <div style="padding-bottom:8px; border-bottom:1px solid #ccc;"> -->
<?php
*/
?>
<br />
<?php include_once($_SERVER["DOCUMENT_ROOT"] . "/scripts/outbrain.php"); ?>
<!--</div>-->
<?php include_once($_SERVER["DOCUMENT_ROOT"] . "/scripts/crowdignite.php"); ?>

