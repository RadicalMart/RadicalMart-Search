<?php
/*
 * @package     RadicalMart Search Package
 * @subpackage  com_radicalmart_search
 * @version     1.0.3
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

namespace Joomla\Component\RadicalMartSearch\Site\Service;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Menu\AbstractMenu;

class Router extends RouterView
{
	/**
	 * Name of the router of the component
	 *
	 * @var    string
	 *
	 * @since  2.0.0
	 */
	protected $name = 'radicalmart_search';

	/**
	 * Router constructor.
	 *
	 * @param   SiteApplication  $app   The application object.
	 * @param   AbstractMenu     $menu  The menu object to work with.
	 *
	 * @throws \Exception
	 *
	 * @since 2.0.0
	 */
	public function __construct(SiteApplication $app, AbstractMenu $menu)
	{
		// Codes route
		$search = new RouterViewConfiguration('search');
		$this->registerView($search);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));
		$this->attachRule(new StandardRules($this));
		$this->attachRule(new NomenuRules($this));
	}
}