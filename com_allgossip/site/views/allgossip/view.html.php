<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class AllgossipViewallgossip extends JViewLegacy
{
	function display($tpl = null)
	{	
		$model = $this->getModel();
			
		$gossips = $model->getArticleList();
		$itemid = $model->getItemid();

		$this->gossips = $gossips;
		$this->itemid = $itemid;
		
		parent::display($tpl);
	}
}
