<?php
jimport( 'joomla.application.component.view');

class FeaturedchViewFeaturedcontent extends JViewLegacy
{
	function display($tpl = null, $var = null)
	{
		$model = $this->getModel();
        $this->featuredcontent = $model->getContent();
		$this->title = $model->getTitle();
		parent::display($tpl);
	}
}
