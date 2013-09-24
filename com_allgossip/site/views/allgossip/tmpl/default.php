<?php defined('_JEXEC') or die('Restricted access');
//Alex :: for Joomla 3.x
$jinput = JFactory::getApplication()->input;
//die('<pre>'.print_r($this->itemid, true).'</pre>');
?>


<table>
    <tr>
        <td colspan="4">
            <h1>Gossip Wire</h1>
        </td>
    </tr>
<?php
	for($i = 0; $i < 4; $i++) {
    ?>
        <tr style="width: 640px; height: 180px;">
            <?php
            for($j = 0; $j < 4; $j++) {?>
                <td style="width: 160px; height: 200px;">
                    <a href="index.php?option=com_gossip&Itemid=<?php echo $this->itemid;?>&id=<?php echo $this->gossips[$i*4+$j]['id'];?>">
						<img src="<?php echo $this->gossips[$i*4+$j]['image']; ?>" width="160" height="160" />
					</a>
                    <a href="index.php?option=com_gossip&Itemid=<?php echo $this->itemid;?>&id=<?php echo $this->gossips[$i*4+$j]['id'];?>">
						<span style="width: 160px; height: 20px; position: relative">
							<?php echo $this->gossips[$i*4+$j]['title']; ?>
						</span>
					</a>
                </td>
            <?php
                if($i==1 && $j==3)
                {
                ?>
                    </tr>
                    <tr>
                        <td colspan="4">
							<a class="modal" title="protostar" rel="{handler: 'iframe', size: {x: 320, y: 515}}" name="Got Mail" href="/scripts/gotMail.php?channel=gossip&height=440&width=283&keepThis=true&TB_iframe=true">
								<img src="components/com_allgossip/views/allgossip/tmpl/the-gossip-wire.jpg" />
							</a>
                        </td>
                <?php
                }
            }?>
        </tr>
    <?php
	}
?>
    <tr>
        <td colspan="4">
            <h2>WANT MORE? <a href="index.php?option=com_allgossip&Itemid=<?php echo $this->itemid;?>&lang=en&view=moregossip">Click Here For More Stories</a></h2>
        </td>
    </tr>
</table>
