<?php
/*
 * @package     RadicalMart Search Package
 * @subpackage  mod_radicalmart_search
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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;

Factory::getLanguage()->load('com_radicalmart');

// Get model
/* @var RadicalMartSearchModelSearch $model */
BaseDatabaseModel::addIncludePath(JPATH_SITE . '/components/com_radicalmart_search/models');
Form::addFormPath(JPATH_SITE . '/components/com_radicalmart_search/models/forms');
$model = BaseDatabaseModel::getInstance('Search', 'RadicalMartSearchModel', array('ignore_request' => true));
$model->setState('params', ComponentHelper::getParams('com_radicalmart'));
$form   = $model->getFilterForm();
$action = Route::link('site', RadicalMartSearchHelperRoute::getSearchRoute());
$form->setValue('keyword', null, '');
$form->setValue('found', null, '');

require ModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));