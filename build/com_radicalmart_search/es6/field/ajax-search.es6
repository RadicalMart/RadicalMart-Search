/*
 * @package     RadicalMart Search Package
 * @subpackage  com_radicalmart_search
 * @version     __DEPLOY_VERSION__
 * @author      Delo Design - delo-design.ru
 * @copyright   Copyright (c) 2021 Delo Design. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://delo-design.ru/
 */

import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('[radicalmart-search="container"], [data-radicalmart-search="container"]')
		.forEach(container => {
			let filed = container.querySelector('input');
			if (filed) {
				filed.addEventListener('input', search);
				filed.addEventListener('change', search);
				filed.addEventListener('focus', search);

				function search() {
					let keyword = filed.value;
					container.dispatchEvent(new CustomEvent('RadicalMartSearchBefore', {
						detail: {
							container: container,
							keyword: keyword,
						}
					}));

					if (keyword.length >= 3) {
						let formData = new FormData();
						formData.set('keyword', keyword);
						axios({
							method: 'post',
							url: Joomla.getOptions('radicalmart_search_ajax').controller,
							data: formData,
							headers: {'Content-Type': 'multipart/form-data'},
						}).then((response) => {
							if (response.data.success) {
								container.dispatchEvent(new CustomEvent('RadicalMartSearchAfter', {
									detail: {
										keyword: keyword,
										result: response.data.data,
									}
								}));
							} else {
								container.dispatchEvent(new CustomEvent('RadicalMartSearchError', {
									detail: {
										keyword: keyword,
										error: response.data.message
									}
								}));
							}
						}).catch((error) => {
							if (error.message !== 'Request aborted') {
								container.dispatchEvent(new CustomEvent('RadicalMartSearchError', {
									detail: {
										keyword: keyword,
										error: error.message
									}
								}));
							}
						});
					}
				}
			}
		});
});