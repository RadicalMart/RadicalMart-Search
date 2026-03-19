/*
 * @package     RadicalMart Search Package
 * @subpackage  com_radicalmart_search
 * @version     1.0.1
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

"use strict";

export const ElementsUtil = {
	/**
	 * Finds the first descendant element matching the attribute and value criteria within a parent element.
	 * It checks both standard attributes and 'data-' prefixed attributes.
	 *
	 * @param {string} attribute - The name of the attribute (e.g., 'role', 'test-id').
	 * @param {string|null} [value=null] - The value of the attribute to match.
	 * @param {HTMLElement|Document} [parent=document] - The parent element to start the search from.
	 * @param {string} [matchType='='] - The CSS attribute matching operator (e.g., '=', '~=', '|=', '*=', '^=', '$=').
	 * @returns {HTMLElement|null} The first matching element, or null if none found.
	 */
	getElementByAttribute(attribute, value = null, parent = document, matchType) {
		return parent.querySelector(this.getElementSelectorByAttribute(attribute, value, matchType));
	},


	/**
	 * Finds all descendant elements matching the attribute and value criteria within a parent element.
	 * It checks both standard attributes and 'data-' prefixed attributes.
	 *
	 * @param {string} attribute - The name of the attribute (e.g., 'role', 'test-id').
	 * @param {string|null} [value=null] - The value of the attribute to match.
	 * @param {HTMLElement|Document} [parent=document] - The parent element to start the search from.
	 * @param {string} [matchType='='] - The CSS attribute matching operator (e.g., '=', '~=', '|=', '*=', '^=', '$=').
	 * @returns {NodeList<HTMLElement>} A NodeList containing all matching elements.
	 */
	getElementsByAttribute(attribute, value = null, parent = document, matchType) {
		return parent.querySelectorAll(this.getElementSelectorByAttribute(attribute, value, matchType));
	},

	/**
	 * Finds the nearest ancestor element matching the attribute and value criteria (including the element itself).
	 * It checks both standard attributes and 'data-' prefixed attributes.
	 *
	 * @param {string} attribute - The name of the attribute (e.g., 'role', 'test-id').
	 * @param {string|null} [value=null] - The value of the attribute to match.
	 * @param {HTMLElement|null} [element=null] - The starting element for the search.
	 * @param {string} [matchType='='] - The CSS attribute matching operator (e.g., '=', '~=', '|=', '*=', '^=', '$=').
	 * @returns {HTMLElement|null} The nearest matching ancestor element, or null if none found or the starting element is null.
	 */
	getClosestByAttribute(attribute, value = null, element = null, matchType = '=') {
		if (!element) {
			return null;
		}
		let result = null,
			selectors = this.getElementSelectorsByAttribute(attribute, value, matchType);
		for (let i = 0; i < selectors.length; i++) {
			const selector = selectors[i];
			result = element.closest(selector);
			if (result) {
				break;
			}
		}

		return result;
	},


	/**
	 * Generates an array of CSS selectors for both standard and 'data-' prefixed attributes.
	 *
	 * @param {string} attribute - The name of the attribute (e.g., 'id', 'action').
	 * @param {string|null} [value=null] - The value of the attribute.
	 * @param {string} [matchType='='] - The CSS attribute matching operator.
	 * @returns {Array<string>} An array containing two selectors: ['[attr...]', '[data-attr...]'].
	 */
	getElementSelectorsByAttribute(attribute, value = null, matchType = '=') {
		let selector = attribute;
		if (value !== null) {
			selector += matchType + '"' + value + '"';
		}

		return [
			'[' + selector + ']',
			'[data-' + selector + ']'
		];
	},

	/**
	 * Generates a comma-separated CSS selector string suitable for querySelector/querySelectorAll.
	 *
	 * @param {string} attribute - The name of the attribute.
	 * @param {string|null} [value=null] - The value of the attribute.
	 * @param {string} [matchType] - The CSS attribute matching operator.
	 * @returns {string} A combined selector string (e.g., '[attr], [data-attr]').
	 */
	getElementSelectorByAttribute(attribute, value = null, matchType) {
		return this.getElementSelectorsByAttribute(attribute, value, matchType).join(',');
	},


	/**
	 * Retrieves the value of a standard attribute or its 'data-' prefixed equivalent from an element.
	 *
	 * @param {HTMLElement|null} element - The element to check for the attribute.
	 * @param {string} attribute - The name of the attribute (without 'data-' prefix).
	 * @param {*} [default_value=null] - The value to return if the attribute is not found.
	 * @returns {*} The attribute value as a string, or the default value if not found.
	 */
	getAttributeValue(element, attribute, default_value = null) {
		let result = null;
		if (element) {
			result = element.getAttribute(attribute);
			if (!result) {
				result = element.getAttribute('data-' + attribute);
			}
		}

		return (result) ? result : default_value;
	}
}