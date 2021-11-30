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

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

extract($displayData);

/**
 * Layout variables
 * -----------------
 *
 * @var  array|false $items Find items data array.
 *
 */

?>
<div>
	<?php if (empty($items)): ?>
		<div class="uk-text-danger">
			<?php echo Text::_('COM_RADICALMART_ERROR_PRODUCTS_NOT_FOUND'); ?>
		</div>
	<?php else: ?>
		<ul class="uk-nav uk-dropdown-nav">
			<?php foreach ($items as $item): ?>
				<li><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>