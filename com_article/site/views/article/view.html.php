<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport( 'joomla.application.module.helper' );

class ArticleViewArticle extends JViewLegacy
{
	function display($tpl = null,$seconedarg = null)
	{	
		$model = $this->getModel();
	
		$article = $model->getArticle();

		$pagination = $model->getPagination();

		$curURL = urlencode($model->curPageURL());
		$app = JFactory::getApplication('site');
		

		$document = JFactory::getDocument();
		$document->addScript( '/components/com_article/views/article/dhtml/thickbox.js' );
		$document->addScript( '/components/com_article/views/article/dhtml/thickbox.css' );

		$pathway	= $app->getPathway();
		$newpathway = $pathway->getPathway();
		$pathway->setPathway( $newpathway );
		$pathway->addItem($article->title,'');
		$document->setTitle( $article->title, 'Article' );

		$article	= $this->get('Article');
		JPluginHelper::importPlugin('content');
        $dispatcher	= JEventDispatcher::getInstance();
		$params = $app->getParams('com_content');
        $var = "com_article";
		$results = $dispatcher->trigger('onAfterDisplayTitle', array (&$article, &$params));
		$results = trim(implode("\n", $results));
        $this->vote = $results;
		
        $this->article = $article;

        $this->pagination = $pagination;
        $this->curURL = $curURL;

		$modules = JModuleHelper::getModules('');
		if (!empty($modules)) {
			foreach($modules as $module) {
				if ($module->module == 'mod_related_content') {
					$module_text = JModuleHelper::renderModule( $module );
					$article->text = str_replace('{relatedarticles}',$module_text,$article->text);
					break;
				}
			}
		}
		$modules = JModuleHelper::getModules('');
		if (!empty($modules)) {
			foreach ($modules as $module) {
				if ($module->module == 'mod_article_extras') {
					$module_text = JModuleHelper::renderModule( $module );
					$var = explode('break', $module_text);
					$this->extras->prints = $var[1];
					$this->extras->mail = $var[2];
					$this->extras->comments = $var[4];
					break;
				}
			}
		}
		$menus = JMenu::getInstance('site');
		$menu  = $menus->getActive();
		
		if (is_object( $menu ) && isset($menu->query['view']) && $menu->query['view'] == 'article' && isset($menu->query['id']) && $menu->query['id'] == $article->id) 
		{
			$menu_params=json_decode($menu->params);
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$article->title);
			}
		} else {
			$params->set('page_title',	$article->title);
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
