<?php defined('_JEXEC') or die('Restricted access');
//Alex :: for Joomla 3.x
$jinput = JFactory::getApplication()->input;
?>

<div id="articleArchiveTitle">All Gossip</div>

<div id="searchform">
    <form action="index.php" name="search" method="get">
        Search in <i><?php echo $this->catName ?></i>
        <input type="hidden" name="option" value="com_allgossip"/>
        <input type="hidden" name="cid" value="<?php echo $jinput->getInt('cid', '0', 'cmd'); ?>"/>
        <input type="hidden" name="Itemid" value="<?php echo $jinput->getInt('Itemid', '0', 'cmd');?>"/>
        <input class="articleSearch" type="text" name="searchword" value="<?php echo $this->search ?>"/>
        <input class="go" type="submit" value="Go"/>
    </form>
</div>


<?php
foreach ($this->gossips as $date => $gossips) {
    ?>
    <div id="allGossipDate">
        <?php echo $date ?>
    </div>
    <?php
    foreach ($gossips as $gossip) {
        ?>
        <div style="clear: both; height: 85px;">
            <div id="allGossipImg" style="float: left;">
                <a href="index.php?option=com_gossip&Itemid=<?php echo $this->itemid;?>&id=<?php echo $gossip['id'];?>">
                    <!--<img src="<?php echo 'slir/w85-h85/'.$gossip['image'] ?>" width="85" height="85" style="margin-right:15px;" /></a>-->
                    <img src="<?php echo $gossip['image'] ?>" width="85" height="85" style="margin-right:15px;" /></a>
            </div>
            <div id="allGossipContent">
                <div id="allGossipTitle">
                    <a href="index.php?option=com_gossip&Itemid=<?php echo $this->itemid;?>&id=<?php echo $gossip['id'];?>"><?php echo $gossip['title']; ?></a>
                </div>
                <div id="allGossipText">
                    <?php echo $gossip['text']; ?>
                </div>
                <div id="allGossipReadMore">
                    <a href="index.php?option=com_gossip&Itemid=<?php echo $this->itemid;?>&id=<?php echo $gossip['id'];?>">Read More</a>
                </div>
            </div>
        </div>
        <hr class="smt">
    <?php
    }
}
echo $this->pagination;
?>
