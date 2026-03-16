<?php
/*
 * @package     RadicalMart Search Package
 * @subpackage  com_radicalmart_search
 * @version     __DEPLOY_VERSION__
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

namespace Joomla\Component\RadicalMartSearch\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\Component\RadicalMart\Site\Model\ProductsModel;
use Joomla\Component\RadicalMart\Site\Traits\Model\CategoryItemsTrait;
use Joomla\Component\RadicalMart\Site\Traits\Model\ProductsFiltersTrait;

class SearchModel extends ProductsModel
{
	use CategoryItemsTrait;
	use ProductsFiltersTrait;

	/**
	 * Model context string.
	 *
	 * @var  string
	 *
	 * @since  1.0.0
	 */
	protected $context = 'com_radicalmart_search.search';

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @throws  \Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function populateState($ordering = null, $direction = null): void
	{
		/** @var SiteApplication $app */
		$app   = Factory::getApplication();
		$input = $app->getInput();


		// Set request states
		$this->setState('filter.keyword', $input->getString('keyword', ''));
		$this->setState('filter.found', $input->getString('found', ''));

		// List state information
		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get a DatabaseQuery object for retrieving the data set from a database.
	 *
	 * @throws  \Exception
	 *
	 * @return  array  A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getProductsIds(array $keywords = []): array
	{
		if (count($keywords) === 0)
		{
			return [];
		}

		// Create a new query object.
		$db    = $this->getDatabase();
		$query = $db->createQuery();
		$query->select('p.id')
			->from($db->quoteName('#__radicalmart_products', 'p'));

		$sql     = [];
		$columns = ['p.title', 'p.code', 'p.search_text'];
		foreach ($columns as $column)
		{
			foreach ($keywords as $word)
			{
				$sql[] = $db->quoteName($column)
					. ' REGEXP ' . $db->quote('([[:<:]]|^)' . $db->escape($word) . '.*?([[:>:]]|$)');
			}
		}
		$query->where('(' . implode(' OR ', $sql) . ')');

		// Get ids
		$result = $db->setQuery($query)->loadColumn();
		$db->disconnect();

		return (!empty($result)) ? $result : [];
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @throws  \Exception
	 *
	 * @return  mixed  An array of data items on success, False on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getItems(): mixed
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
			$keyword = $this->getState('filter.keyword');
			if ($this->getState('list.start') > 0 && $this->getState('filter.found'))
			{
				$keyword = $this->getState('filter.found');
			}

			if (empty($keyword))
			{
				$this->cache[$store] = [];

				return [];
			}
			$keyword = trim(str_replace(['"', "'", '«', '»', '  '], ' ', $keyword));
			$this->setState('filter.keyword', $keyword);

			if (mb_strlen($keyword) < 3)
			{
				$this->cache[$store] = [];

				return [];
			}

			$words = explode(' ', $keyword);
			$i     = 0;
			$max   = 5;
			$ids   = [];
			while (true)
			{
				if ($i === 0)
				{
					$this->total = null;
				}

				$i++;

				if (count($words) === 0)
				{
					break;
				}

				$this->setState('filter.keywords', $words);
				$ids = $this->getProductsIds($words);
				if (count($ids) > 0)
				{
					break;
				}

				if ($i >= $max)
				{
					break;
				}

				$source = $words;
				$words  = [];
				foreach ($source as $w => $word)
				{
					if (mb_strlen($word) > 3)
					{
						$words[$w] = mb_substr($word, 0, -1);
					}
				}
			}

			if (count($ids) === 0)
			{
				$this->cache[$store] = [];

				return [];
			}

			$this->setState('filter.found', implode(' ', $words));
			$this->setState('filter.item_id', $ids);
			$this->setState('category.id', 1);

			return parent::getItems();
		}
		catch (\RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return [];
		}
	}
}