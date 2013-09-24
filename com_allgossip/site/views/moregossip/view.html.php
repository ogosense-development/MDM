<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class AllgossipViewmoregossip extends JViewLegacy
{
    function display($tpl = null)
    {
        $model = $this->getModel();

        $search = JFactory::getApplication()->input->get('searchword');
        $model->setSearch($search);

        $gossips = $model->getArticleList($search);
        $category = $model->getCatName();
        $itemid = $model->getItemid();
        $search = $model->getSearch();
        $pagination = $model->getPagination();

        $this->gossips = $gossips;
        $this->pagination = $pagination;
        $this->search = $search;
        $this->catName = $category;
        $this->itemid = $itemid;

        parent::display($tpl);
    }
}
