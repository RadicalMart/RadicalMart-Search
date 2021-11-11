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

use Joomla\CMS\Layout\LayoutHelper;

?>
<?php foreach ($this->items as $i => $item):
	if ($i > 0) echo '<hr class="uk-margin-remove">'; ?>
	<div class="item-<?php echo $item->id; ?>">
		<?php echo LayoutHelper::render('components.radicalmart.products.item.list',
			array('product' => $item, 'mode' => $this->mode)); ?>
	</div>
<?php endforeach; ?>