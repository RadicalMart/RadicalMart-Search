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

namespace Joomla\Component\RadicalMartSearch\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Association\AssociationExtensionHelper;
use Joomla\Component\RadicalMartSearch\Site\Helper\AssociationHelper;

class AssociationsHelper extends AssociationExtensionHelper
{
	/**
	 * The extension name.
	 *
	 * @var array $extension
	 *
	 * @since 1.0.0
	 */
	protected $extension = 'com_radicalmart_search';

	/**
	 * Has the extension association support
	 *
	 * @var     bool $associationsSupport
	 *
	 * @since   1.0.0
	 */
	protected $associationsSupport = true;

	/**
	 * Method to get the associations for a given item.
	 *
	 * @param   int     $id    ID of the item.
	 * @param   string  $view  Name of the view.
	 *
	 * @throws \Exception
	 *
	 * @return  array   Array of associations for the item.
	 *
	 * @since  1.0.0
	 */
	public function getAssociationsForItem($id = 0, $view = null): array
	{
		return AssociationHelper::getAssociations($id, $view);
	}
}