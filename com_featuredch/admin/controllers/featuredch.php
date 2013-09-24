<?php
/**
 * @version     1.0.0
 * @package     com_featuredch
 * @copyright   OGOSense
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Alex <aaleksic@ogosense.com> - http://
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Featuredch controller class.
 */
class FeaturedchControllerFeaturedch extends JControllerForm
{

    function __construct() {
        $this->view_list = 'featuredchs';
        parent::__construct();
    }

}