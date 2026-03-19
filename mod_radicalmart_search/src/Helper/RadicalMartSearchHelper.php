<?php
/*
 * @package     RadicalMart Search Package
 * @subpackage  mod_radicalmart_search
 * @version     1.0.1
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

namespace Joomla\Module\RadicalMartSearch\Site\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Router\Route;
use Joomla\Component\RadicalMart\Administrator\Helper\ParamsHelper;
use Joomla\Component\RadicalMartSearch\Site\Helper\RouteHelper;
use Joomla\Component\RadicalMartSearch\Site\Model\SearchModel;
use Joomla\Registry\Registry;

\defined('_JEXEC') or die;

class RadicalMartSearchHelper
{
	/**
	 * Search form object.
	 *
	 * @var  Form|null
	 *
	 * @since  1.0.0
	 */
	protected ?Form $_form = null;

	/**
	 * Products model object.
	 *
	 * @var  SearchModel|null
	 *
	 * @since  1.0.0
	 */
	protected ?SearchModel $_model = null;

	/**
	 * Method to get form action url.
	 *
	 * @param   Registry  $params  Module params.
	 *
	 * @throws  \Exception
	 *
	 * @return  string  The action url.
	 *
	 * @since  1.0.0
	 */
	public function getAction(Registry $params): string
	{
		if ((int) $params->get('menu_item') > 0)
		{
			$link = 'index.php?Itemid=' . (int) $params->get('menu_item');
		}
		else
		{
			$link = RouteHelper::getSearchRoute();
		}

		return Route::link('site', $link);
	}

	/**
	 * Method to get search form.
	 *
	 * @throws  \Exception
	 *
	 * @return  Form|false  The Form object or false on error.
	 *
	 * @since  1.0.0
	 */
	public function getForm(): false|Form
	{
		if ($this->_form !== null)
		{
			return $this->_form;
		}

		$model = $this->getModel();

		Form::addFormPath(JPATH_ROOT . '/components/com_radicalmart_search/forms');
		$this->_form = $model->getFilterForm();

		return $this->_form;
	}

	/**
	 * Method to get products model.
	 *
	 * @throws  \Exception
	 *
	 * @return  SearchModel  Products mode.
	 *
	 * @since  1.0.0
	 */
	protected function getModel(): SearchModel
	{
		if ($this->_model === null)
		{
			$app = Factory::getApplication();

			// Load language
			$language = $app->getLanguage();
			$language->load('com_radicalmart');
			$language->load('com_radicalmart_search');


			// Get model
			$this->_model = $app->bootComponent('com_radicalmart_search')->getMVCFactory()
				->createModel('Search', 'Site', ['ignore_request' => true]);
			$this->_model->setState('params', ParamsHelper::getComponentParams());
			$this->_model->setState('filter.published', 1);
			$this->_model->setState('filter.language', Multilanguage::isEnabled());
		}

		return $this->_model;
	}
}