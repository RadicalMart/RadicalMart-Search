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

JLoader::register('RadicalMartHelperIntegration', JPATH_ADMINISTRATOR . '/components/com_radicalmart/helpers/integration.php');
RadicalMartHelperIntegration::initializeSite();
JLoader::register('RadicalMartSearchHelperRoute', JPATH_SITE . '/components/com_radicalmart_search/helpers/route.php');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;

// Get model
/* @var RadicalMartSearchModelSearch $model */
BaseDatabaseModel::addIncludePath(JPATH_SITE . '/components/com_radicalmart_search/models');
Form::addFormPath(JPATH_SITE . '/components/com_radicalmart_search/models/forms');
Form::addFieldPath(JPATH_SITE . '/components/com_radicalmart_search/models/fields');
$model = BaseDatabaseModel::getInstance('Search', 'RadicalMartSearchModel', array('ignore_request' => true));
$model->setState('params', ComponentHelper::getParams('com_radicalmart'));
$form   = $model->getFilterForm();
$action = Route::link('site', RadicalMartSearchHelperRoute::getSearchRoute());
$form->setValue('keyword', null, '');
$form->setValue('found', null, '');

require ModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));