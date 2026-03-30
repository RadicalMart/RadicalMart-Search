<?php
/*
 * @package     RadicalMart Search Package
 * @subpackage  com_radicalmart_search
 * @version     1.0.3
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

/** @var \Joomla\Component\RadicalMartSearch\Site\View\Search\HtmlView $this */
?>
<div id="RadicalMart" class="search">
	<h1 class="h2 mb-3">
		<?php echo $this->params->get('seo_search_h1', Text::_('COM_RADICALMART_SEARCH_TITLE')); ?>
	</h1>
	<form action="<?php echo $this->link; ?>" class="row row-cols-auto">
		<div class="input-group mb-3">
			<?php echo $this->filterForm->getInput('keyword'); ?>
			<button type="submit" class="btn btn-primary">
				<span class="icon-search icon-white" aria-hidden="true"></span>
				<?php echo Text::_('JSEARCH_FILTER_SUBMIT'); ?>
			</button>
		</div>
	</form>
	<div class="mt-3">
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-info">
				<span class="icon-info-circle" aria-hidden="true"></span>
				<span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
				<?php echo Text::_('COM_RADICALMART_ERROR_PRODUCTS_NOT_FOUND'); ?>
			</div>
		<?php else: ?>
			<div class="products-list">
				<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
					<?php foreach ($this->items as $item)
					{
						echo '<div class="mb-3">' . LayoutHelper::render('components.radicalmart.products.item.grid',
										['product' => $item, 'mode' => $this->mode]) . '</div>';
					} ?>
				</div>
			</div>
			<div class="list-pagination mt-3">
				<?php echo $this->pagination->getPaginationLinks(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>