<?php
/**
 * @version     1.0.0
 * @package     com_featuredogo
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Alex <abjelosevic@ogosense.com> - http://www.ogosense.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Featuredogo controller class.
 */
class FeaturedogoControllerFeaturedogo extends JControllerForm
{

    function __construct() {
        $this->view_list = 'featuredogos';
        parent::__construct();
    }

}