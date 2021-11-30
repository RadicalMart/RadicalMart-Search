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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/**
 * Layout variables
 * -----------------
 *
 *
 *
 */
HTMLHelper::script('com_radicalmart_search/field-ajax-search.min.js', array('version' => 'auto', 'relative' => true));

$doc       = Factory::getDocument();
$doc->addScriptOptions('radicalmart_search_ajax', array(
	'controller' => Route::_('index.php?option=com_radicalmart_search&task=search.find', false)
));
?>
<div radicalmart-search="container" data-id="<?php echo $displayData['id']; ?>">
	<?php echo LayoutHelper::render('joomla.form.field.text', $displayData); ?>
	<div radicalmart-search="result"></div>

	<script>
		document.addEventListener('DOMContentLoaded', function (ev) {
			let container = document.querySelector('[radicalmart-search="container"][data-id="<?php echo $displayData['id']; ?>"]'),
				result = container.querySelector('[radicalmart-search="result"]'),
				field = container.querySelector('input'),
				dropdown = UIkit.dropdown(result, {
					pos: 'bottom-justify',
					mode: 'click'
				});

			container.addEventListener('RadicalMartSearchAfter', function (event) {
				result.innerHTML = event.detail.result.html;
				dropdown.show();
			});


			UIkit.util.on('[radicalmart-search="container"][data-id="<?php echo $displayData['id']; ?>"] [radicalmart-search="result"]',
				'beforeshow', function (event) {
					if (field.value.length < 3) {
						event.preventDefault();
					}
				});
		});
	</script>
</div>
