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

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Menu\MenuItem;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\Registry\Registry;

class RadicalMartSearchViewSearch extends HtmlView
{
	/**
	 * Application params.
	 *
	 * @var  Registry;
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $params;

	/**
	 * An array of items.
	 *
	 * @var  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $items;

	/**
	 * Pagination object.
	 *
	 * @var  Pagination
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $pagination;

	/**
	 * This view canonical link.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $link;

	/**
	 * Form object for search filters.
	 *
	 * @var  Form
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $filterForm;

	/**
	 * The active search filters.
	 *
	 * @var  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $activeFilters;

	/**
	 * Active menu item.
	 *
	 * @var  MenuItem
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $menu;

	/**
	 * Is current menu item.
	 *
	 * @var  bool
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $menuCurrent;

	/**
	 * RadicalMart mode.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $mode;

	/**
	 * Page class suffix from params.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $pageclass_sfx;

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse.
	 *
	 * @throws  Exception
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		$this->state         = $this->get('State');
		$this->params        = $this->state->get('params');
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->link          = Route::link('site', RadicalMartSearchHelperRoute::getSearchRoute());
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Check for errors
		if (count($errors = $this->get('Errors'))) throw new Exception(implode('\n', $errors), 500);

		// Set menu
		$this->menu        = Factory::getApplication()->getMenu()->getActive();
		$this->menuCurrent = ($this->menu
			&& isset($this->menu->query['option'], $this->menu->query['view'])
			&& $this->menu->query['option'] === 'com_radicalmart_search'
			&& $this->menu->query['view'] === 'product');

		// Set params
		if ($this->menuCurrent)
		{
			$this->params->merge($this->menu->getParams());
			if (!empty($this->menu->query['layout']))
			{
				$this->params->set('search_layout', $this->menu->query['layout']);
			}
		}

		$this->params = RadicalMartHelperSEO::replaceParamsShortcodes($this->params, $this);
		if (!$this->menu)
		{
			$this->menu        = new stdClass();
			$this->menu->title = Text::_('COM_RADICALMART_FRONT_PRODUCT');
			$this->menu->query = array();
			if (empty($this->params->get('seo_search_title')))
			{
				$this->params->set('seo_search_title', Text::_('COM_RADICALMART_SEARCH_TITLE'));
			}
			if (empty($this->params->get('seo_search_h1')))
			{
				$this->params->set('seo_search_h1', Text::_('COM_RADICALMART_SEARCH_TITLE'));
			}
		}

		// Set layout
		$this->setLayout($this->params->get('search_layout', 'default'));

		// Escape strings for html output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

		// Prepare the document
		$this->_prepareDocument();

		return parent::display($tpl);
	}

	/**
	 * Prepare the document.
	 *
	 * @throws  Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function _prepareDocument()
	{
		// Add checkout pathway item if no current menu
		if ($this->menu && !$this->menuCurrent)
		{
			Factory::getApplication()->getPathway()->addItem(Text::_('COM_RADICALMART_SEARCH_PATHWAY'));
		}

		// Set meta
		$this->document->setTitle(Text::_('COM_RADICALMART_SEARCH_TITLE'));
		$this->document->setMetadata('robots', 'noindex, nofollow');

		// Set microdata
		RadicalMartHelperSEO::setMicrodata($this);
	}
}