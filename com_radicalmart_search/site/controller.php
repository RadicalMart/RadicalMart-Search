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
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

class RadicalMartSearchController extends BaseController
{
	/**
	 * Response code.
	 *
	 * @var  integer
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $code = null;

	/**
	 * The default view.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $default_view = 'search';

	/**
	 * Typical view method for MVC based architecture.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types.
	 *
	 * @throws  Exception
	 *
	 * @return  BaseController  A BaseController object to support chaining.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function display($cachable = false, $urlparams = array())
	{
		/* @var SiteApplication $app */
		$app = Factory::getApplication();

		// Duplicates protection
		if (ComponentHelper::getParams('com_radicalmart')->get('duplicates_protection', 1)
			&& $this->input->getMethod() !== 'POST')
		{
			$view    = $this->input->getCmd('view', $this->default_view);
			$keyword = $this->input->getString('keyword');
			$found   = $this->input->getString('found');
			if (empty($this->input->getInt('start'))) $found = '';
			$link = false;

			if ($view == 'search')
			{
				$link = RadicalMartSearchHelperRoute::getSearchRoute($keyword, $found);
			}

			if ($link)
			{
				// Add start to canonical
				$uri       = Uri::getInstance();
				$canonical = Uri::getInstance(Route::link('site', $link, false));
				if (!empty($uri->getVar('start')))
				{
					$canonical->setVar('start', $uri->getVar('start'));
				}

				$root      = $uri->toString(array('scheme', 'host', 'port'));
				$canonical = $canonical->toString();
				$current   = $_SERVER['REQUEST_URI'];
				if ($current !== $canonical)
				{
					Factory::getDocument()->addCustomTag('<link href="' . $root . $canonical . '" rel="canonical"/>');

					$redirect = Uri::getInstance(Route::_($link));
					$skipKeys = array('start', 'debug', 'filter', 'tmpl');

					foreach ($uri->getQuery(true) as $key => $value)
					{
						$value = $this->cleanUriQueryValue($value);
						if (!empty($value) && (preg_match('#^utm_#', $key) || in_array($key, $skipKeys)))
						{
							$redirect->setVar($key, $value);
						}
					}
					$redirect = $redirect->toString(array('path', 'query', 'fragment'));

					if (urldecode($current) != urldecode($redirect))
					{
						$app->redirect($redirect, 301);
					}
				}
			}
		}

		return parent::display(false, array());
	}

	/**
	 * Method to clean query value.
	 *
	 * @param   string|array  $value  Query value.
	 *
	 * @return array|string Clean query value.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function cleanUriQueryValue($value = null)
	{
		if (is_array($value))
		{
			foreach ($value as $v => $val)
			{
				$val = $this->cleanUriQueryValue($val);
				if (!empty($val)) $value[$v] = $val;
				else unset($value[$v]);
			}
		}
		else $value = trim($value);

		return $value;
	}
}