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

namespace Joomla\Module\RadicalMartSearch\Site\Dispatcher;

\defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Joomla\Module\RadicalMartSearch\Site\Helper\RadicalMartSearchHelper;

class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
	use HelperFactoryAwareTrait;

	/**
	 * Returns the layout data.
	 *
	 * @throws \Exception
	 *
	 * @return  array Module layout data.
	 *
	 * @since  1.0.0
	 */
	protected function getLayoutData(): array
	{
		$data = parent::getLayoutData();

		/** @var RadicalMartSearchHelper $helper */
		$helper = $this->getHelperFactory()->getHelper('RadicalMartSearchHelper');

		$data['action'] = $helper->getAction($data['params']);
		$data['form']   = $helper->getForm();

		return $data;
	}
}