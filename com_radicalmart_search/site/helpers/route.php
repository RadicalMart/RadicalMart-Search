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

use Joomla\CMS\Helper\RouteHelper;

class RadicalMartSearchHelperRoute extends RouteHelper
{
	/**
	 * Fetches product route.
	 *
	 * @param   string  $keyword  Search string.
	 * @param   string  $found    Found search string.
	 *
	 * @throws  Exception
	 *
	 * @return  string  Product view link.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getSearchRoute($keyword = null, $found = null)
	{
		$link = 'index.php?option=com_radicalmart_search&view=search';

		if (!empty($keyword)) $link .= '&keyword=' . $keyword;
		if (!empty($found)) $link .= '&found=' . $found;

		return $link;
	}
}