<?php
/*
 * @package     RadicalMart Search Package
 * @subpackage  com_radicalmart_search
 * @version     __DEPLOY_VERSION__
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
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
<?php if (empty($items)): ?>
	<li>
		<div class="dropdown-item text-danger" style="white-space: normal">
			<?php echo Text::_('COM_RADICALMART_ERROR_PRODUCTS_NOT_FOUND'); ?>
		</div>
	</li>
<?php else: ?>
	<?php foreach ($items as $item): ?>
		<li>
			<a href="<?php echo $item->link; ?>" class="dropdown-item" style="white-space: normal">
				<?php echo $item->title; ?>
			</a>
		</li>
	<?php endforeach; ?>
<?php endif; ?>