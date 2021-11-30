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
use Joomla\CMS\Input\Json;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

class RadicalMartSearchControllerSearch extends BaseController
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
	 * Method to find items.
	 *
	 * @throws  Exception
	 *
	 * @return  boolean True on success, False on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function find()
	{
		$keyword = trim($this->input->get('keyword', '', 'text'));

		if (mb_strlen($keyword) < 3)
		{
			$this->code    = 404;
			$this->message = Text::_((empty($keyword) ? 'COM_RADICALMART_SEARCH_ERROR_EMPTY_KEYWORD'
				: 'COM_RADICALMART_SEARCH_ERROR_KEYWORD_LENGTH'));

			return $this->setJsonResponse();
		}
		/* @var RadicalMartSearchModelSearch $model */
		$model = $this->getModel('Search', 'RadicalMartSearchModel', array('ignore_request' => false));
		$items = $model->getItems();
		$link  = Route::link('site',
			RadicalMartSearchHelperRoute::getSearchRoute($keyword, false));

		return $this->setJsonResponse(
			array(
				'items' => $items,
				'link'  => $link,
				'html'  => LayoutHelper::render('components.radicalmart_search.field.ajax-search.result',
					array('keyword' => $keyword, 'items' => $items, 'link' => $link))
			)
		);
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name.
	 * @param   string  $prefix  The class prefix.
	 * @param   array   $config  The array of possible config values.
	 *
	 * @return  BaseDatabaseModel  A model object.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getModel($name = 'Search', $prefix = 'RadicalMartSearchModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Method to set json response.
	 *
	 * @param   mixed  $response  Response data.
	 *
	 * @throws  Exception
	 *
	 * @return  boolean True on success, False on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function setJsonResponse($response = null)
	{
		$message = (is_array($this->message)) ? implode(PHP_EOL, $this->message) : $this->message;
		$code    = (!empty($this->code)) ? $this->code : 200;

		header('Content-Type: application/json');
		echo new JsonResponse($response, $message, ($code !== 200));
		Factory::getApplication()->close($code);

		return ($code === 200);
	}
}