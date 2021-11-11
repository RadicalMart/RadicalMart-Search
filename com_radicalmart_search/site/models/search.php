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
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\MVC\Model\ListModel;

class RadicalMartSearchModelSearch extends ListModel
{
	/**
	 * Model context string.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $context = 'com_radicalmart.search';

	protected $total = null;

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

		// Set keyword filter state
		$keyword = $this->getUserStateFromRequest($this->context . '.keyword', 'keyword', '');
		$keyword = trim(str_replace(array('"', "'", '«', '»', '  '), ' ', $keyword));
		$this->setState('filter.keyword', $keyword);

		// Set found filter state
		$found = $this->getUserStateFromRequest($this->context . '.found', 'found', '');
		$this->setState('filter.found', $found);

		parent::populateState($ordering, $direction);

		// Set limit & start for query
		$this->setState('list.limit', (int) $params->get('products_limit', 9));
		$this->setState('list.start', $app->input->get('start', 0, 'uint'));
	}


	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.keyword');
		$id .= ':' . $this->getState('filter.found');
		$id .= ':' . serialize($this->getState('filter.search'));

		return parent::getStoreId($id);
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
		$query->select(array('p.id'))
			->from($db->quoteName('#__radicalmart_products', 'p'));

		$search = $this->getState('filter.search', array());
		if (empty($search) || !is_array($search)) $query->where('p.id = -1');
		else
		{
			$sql     = array();
			$columns = array('p.title');
			foreach ($columns as $column)
			{
				foreach ($search as $word)
				{
					$sql[] = $db->quoteName($column)
						. ' REGEXP ' . $db->quote('([[:<:]]|^)' . $db->escape($word) . '.*?([[:>:]]|$)');
				}
			}
			$query->where('(' . implode(' OR ', $sql) . ')');
		}

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
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		try
		{
			$app     = Factory::getApplication();
			$keyword = $this->getState('filter.keyword');
			if ($this->getState('list.start') > 0 && $this->getState('filter.found'))
			{
				$keyword = $this->getState('filter.found');
			}

			if (empty($keyword))
			{
				$this->cache[$store] = array();

				return array();
			}
			if (mb_strlen($keyword) <= 3)
			{
				$this->cache[$store] = array();

				return array();
			}

			$words = explode(' ', $keyword);
			$i     = 0;
			while (true)
			{
				if ($i === 0) $this->total = null;
				$i++;
				if (count($words) === 0) break;

				$this->setState('filter.search', $words);
				$ids = $this->_getList($this->_getListQuery());
				if (count($ids) > 0)
				{
					$this->setState('filter.found', implode(' ', $words));
					break;
				}
				else
				{
					$source = $words;
					$words  = array();
					foreach ($source as $w => $word)
					{
						if (mb_strlen($word) > 3)
						{
							$words[$w] = mb_substr($word, 0, -1);
						}
					}
				}
			}

			if (empty($ids))
			{
				$this->cache[$store] = array();

				return array();
			}

			/* @var RadicalMartModelProducts $model */
			BaseDatabaseModel::addIncludePath(JPATH_SITE . '/components/com_radicalmart/models');
			$model = BaseDatabaseModel::getInstance('Products', 'RadicalMartModel', array('ignore_request' => true));
			$model->setState('params', $this->getState('params'));
			$model->setState('filter.item_id', $ids);
			$model->setState('filter.published', 1);
			$model->setState('list.limit', $this->getState('list.limit'));
			$model->setState('list.start', $this->getState('list.start'));
			$model->set('context', $this->context);

			$items       = $model->getItems();
			$this->total = $model->getTotal();

			$this->cache[$store] = $items;
		}
		catch (\RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		return $this->cache[$store];
	}

	public function getTotal()
	{
		if ($this->total === null) $this->getItems();

		return $this->total;
	}

	public function getPagination()
	{
		$pagination = parent::getPagination();
		if ($keyword = $this->getState('filter.keyword'))
		{
			$pagination->setAdditionalUrlParam('keyword', $keyword);
		}

		if ($found = $this->getState('filter.found'))
		{
			$pagination->setAdditionalUrlParam('found', $found);
		}

		return $pagination;
	}

	/**
	 * Gets an array of objects from the results of database query.
	 *
	 * @param   string   $query       The query.
	 * @param   integer  $limitstart  Offset.
	 * @param   integer  $limit       The number of records.
	 *
	 * @throws  RuntimeException
	 *
	 * @return  object[]  An array of results.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function _getList($query, $limitstart = 0, $limit = 0)
	{
		$this->getDbo()->setQuery($query);

		return $this->getDbo()->loadColumn();
	}

	/**
	 * Gets the value of a user state variable and sets it in the session.
	 *
	 * @param   string   $key        The key of the user state variable.
	 * @param   string   $request    The name of the variable passed in a request.
	 * @param   string   $default    The default value for the variable if not found. Optional.
	 * @param   string   $type       Filter for the variable, for valid values.
	 * @param   boolean  $resetPage  If true, the limitstart in request is set to zero
	 *
	 * @throws  Exception
	 *
	 * @return  mixed  The request user state.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getUserStateFromRequest($key, $request, $default = null, $type = 'none', $resetPage = true)
	{
		$app   = Factory::getApplication();
		$new   = parent::getUserStateFromRequest($key, $request, $default, $type, $resetPage);
		$set   = $app->input->get($request, null, $type);
		$state = ($new === $set) ? $new : $set;
		$app->setUserState($key, $state);

		return $state;
	}
}