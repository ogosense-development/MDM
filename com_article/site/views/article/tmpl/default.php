<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php

	include($_SERVER['DOCUMENT_ROOT'] . "/scripts/newMail.php");
	$jinput = JFactory::getApplication()->input;
    $articleID = $jinput->getInt('id', '0', 'cmd');
?>

<div class="article-container">

	<h1 class="article-title"><?php echo $this->article->title ?> </h1>

		<?php if (strlen(str_replace(' ','',$this->article->author)) > 0)
			echo 'By <span style="text-decoration:underline;">'.$this->article->author.'</span><br>'; ?>
	</div>-->
	<?php echo $this->article->datePublished ?>
	<!-- end article title, author and date published -->
	<?php
	if ($nmFlag && $nmLoc == 'top') {
		echo "<br />";
		include_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/"  . $nmTemplate . "NMCtr.php");
	}
	?>
	<div>
		<!-- Show voting -->
		<div style="float:left; width:100%;color:#656565;">
			<span style="font-size:80%;float:left;margin-top:3px;">RATE:&nbsp;</span> <?php echo $this->vote; ?>
		</div>
		<!-- end voting -->		
	</div>
	<!-- Show sharing links -->
	<table cellpadding="0" cellspacing="0" style="width:100%; border:0;">
		<tr>
			<td style="padding-left:0px;">
				<?php include_once($_SERVER["DOCUMENT_ROOT"] . "/scripts/addThisJS.php"); ?>
                <?php include_once($_SERVER["DOCUMENT_ROOT"] . "/scripts/getMySEFURL.php");	?>
                <div style="height:20px;margin:5px 0;overflow:hidden;">		
					<?php include($_SERVER["DOCUMENT_ROOT"] . "/scripts/addThisCodeArticles.php"); ?>
				</div>
			</td>
			<td style="padding-left:0px;padding-right:0px;">
			<?php echo $this->extras->prints; ?>
			</td>
            <td style="padding-left:0px;padding-right:0px;">
			<?php echo $this->extras->mail; ?>
			</td>
            <td style="padding-left:0px;padding-right:0px;">
			<a style="vertical-align:middle;" href="#ccomment-article-<?php echo JFactory::getApplication()->input->get('id',0); ?>"><img style="vertical-align:middle;" src="/images/comments/comments.png" /></a>
			</td>
			</tr>
	</table>
	</div>
	
	<div class="sponsored-ads-article" style="color:#656565; background:#efebee; margin-bottom:20px; padding:5px; width:542px;">
		<?php include_once($_SERVER["DOCUMENT_ROOT"] . "/scripts/sponsoredTags.php"); ?>
	</div>
	
	
	<?php
	
	echo '<div style=" color:#333; line-height:16px;"><span id="intelliTXT">' . "\r\n";
	if ($nmFlag && $nmLoc == 'mid') { // if nmFlag and mid
		$tmpArtText = $this->article->text;
		$tmpArtTextAr = explode('</p>', $tmpArtText);
		$tmpArtTextArLen = count($tmpArtTextAr) - 1;
		$tmpArtTextArLenHalf = floor($tmpArtTextArLen/2);
		for ($z=0; $z < $tmpArtTextArLen; $z++){

			$tmpArtTextAr[$z] = preg_replace('/<img /', '<img class="article-image"', $tmpArtTextAr[$z],1);

			echo $tmpArtTextAr[$z] . "</p>";
			if ($z == $tmpArtTextArLenHalf) {
				include_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/"  . $nmTemplate . "NMCtr.php");
			}
		}
	} 
	else 
	{
		$this->article->text = preg_replace('/<img /', '<img class="article-image"', $this->article->text,1);
		echo $this->article->text . "\r\n";
	}
	echo " </span> </div>\r\n";
	?>
	<?php
	if ($nmFlag && $nmLoc == 'bot') {
		include_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/"  . $nmTemplate . "NMCtr.php");
	}
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
	<table cellpadding="0" cellspacing="0" style="width:100%; border:0;"> 
		<tr>
			<td style="padding-left:0px;width:84%;">
				<?php include_once($_SERVER["DOCUMENT_ROOT"] . "/scripts/addThisJS.php"); ?>
                <?php include_once($_SERVER["DOCUMENT_ROOT"] . "/scripts/getMySEFURL.php");	?>
                <div style="height:20px;margin:5px 0;overflow:hidden;">		
					<?php include($_SERVER["DOCUMENT_ROOT"] . "/scripts/addThisCodeArticles.php");
						  include_once($_SERVER["DOCUMENT_ROOT"] . "/scripts/addThisCodeInit.php"); 
					?>
				</div>
			</td>
			<td style="padding-left:0px;padding-right:0px;">
			<?php echo $this->extras->prints; ?>
			</td>
            <td style="padding-left:0px;padding-right:0px;">
			<?php echo $this->extras->mail; ?>
			</td>
            <td style="padding-left:0px;padding-right:0px;">
			<a style="vertical-align:middle;" href="#ccomment-article-<?php echo JFactory::getApplication()->input->get('id',0); ?>"><img style="vertical-align:middle;" src="/images/comments/comments.png" /></a>
			</td>
			</tr>
	</table>
	<!-- end Sharing links -->
	<div>
		<?php echo $this->article->dateCreated?>
		<!-- Show pagination -->
		<div class="article-paginations">
			<div style="float:left; color:#666;">
				<?php echo $this->pagination ?>
			</div>
			<div id="extras_commentSee" style="float: right;">	
				<div>
					<?php echo $this->extras->comments; ?>
				</div>
		    </div>
		</div>
	<div style="clear: both;"> </div>
	<!-- end pagination -->

</div>
