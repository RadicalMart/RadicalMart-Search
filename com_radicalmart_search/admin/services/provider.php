<?php
/*
 * @package     RadicalMart Search Package
 * @subpackage  com_radicalmart_search
 * @version     1.0.0
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Association\AssociationExtensionInterface;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Component\RadicalMartSearch\Administrator\Extension\RadicalMartSearchComponent;
use Joomla\Component\RadicalMartSearch\Administrator\Helper\AssociationsHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class implements ServiceProviderInterface {

	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @since  1.0.0
	 */
	public function register(Container $container): void
	{
		// Register services
		$container->registerServiceProvider(new ComponentDispatcherFactory('\\Joomla\\Component\\RadicalMartSearch'));
		$container->registerServiceProvider(new MVCFactory('\\Joomla\\Component\\RadicalMartSearch'));
		$container->registerServiceProvider(new RouterFactory('\\Joomla\\Component\\RadicalMartSearch'));

		// Set component
		$container->set(
			ComponentInterface::class,
			function (Container $container) {
				$component = new RadicalMartSearchComponent($container->get(ComponentDispatcherFactoryInterface::class));

				$component->setRegistry($container->get(Registry::class));
				$component->setMVCFactory($container->get(MVCFactoryInterface::class));
				$component->setAssociationExtension($container->get(AssociationExtensionInterface::class));
				$component->setRouterFactory($container->get(RouterFactoryInterface::class));

				return $component;
			}
		);

		// Set component multilanguage associations
		$container->set(AssociationExtensionInterface::class, new AssociationsHelper);
	}
};