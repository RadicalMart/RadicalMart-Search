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

JLoader::register('RadicalMartSearchHelperRoute', JPATH_SITE . '/components/com_radicalmart_search/helpers/route.php');
JLoader::register('RadicalMartHelperRoute', JPATH_SITE . '/components/com_radicalmart/helpers/route.php');
JLoader::register('RadicalMartHelperSEO', JPATH_SITE . '/components/com_radicalmart/helpers/seo.php');
JLoader::register('RadicalMartHelperMedia', JPATH_SITE . '/components/com_radicalmart/helpers/media.php');
JLoader::register('RadicalMartHelperPrice', JPATH_ADMINISTRATOR . '/components/com_radicalmart/helpers/price.php');
JLoader::register('RadicalMartHelperSQL', JPATH_ADMINISTRATOR . '/components/com_radicalmart/helpers/sql.php');
JLoader::register('RadicalMartHelperUser', JPATH_ADMINISTRATOR . '/components/com_radicalmart/helpers/user.php');
JLoader::register('RadicalMartHelperMessage', JPATH_ADMINISTRATOR . '/components/com_radicalmart/helpers/message.php');
JLoader::register('RadicalMartHelperPlugins', JPATH_ADMINISTRATOR . '/components/com_radicalmart/helpers/plugins.php');
JLoader::register('RadicalMartHelperFields', JPATH_ADMINISTRATOR . '/components/com_radicalmart/helpers/fields.php');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

Factory::getLanguage()->load('com_radicalmart');

$controller = BaseController::getInstance('RadicalMartSearch');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();