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


?>
	<form action="<?php echo $this->link; ?>">
		<?php echo $this->filterForm->getInput('keyword'); ?>
		<button>SEARCH</button>
	</form>

<?php foreach ($this->items as $item): ?>
	<h3><?php echo $item->title; ?></h3>
<?php endforeach; ?>

<?php if ($this->items && $this->pagination): ?>
	<div class="list-pagination uk-margin-medium">
		<?php echo $this->pagination->getPagesLinks() ?>
	</div>
<?php endif; ?>