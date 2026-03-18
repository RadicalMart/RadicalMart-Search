/*
 * @package     RadicalMart Search Package
 * @subpackage  com_radicalmart_search
 * @version     __DEPLOY_VERSION__
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

import JoomlaAjax from "../utils/joomla-ajax.es6";
import {ElementsUtil} from "../utils/elements.es6";
import {EventsUtil} from "../utils/events.es6";

class RadicalMartSearchFieldSearchAjax {
	constructor(container) {
		let id = container.getAttribute('data-field_id'),
			options = Joomla.getOptions('com_radicalmart_search.ajax'),
			controller = (options) ? options.controller : false;

		this.id = id;
		this.attribute = 'radicalmart_search-field-search-ajax';
		this.container = container;

		this.element_field = container.querySelector('#' + id);

		console.log(this.element_field);
		console.log(this.id);

		if (!this.element_field || !controller) {
			return;
		}

		this.options = options;
		this.controller = controller;
		this.ajax = new JoomlaAjax(controller);

		this.initialization();
	}

	initialization() {
		this.element_field.addEventListener('input', () => {
			this.search();
		});
		this.element_field.addEventListener('change', () => {
			this.search();
		});
		this.element_field.addEventListener('focus', () => {
			this.search();
		});
	}

	async search() {
		let keyword = this.element_field.value;
		if (keyword.length < 3) {
			return;
		}

		let eventData = {
			keyword,
			id: this.id,
			container: this.container,
			attribute: this.attribute,
			controller: this.controller
		}

		EventsUtil.triggerCustomEvent('onRadicalMartSearchAjaxBefore', eventData, this.container, true);

		try {
			eventData.response = await this.ajax.sendAjax('search.find', {keyword}, true);

			EventsUtil.triggerCustomEvent('onRadicalMartSearchAjaxAfter', eventData, this.container, true);

		} catch (error) {
			error = (error instanceof Error) ? error : new Error(error);
			if (error.request_aborted) {
				return
			}
			eventData.error = error;
			EventsUtil.triggerCustomEvent('onRadicalMartSearchAjaxError', eventData, this.container, false);
			console.error(error);
		}
	}
}

document.addEventListener('DOMContentLoaded', () => {
	ElementsUtil.getElementsByAttribute('radicalmart_search-field-search-ajax', 'container')
		.forEach((container) => {
			container.FieldClass = new RadicalMartSearchFieldSearchAjax(container);
		});
});