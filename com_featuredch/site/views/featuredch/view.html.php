<?php


jimport( 'joomla.application.component.view');


class FeaturedchViewFeaturedch extends JViewLegacy
{
	function display($tpl = null)
	{
		$model = $this->getModel();
		$menu = JMenu::getInstance('site');
		$item = $menu->getActive();

        $this->featuredch = $model->getContent();

        $document = JFactory::getDocument();
		$document->setTitle( $item->title );
        
		parent::display($tpl);
		
		
	}
}
?>
