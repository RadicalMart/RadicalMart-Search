<?php
/*
 * @package     RadicalMart Package
 * @subpackage  plg_system_radicalmart
 * @version     1.0.0
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

namespace Joomla\Plugin\RadicalMart\Search\Extension;

\defined('_JEXEC') or die;

use Joomla\Application\ApplicationEvents;
use Joomla\Application\Event\ApplicationEvent;
use Joomla\CMS\Application\AdministratorApplication;
use Joomla\CMS\Application\ConsoleApplication;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Event\Menu\AfterGetMenuTypeOptionsEvent;
use Joomla\CMS\Event\Menu\PreprocessMenuItemsEvent;
use Joomla\CMS\Event\Model\BeforeValidateDataEvent;
use Joomla\CMS\Event\Model\NormaliseRequestDataEvent;
use Joomla\CMS\Event\Model\PrepareDataEvent;
use Joomla\CMS\Event\Model\PrepareFormEvent;
use Joomla\CMS\Event\User\AfterDeleteEvent;
use Joomla\CMS\Event\User\AfterSaveEvent;
use Joomla\CMS\Event\User\BeforeSaveEvent;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Menu\AdministratorMenuItem;
use Joomla\CMS\MVC\Factory\MVCFactoryAwareTrait;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\WebAsset\WebAssetManagerInterface;
use Joomla\Component\RadicalMart\Administrator\Helper\ParamsHelper;
use Joomla\Component\RadicalMart\Administrator\Helper\PermissionsHelper;
use Joomla\Component\RadicalMart\Administrator\Helper\PluginsHelper;
use Joomla\Component\RadicalMart\Administrator\Helper\PriceHelper;
use Joomla\Component\RadicalMart\Administrator\Helper\UserHelper;
use Joomla\Component\RadicalMart\Site\Model\CartModel;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\ParameterType;
use Joomla\Event\DispatcherInterface;
use Joomla\Event\SubscriberInterface;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\Filesystem\Path;
use Joomla\Registry\Registry;

class Search extends CMSPlugin implements SubscriberInterface
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    bool
	 *
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since  1.0.0
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onRadicalMartPrepareConfigForm'   => 'onRadicalMartPrepareConfigForm',
			'onRadicalMartPrepareConfigGroups' => 'onRadicalMartPrepareConfigGroups',
		];
	}

	/**
	 * Method to load codes config form.
	 *
	 * @param   Form         $form  The form to be altered.
	 * @param   mixed|array  $data  The associated data for the form.
	 *
	 * @throws \Exception
	 *
	 * @since 1.0.0
	 */
	public function onRadicalMartPrepareConfigForm(Form $form, mixed $data = []): void
	{
		$form->loadFile(JPATH_PLUGINS . '/radicalmart/search/forms/com_radicalmart.config.xml');
	}

	/**
	 * Method to add search group to RadicalMart config.
	 *
	 * @param   array  $groups  Current groups array
	 *
	 * @throws \Exception
	 *
	 * @since 1.0.0
	 */
	public function onRadicalMartPrepareConfigGroups(array &$groups): void
	{
		$groups['search'] = [
			'title'    => 'PLG_RADICALMART_SEARCH_CONFIG',
			'key'      => 'search',
			'sections' => [
				'global'  => [
					'title'     => 'PLG_RADICALMART_SEARCH_CONFIG_GLOBAL',
					'key'       => 'search-global',
					'type'      => 'fieldsets',
					'fieldsets' => [
						'search_global',
						'search_global_note',
					],
				],
				'seo'     => [
					'title'     => 'COM_RADICALMART_CONFIG_SEO',
					'key'       => 'search-seo',
					'type'      => 'fieldsets',
					'template'  => 'seo',
					'fieldsets' => [
						'search_seo',
					],
				],
				'display' => [
					'title'     => 'COM_RADICALMART_CONFIG_DISPLAY',
					'key'       => 'search-display',
					'type'      => 'fieldsets',
					'fieldsets' => [
						'search_display',
					],
				],
			]
		];
	}
}