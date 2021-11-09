<?php
/*
 * @package     RadicalMart Search Package
 * @subpackage  com_radicalmart_search
 * @version     __DEPLOY_VERSION__
 * @author      Delo Design - delo-design.ru
 * @copyright   Copyright (c) 2021 Delo Design. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://delo-design.ru/
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

class RadicalMartSearchModelSearch extends ListModel
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @throws  Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		/* @var SiteApplication $app */
		$app = Factory::getApplication('site');

		// Set params state
		$params = ComponentHelper::getParams('com_radicalmart');
		$params->merge($app->getParams());
		$this->setState('params', $params);

		parent::populateState($ordering, $direction);
	}


	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @throws  Exception
	 *
	 * @return  JDatabaseQuery  A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @throws  Exception
	 *
	 * @return  mixed  An array of data items on success, False on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getItems()
	{
		return array();
	}
}