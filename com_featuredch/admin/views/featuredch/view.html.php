<?php
/**
 * @version     1.0.0
 * @package     com_featuredchannel
 * @copyright   OGOSense
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Alex <aaleksic@ogosense.com> - http://
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class FeaturedchViewFeaturedch extends JViewLegacy
{
	protected $state;
	protected $featuredch;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->featuredch		= $this->get('Item');
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->featuredch->id == 0);
        if (isset($this->featuredch->checked_out)) {
		    $checkedOut	= !($this->featuredch->checked_out == 0 || $this->featuredch->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
		$canDo		= FeaturedchHelper::getActions();

		JToolBarHelper::title(JText::_('Featuredch'), 'featuredch.png');

		// If not checked out, can save the featuredch.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{

			JToolBarHelper::apply('featuredch.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('featuredch.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && ($canDo->get('core.create'))){
			JToolBarHelper::custom('featuredch.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing featuredch, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::custom('featuredch.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->featuredch->id)) {
			JToolBarHelper::cancel('featuredch.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('featuredch.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}
