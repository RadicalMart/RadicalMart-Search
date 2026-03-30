/*
 * @package     RadicalMart Search Package
 * @subpackage  com_radicalmart_search
 * @version     1.0.3
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

"use strict";

export const EventsUtil = {
	/**
	 * Triggers a custom event on a specified element.
	 *
	 * @param {string} eventName The name of the event to trigger.
	 * @param {object} detail Custom data to pass with the event.
	 * @param {HTMLElement|Document|null} element The element to dispatch the event from (defaults to document).
	 * @param {boolean|string|null} debug Controls logging (true/string enables, false/null disables).
	 */
	triggerCustomEvent(eventName, detail = {}, element = null, debug = true) {
		if (!eventName) {
			return;
		}

		if (!element) {
			element = document;
		}

		console.log(element);
		element.dispatchEvent(new CustomEvent(eventName, {detail: detail}));

		if (!debug) {
			return;
		}

		let title = 'Triggered: ' + eventName + '';
		if (typeof debug === 'string' && debug.length > 0) {
			title = debug + ' ' + title;
		}
		if (Object.keys(detail).length > 0) {
			console.debug(title, {target: element, detail});
		} else {
			console.debug(title, {target: element});
		}
	}
}