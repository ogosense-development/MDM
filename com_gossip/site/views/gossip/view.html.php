<?php
/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_2
 * @license    GNU/GPL
	*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport('joomla.application.component.helper');

class GossipViewGossip extends JViewLegacy
{
	function display($tpl = null)
	{	
		$model = $this->getModel();
			
		$gossip = $model->getGossip();

		$this->gossip =	$gossip;
		$app = JFactory::getApplication('site');
		$params = $app->getParams('com_content');
		$document = JFactory::getDocument();
		$menus = JMenu::getInstance('site');
		$menu  = $menus->getActive();
		
		$article = $gossip;
		if (is_object( $menu ) && isset($menu->query['view']) && $menu->query['view'] == 'gossip' && isset($menu->query['id']) && $menu->query['id'] == $article->id) {
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$article->title);
			}
		} else { 
			$params->set('page_title',	$gossip->title);
		}		
		$document->setTitle( $params->get( 'page_title' ) );


		if ($article->metadesc) {
			$document->setDescription( $article->metadesc );
		}
		if ($article->metakey) {
			$document->setMetadata('keywords', $article->metakey);
		}

		if ($app->getCfg('MetaTitle') == '1') {
			$document->setMetadata('title', $article->title);
		}
		if ($app->getCfg('MetaAuthor') == '1') {
			$document->setMetadata('author', $article->author);
		}

		$mdata = new JRegistry($article->metadata);
		$mdata = $mdata->toArray();
		foreach ($mdata as $k => $v)
		{
			if ($v) {
				$document->setMetadata($k, $v);
			}
		}
		
		parent::display($tpl);
	}
}
