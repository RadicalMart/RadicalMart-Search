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

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

if (Factory::getApplication()->getTemplate() !== 'yootheme' && $this->params->get('uikit', 1))
{
	HTMLHelper::stylesheet('com_radicalmart/uikit.min.css', array('version' => 'auto', 'relative' => true));
	HTMLHelper::script('com_radicalmart/uikit.min.js', array('version' => 'auto', 'relative' => true));
	HTMLHelper::script('com_radicalmart/uikit-icons.min.js', array('version' => 'auto', 'relative' => true));
}

if ($this->mode === 'shop')
{
	HTMLHelper::script('com_radicalmart/cart.min.js', array('version' => 'auto', 'relative' => true));
	HTMLHelper::script('com_radicalmart/axios.min.js', array('version' => 'auto', 'relative' => true));
	if ($this->params->get('radicalmart_js', 1))
	{
		HTMLHelper::script('com_radicalmart/radicalmart.min.js', array('version' => 'auto', 'relative' => true));
	}
}

if ($this->params->get('trigger_js', 1))
{
	HTMLHelper::script('com_radicalmart/trigger.min.js', array('version' => 'auto', 'relative' => true));
}

$fieldsets = array();
foreach ($this->filterForm->getFieldsets() as $key => $fieldset)
{
	foreach ($this->filterForm->getFieldset($key) as $field)
	{
		$name  = $field->fieldname;
		$group = $field->group;
		$type  = strtolower($field->type);
		$class = $this->filterForm->getFieldAttribute($name, 'class', '', $group);
		$input = $field->input;
		if ($type === 'text' || $type === 'email') $class .= ' uk-input';
		elseif ($type === 'list' || preg_match('#<select#', $input)) $class .= ' uk-select';
		elseif ($type === 'textarea' || preg_match('#<textarea#', $input)) $class .= ' uk-textarea';
		elseif ($type === 'range') $class .= ' uk-range';

		$this->filterForm->setFieldAttribute($name, 'class', $class, $group);
	}
}
?>

<div id="RadicalMart" class="radicalmart-container search">
	<h1 class="uk-h2 uk-margin uk-margin-remove-top uk-text-center">
		<?php echo $this->params->get('seo_search_h1', Text::_('COM_RADICALMART_SEARCH_TITLE')); ?>
	</h1>
	<div class="uk-card uk-card-default">
		<div class="uk-card-header">
			<div class="uk-grid-small uk-flex-middle" uk-grid>
				<div class="uk-width-expand@s uk-flex uk-flex-center uk-flex-left@s uk-text-small">
					<form action="<?php echo $this->link; ?>"
						  class="uk-form uk-child-width-expand@m uk-grid-small uk-width-1-1" uk-grid="">
						<?php foreach ($this->filterForm->getFieldset('search') as $field): ?>
							<div>
								<?php echo $this->filterForm->getInput($field->fieldname, $field->group); ?>
							</div>
						<?php endforeach; ?>
						<div class="uk-width-auto@s">
							<button class="uk-button uk-button-primary" type="submit"><span uk-search-icon></span>
							</button>
						</div>
					</form>
				</div>
				<div class="uk-width-auto@s uk-flex uk-flex-center uk-flex-middle">
					<ul class="uk-subnav uk-iconnav uk-margin-small-left uk-visible@s">
						<li class="<?php echo ($this->productsListTemplate === 'grid') ? 'uk-active' : ''; ?>">
							<span class="uk-link"
								  uk-icon="grid" uk-tooltip onclick="setProductsListTemplate('grid')"
								  title="<?php echo Text::_('COM_RADICALMART_PRODUCTS_LIST_LAYOUT_GRID'); ?>"></span>
						</li>
						<li class="<?php echo ($this->productsListTemplate === 'list') ? 'uk-active' : ''; ?>">
							<span class="uk-link"
								  uk-icon="list" uk-tooltip onclick="setProductsListTemplate('list')"
								  title="<?php echo Text::_('COM_RADICALMART_PRODUCTS_LIST_LAYOUT_LIST'); ?>"></span>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="uk-card-body">
			<?php if (empty($this->items)) : ?>
				<div class="uk-alert uk-alert-warning">
					<?php if (empty($this->keyword)) echo Text::_('COM_RADICALMART_SEARCH_ERROR_EMPTY_KEYWORD');
					elseif (mb_strlen($this->keyword) <= 3) echo Text::_('COM_RADICALMART_SEARCH_ERROR_KEYWORD_LENGTH');
					else  echo Text::_('COM_RADICALMART_ERROR_PRODUCTS_NOT_FOUND'); ?>
				</div>
			<?php else: ?>
				<div class="products-list">
					<?php echo $this->loadTemplate($this->productsListTemplate); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if ($this->items && $this->pagination): ?>
		<div class="list-pagination uk-margin-medium">
			<?php echo $this->pagination->getPaginationLinks(); ?>
		</div>
	<?php endif; ?>
</div>