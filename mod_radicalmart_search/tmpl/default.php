<?php
/*
 * @package     RadicalMart Search Package
 * @subpackage  mod_radicalmart_search
 * @version     __DEPLOY_VERSION__
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Registry\Registry;

/**
 * Template variables
 * -----------------
 *
 * @var  Form      $form   Filter form object.
 * @var  string    $action Form action href.
 * @var   object   $module Module object.
 * @var   Registry $params Module params.
 */

if (!$form) return;

if (Factory::getApplication()->getTemplate() !== 'yootheme'
	&& ComponentHelper::getParams('com_radicalmart')->get('uikit', 1))
{
	HTMLHelper::stylesheet('com_radicalmart/uikit.min.css', array('version' => 'auto', 'relative' => true));
	HTMLHelper::script('com_radicalmart/uikit.min.js', array('version' => 'auto', 'relative' => true));
	HTMLHelper::script('com_radicalmart/uikit-icons.min.js', array('version' => 'auto', 'relative' => true));
}


$fieldsets = array();
foreach ($form->getFieldsets() as $key => $fieldset)
{
	foreach ($form->getFieldset($key) as $field)
	{
		$name  = $field->fieldname;
		$group = $field->group;
		$type  = strtolower($field->type);
		$class = $form->getFieldAttribute($name, 'class', '', $group);
		$input = $field->input;
		if ($type === 'text' || $type === 'email') $class .= ' uk-input';
		elseif ($type === 'list' || preg_match('#<select#', $input)) $class .= ' uk-select';
		elseif ($type === 'textarea' || preg_match('#<textarea#', $input)) $class .= ' uk-textarea';
		elseif ($type === 'range') $class .= ' uk-range';

		$form->setFieldAttribute($name, 'class', $class, $group);
	}
}
?>
<div id="mod_radicalmart_search_<?php echo $module->id; ?>" class="radicalmart-container search">
	<form action="<?php echo $action; ?>" method="get"
		  class="uk-form uk-child-width-expand@m uk-grid-small uk-width-1-1" uk-grid="">
		<?php foreach ($form->getFieldsets() as $key => $fieldset):
			foreach ($form->getFieldset($key) as $field):
				$name = $field->fieldname;
				$group = $field->group;
				$id = 'mod_radicalmart_filter_' . $module->id . '_' . $field->id;
				$form->setFieldAttribute($name, 'id', $id, $group);
				?>
				<div>
					<?php echo $form->getInput($name, $group); ?>
				</div>
				<?php
				$form->setFieldAttribute($name, 'id', '', $group);
			endforeach;
		endforeach; ?>
		<div class="uk-width-auto@s">
			<button class="uk-button uk-button-primary" type="submit"><span uk-search-icon></span>
			</button>
		</div>
	</form>
</div>
