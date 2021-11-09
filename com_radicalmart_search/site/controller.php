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

use Joomla\CMS\MVC\Controller\BaseController;

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
}