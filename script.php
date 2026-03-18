<?php
/*
 * @package     RadicalMart Search Package
 * @subpackage  pkg_radicalmart_search
 * @version     1.0.0
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Application\AdministratorApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;
use Joomla\Database\DatabaseDriver;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Registry\Registry;

return new class () implements ServiceProviderInterface {
	public function register(Container $container): void
	{
		$container->set(InstallerScriptInterface::class,
			new class ($container->get(AdministratorApplication::class)) implements InstallerScriptInterface {
				/**
				 * The application object
				 *
				 * @var  AdministratorApplication
				 *
				 * @since  1.0.0
				 */
				protected AdministratorApplication $app;

				/**
				 * The Database object.
				 *
				 * @var   DatabaseDriver
				 *
				 * @since  1.0.0
				 */
				protected DatabaseDriver $db;

				/**
				 * Minimum Joomla version required to install the extension.
				 *
				 * @var  string
				 *
				 * @since  1.0.0
				 */
				protected string $minimumJoomla = '5.3';

				/**
				 * Minimum PHP version required to install the extension.
				 *
				 * @var  string
				 *
				 * @since  1.0.0
				 */
				protected string $minimumPhp = '8.2';

				/**
				 * Minimum MySQL version required to install the extension.
				 *
				 * @var  string
				 *
				 * @since  1.0.0
				 */
				protected string $minimumMySQL = '8.0.21';

				/**
				 * Minimum MariaDb version required to install the extension.
				 *
				 * @var  string
				 *
				 * @since  1.0.0
				 */
				protected string $minimumMariaDb = '10.4.1';


				/**
				 * Minimum RadicalMart version required to install the extension.
				 *
				 * @var  string
				 *
				 * @since  1.0.0
				 */
				protected string $minimumRadicalMart = '3.0.0';

				/**
				 * Language constant for errors.
				 *
				 * @var string
				 *
				 * @since 1.0.0
				 */
				protected string $constant = "PKG_RADICALMART_SEARCH";

				/**
				 * Update methods.
				 *
				 * @var  array
				 *
				 * @since  1.0.0
				 */
				protected array $updateMethods = [];

				/**
				 * Constructor.
				 *
				 * @param   AdministratorApplication  $app  The application object.
				 *
				 * @since 1.0.0
				 */
				public function __construct(AdministratorApplication $app)
				{
					$this->app = $app;
					$this->db  = Factory::getContainer()->get('DatabaseDriver');
				}

				/**
				 * Function called after the extension is installed.
				 *
				 * @param   InstallerAdapter  $adapter  The adapter calling this method
				 *
				 * @return  boolean  True on success
				 *
				 * @since   1.0.0
				 */
				public function install(InstallerAdapter $adapter): bool
				{
					return true;
				}

				/**
				 * Function called after the extension is updated.
				 *
				 * @param   InstallerAdapter  $adapter  The adapter calling this method
				 *
				 * @return  boolean  True on success
				 *
				 * @since   1.0.0
				 */
				public function update(InstallerAdapter $adapter): bool
				{
					// Refresh media version
					(new Version())->refreshMediaVersion();

					return true;
				}

				/**
				 * Function called after the extension is uninstalled.
				 *
				 * @param   InstallerAdapter  $adapter  The adapter calling this method
				 *
				 * @return  boolean  True on success
				 *
				 * @since   1.0.0
				 */
				public function uninstall(InstallerAdapter $adapter): bool
				{
					return true;
				}

				/**
				 * Function called before extension installation/update/removal procedure commences.
				 *
				 * @param   string            $type     The type of change (install or discover_install, update, uninstall)
				 * @param   InstallerAdapter  $adapter  The adapter calling this method
				 *
				 * @return  boolean  True on success
				 *
				 * @since   1.0.0
				 */
				public function preflight(string $type, InstallerAdapter $adapter): bool
				{
					// Check compatible
					if (!$this->checkCompatible())
					{
						return false;
					}

					// Check RadicalMart version
					if (!$this->checkRadicalMartVersion())
					{
						return false;
					}

					return true;
				}

				/**
				 * Function called after extension installation/update/removal procedure commences.
				 *
				 * @param   string            $type     The type of change (install or discover_install, update, uninstall)
				 * @param   InstallerAdapter  $adapter  The adapter calling this method
				 *
				 * @return  boolean  True on success
				 *
				 * @since   1.0.0
				 */
				public function postflight(string $type, InstallerAdapter $adapter): bool
				{
					// Run updates script
					if ($type === 'update')
					{
						foreach ($this->updateMethods as $method)
						{
							if (method_exists($this, $method))
							{
								$this->$method($adapter);
							}
						}
					}

					return true;
				}

				/**
				 * Method to check compatible.
				 *
				 * @throws  \Exception
				 *
				 * @return  bool True on success, False on failure.
				 *
				 * @since  1.0.0
				 */
				protected function checkCompatible(): bool
				{
					$app = Factory::getApplication();

					// Check joomla version
					if (!(new Version())->isCompatible($this->minimumJoomla))
					{
						$app->enqueueMessage(Text::sprintf($this->constant . '_ERROR_COMPATIBLE_JOOMLA', $this->minimumJoomla),
							'error');

						return false;
					}

					// Check PHP
					if (!(version_compare(PHP_VERSION, $this->minimumPhp) >= 0))
					{
						$app->enqueueMessage(Text::sprintf($this->constant . '_ERROR_COMPATIBLE_PHP', $this->minimumPhp),
							'error');

						return false;
					}

					// Check database version
					$db            = $this->db;
					$serverType    = $db->getServerType();
					$serverVersion = $db->getVersion();
					if ($serverType == 'mysql' && stripos($serverVersion, 'mariadb') !== false)
					{
						$serverVersion = preg_replace('/^5\.5\.5-/', '', $serverVersion);

						if (!(version_compare($serverVersion, $this->minimumMariaDb) >= 0))
						{
							$app->enqueueMessage(Text::sprintf($this->constant . '_ERROR_COMPATIBLE_DATABASE',
								$this->minimumMySQL, $this->minimumMariaDb), 'error');

							return false;
						}
					}

					if ($serverType == 'mysql' && !(version_compare($serverVersion, $this->minimumMySQL) >= 0))
					{
						$app->enqueueMessage(Text::sprintf($this->constant . '_ERROR_COMPATIBLE_DATABASE',
							$this->minimumMySQL, $this->minimumMariaDb), 'error');

						return false;
					}

					return true;
				}

				/**
				 * Method to check RadicalMart version compatible.
				 *
				 * @throws  \Exception
				 *
				 * @return  bool True on success, False on failure.
				 *
				 * @since  1.0.0
				 */
				protected function checkRadicalMartVersion(): bool
				{
					// Get current version
					$db    = $this->db;
					$query = $db->createQuery()
						->select('manifest_cache')
						->from($db->quoteName('#__extensions'))
						->where($db->quoteName('element') . ' = ' . $db->quote('com_radicalmart'));

					$radicalmartVersion = (new Registry($db->setQuery($query)->loadResult()))->get('version');
					if (empty($radicalmartVersion))
					{
						return true;
					}

					if (!(version_compare($radicalmartVersion, $this->minimumRadicalMart) >= 0))
					{
						$app = Factory::getApplication();
						$app->enqueueMessage(Text::sprintf('PKG_RADICALMART_SEARCH_ERROR_COMPATIBLE_RADICALMART',
							$this->minimumRadicalMart), 'error');

						return false;
					}

					return true;
				}
			});
	}
};