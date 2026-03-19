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

namespace Joomla\Component\RadicalMartSearch\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Helper\RouteHelper as BaseHelper;
use Joomla\CMS\Language\Multilanguage;

class RouteHelper extends BaseHelper
{
	/**
	 * Fetches search route.
	 *
	 * @param   string|null  $language  The language code.
	 *
	 * @throws  \Exception
	 *
	 * @return  string  Search view link.
	 *
	 * @since  1.0.0
	 */
	public static function getSearchRoute(string $language = null): string
	{
		$link = 'index.php?option=com_radicalmart_search&view=search';

		if ($language && $language !== '*' && Multilanguage::isEnabled())
		{
			$link .= '&lang=' . $language;
		}

		return $link;
	}
}