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

namespace Joomla\Component\RadicalMartSearch\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Joomlaupdate\Api\Controller\BaseController;
use Joomla\Component\RadicalMart\Administrator\Helper\ParamsHelper;
use Joomla\Component\RadicalMart\Administrator\Traits\Controller\JsonTrait;
use Joomla\Component\RadicalMartSearch\Site\Helper\RouteHelper;
use Joomla\Component\RadicalMartSearch\Site\Model\SearchModel;

class SearchController extends BaseController
{
	use JsonTrait;

	/**
	 * Method to find items.
	 *
	 * @throws  \Exception
	 *
	 * @return  boolean True on success, False on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function find(): bool
	{
		$keyword = trim($this->input->get('keyword', '', 'text'));

		$search_length = (int) ParamsHelper::getComponentParams()->get('search_length', 3);
		if (mb_strlen($keyword) < $search_length)
		{
			$this->code    = 404;
			$this->message = (empty($keyword) ? Text::_('COM_RADICALMART_SEARCH_ERROR_EMPTY_KEYWORD')
				: Text::sprintf('COM_RADICALMART_SEARCH_ERROR_KEYWORD_LENGTH', $search_length));

			return $this->setJsonResponse();
		}

		/* @var SearchModel $model */
		$model = $this->getModel('Search', 'Site', ['ignore_request' => false]);
		$items = $model->getItems();
		$link  = Route::link('site', RouteHelper::getSearchRoute($keyword), false);

		return $this->setJsonResponse([
			'items' => $items,
			'link'  => $link,
			'html'  => LayoutHelper::render('components.radicalmart_search.field.search.ajax.result',
				['keyword' => $keyword, 'items' => $items, 'link' => $link])
		]);
	}
}