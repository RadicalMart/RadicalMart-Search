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

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Menu\AbstractMenu;

class RadicalMart_SearchRouter extends RouterView
{
	/**
	 * Router segments.
	 *
	 * @var  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $_segments = array();

	/**
	 * Router ids.
	 *
	 * @var  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $_ids = array();

	/**
	 * Router constructor.
	 *
	 * @param   CMSApplication  $app   The application object.
	 * @param   AbstractMenu    $menu  The menu object to work with.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function __construct($app = null, $menu = null)
	{
		// Search route
		$search = new RouterViewConfiguration('search');
		$this->registerView($search);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));
		$this->attachRule(new StandardRules($this));
		$this->attachRule(new NomenuRules($this));
	}
}

/**
 * RadicalMart - Search router functions.
 *
 * @param   array &$query  An array of url arguments.
 *
 * @throws  Exception
 *
 * @return  array  The url arguments to use to assemble the subsequent URL.
 *
 * @since  __DEPLOY_VERSION__
 */
function RadicalMart_SearchBuildRoute(&$query)
{
	$app    = Factory::getApplication();
	$router = new RadicalMart_SearchRouter($app, $app->getMenu());

	return $router->build($query);
}

/**
 * Parse the segments of a url.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @throws  Exception
 *
 * @return  array  The url attributes to be used by the application.
 *
 * @since  __DEPLOY_VERSION__
 */
function RadicalMart_SearchParseRoute($segments)
{
	$app    = Factory::getApplication();
	$router = new RadicalMart_SearchRouter($app, $app->getMenu());

	return $router->parse($segments);
}