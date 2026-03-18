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

namespace Joomla\Component\RadicalMartSearch\Site\WebAsset\AssetItem;

use Joomla\CMS\Document\Document;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\WebAsset\WebAssetAttachBehaviorInterface;
use Joomla\CMS\WebAsset\WebAssetItem;
use Joomla\Component\RadicalMart\Administrator\Helper\ParamsHelper;

class SearchAjaxField extends WebAssetItem implements WebAssetAttachBehaviorInterface
{
	/**
	 * Method called when asset attached to the Document.
	 *
	 * @param   Document  $doc  Active document
	 *
	 * @throws \Exception
	 *
	 * @since  1.0.0
	 */
	public function onAttachCallback(Document $doc): void
	{
		$key = 'com_radicalmart_search.ajax';
		if (!empty($doc->getScriptOptions($key)))
		{
			return;
		}

		$app = Factory::getApplication();
		$app->getLanguage()->load('com_radicalmart');

		$doc->addScriptOptions($key, [
			'controller'    => Route::link('site', 'index.php?option=com_radicalmart_search', false),
			'search_length' => (int) ParamsHelper::getComponentParams()->get('search_length', 3),
		]);
	}
}