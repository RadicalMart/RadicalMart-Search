<?php
/*
 * @package     RadicalMart Search Package
 * @subpackage  com_radicalmart_search
 * @version     1.0.1
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

namespace Joomla\Component\RadicalMartSearch\Site\View\Search;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Menu\MenuItem;
use Joomla\CMS\Router\Route;
use Joomla\Component\RadicalMart\Site\Helper\ShortcodesHelper;
use Joomla\Component\RadicalMart\Site\View\ListView;
use Joomla\Component\RadicalMartSearch\Site\Helper\RouteHelper;
use Joomla\Component\RadicalMartSearch\Site\Model\SearchModel;

class HtmlView extends ListView
{
	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse.
	 *
	 * @throws  \Exception
	 *
	 * @since  2.0.0
	 */
	public function display($tpl = null): void
	{
		/** @var SearchModel $model */
		$model            = $this->getModel();
		$this->state      = $model->getState();
		$this->items      = $model->getItems();
		$this->pagination = $model->getPagination();

		// Check model errors
		$this->_checkModelErrors();

		$this->link = Route::link('site', RouteHelper::getSearchRoute(), false);

		// Set menu properties
		$this->_setMenu(['option' => 'com_radicalmart_search', 'view' => 'search']);

		$this->filterForm    = $model->getFilterForm();
		$this->activeFilters = $model->getActiveFilters();
		if (!empty($this->state->get('filter.keyword')))
		{
			$this->filterForm->setValue('keyword', '', $this->state->get('filter.keyword'));
		}

		// Set params
		$this->params = $this->state->get('params');
		if ($this->menuCurrent)
		{
			$this->params->merge($this->menu->getParams());
			if (!empty($this->menu->query['layout']))
			{
				$this->params->set('search_layout', $this->menu->query['layout']);
			}
		}
		if (!$this->menu)
		{
			$this->menu        = new MenuItem();
			$this->menu->title = Text::_('COM_RADICALMART_SEARCH');
			$this->menu->query = [];

			if (empty($this->params->get('seo_search_title')))
			{
				$this->params->set('seo_search_title', Text::_('COM_RADICALMART_SEARCH'));
			}
			if (empty($this->params->get('seo_search_h1')))
			{
				$this->params->set('seo_search_h1', Text::_('COM_RADICALMART_SEARCH'));
			}
		}
		ShortcodesHelper::replaceInRegistry($this->params);

		// Set layout
		$this->setLayout($this->params->get('search_layout', 'default'));

		// Prepare the pagination
		$this->_preparePagination();

		// Prepare the document
		$this->_prepareDocument();

		// Set meta to head
		$this->_setMetadata(Text::_('COM_RADICALMART_SEARCH'), 'seo_search', true);

		parent::display($tpl);
	}

	/**
	 * Prepare the document.
	 *
	 * @throws  \Exception
	 *
	 * @since  2.0.0
	 */
	protected function _prepareDocument(): void
	{
		// Add checkout pathway item if no current menu
		if ($this->menu && !$this->menuCurrent)
		{
			/** @var SiteApplication $app */
			$app = Factory::getApplication();
			$app->getPathway()->addItem(Text::_('COM_RADICALMART_SEARCH'));
		}
	}

	/**
	 * Method to set pagination object to list view.
	 *
	 * @param   array  $urlParams  Advanced url params.
	 *
	 * @throws \Exception
	 *
	 * @since  3.0.0
	 */
	protected function _preparePagination(array $urlParams = []): void
	{
		if (!$this->pagination)
		{
			return;
		}

		parent::_preparePagination($urlParams);

		foreach (['keyword', 'found'] as $key)
		{
			$value = $this->state->get('filter.' . $key);
			if (empty($value))
			{
				continue;
			}

			$this->pagination->setAdditionalUrlParam($key, $value);
		}
	}
}