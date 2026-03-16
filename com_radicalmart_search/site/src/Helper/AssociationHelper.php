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

namespace Joomla\Component\RadicalMartSearch\Site\Helper;

defined('_JEXEC') or die;
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\LanguageHelper;

abstract class AssociationHelper
{
	/**
	 * Method to get the associations for a given item
	 *
	 * @param   int          $id      Id of the item.
	 * @param   string|null  $view    Name of the view.
	 * @param   string|null  $layout  View layout.
	 *
	 * @throws  \Exception
	 *
	 * @return  array   Array of associations for the item.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getAssociations(int $id = 0, string $view = null, string $layout = null): array
	{
		$app       = Factory::getApplication();
		$view      = ($view === null) ? $app->input->get('view') : $view;
		$languages = LanguageHelper::getContentLanguages(false, false, null, 'ordering', 'asc');

		if ($view === 'search' && count($languages) > 0)
		{
			$result = [];
			foreach ($languages as $language)
			{
				$result[$language->lang_code] = RouteHelper::getSearchRoute($language->lang_code);
			}

			return $result;
		}

		return [];
	}
}