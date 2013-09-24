<?php
/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_1#Creating_the_Entry_Point
 * @license    GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');


class ArticleController extends JControllerLegacy
{
	/**
	 * Method to display the view
	 *
	 * @access    public
	 */
	function display($tmpl =null, $secarg =null)
	{
		 
		parent::display();
	}

}