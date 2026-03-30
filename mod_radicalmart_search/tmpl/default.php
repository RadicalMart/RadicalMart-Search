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

use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
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

?>
<div id="mod_radicalmart_search_<?php echo $module->id; ?>" class="radicalmart-container search">
	<form action="<?php echo $action; ?>" class="row row-cols-auto" method="get">
		<div class="input-group mb-3">
			<?php echo $form->getInput('keyword'); ?>
			<button type="submit" class="btn btn-primary">
				<span class="icon-search icon-white" aria-hidden="true"></span>
				<?php echo Text::_('JSEARCH_FILTER_SUBMIT'); ?>
			</button>
		</div>
	</form>
</div>