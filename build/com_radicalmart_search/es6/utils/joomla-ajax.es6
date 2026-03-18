/*
 * @package     RadicalMart Search Package
 * @subpackage  com_radicalmart_search
 * @version     1.0.0
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2026 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

"use strict";

export default class JoomlaAjax {
	/**
	 * Initializes AJAX helper with a controller URL and CSRF cache settings.
	 *
	 * @param {string|null} controller Controller URL for AJAX requests.
	 * @param {boolean} csrfCache Enables/disables CSRF token caching.
	 */
	constructor(controller = null, csrfCache = true) {
		if (!controller || controller.length === 0) {
			throw new Error("Controller url can't be empty");
		}

		this.controller = controller;
		this.csrfCache = csrfCache;
		this.csrf = null;
	}


	/**
	 * Sends an AJAX request with CSRF token and task parameter.
	 *
	 * @param {string} task Joomla task to execute.
	 * @param {object|FormData} data Request payload as plain object or FormData.
	 * @param {boolean|null} csrfCache Overrides CSRF caching for this call (null to inherit).
	 */
	async sendAjax(task = '', data = {}, csrfCache = null) {
		let debug = {task, data}
		if (csrfCache !== null) {
			debug.csrfCache = csrfCache;
		} else {
			debug.csrfCache = 'inherit'
			debug.csrfCache += (this.csrfCache) ? '(true)' : '(false)';
		}

		if (typeof task !== 'string' || task.trim().length === 0) {
			throw this.createError('sendAjax', "Task can't be empty", debug);
		}

		let formData = false;
		if (data instanceof FormData) {
			formData = data;
		} else if (data instanceof Object) {
			formData = JoomlaAjaxUtil.convertObjectToFormData(data)
		}

		if (formData === false) {
			throw this.createError('sendAjax', 'Incorrect request data', debug);
		}

		let csrf = await this.getCSRF(csrfCache);
		formData.set(csrf, '1');
		formData.set('task', task);

		try {
			return await JoomlaAjaxUtil.sendRequest(this.controller, formData);
		} catch (error) {
			throw this.createError('sendAjax', error, debug);
		}
	}

	/**
	 * Retrieves a CSRF token from the server, optionally using a cached value.
	 *
	 * @param {boolean|null} cache Enables/disables caching (null to inherit instance setting).
	 */
	async getCSRF(cache = null) {
		cache = (cache === null) ? this.csrfCache : cache;

		if (cache && this.csrf) {
			return this.csrf;
		}

		let formData = new FormData();
		formData.set('task', 'getCSRF');
		formData.set('check_post', '1');

		let token = '';
		try {
			token = await JoomlaAjaxUtil.sendRequest(this.controller, formData);
		} catch (error) {
			throw this.createError('getCSRF', error);
		}

		if (token.trim().length === 0) {
			throw this.createError('getCSRF', 'Incorrect CSRF Token');
		}

		this.csrf = token;
		return token;
	}

	/**
	 * Creates a normalized Error instance with extended debug information.
	 *
	 * @param {string} method Method name where the error occurred.
	 * @param {Error|string} error Original error or message.
	 * @param {object} extra Extra debug data to attach.
	 */
	createError(method, error, extra = {}) {
		error = (error instanceof Error) ? error : new Error(error);

		let full_method = 'JoomlaAjax.' + method + '()',
			debug = {
				message: error.message,
				method: full_method,
				controller: this.controller,
			}
		Object.assign(debug, extra);
		error.debug = debug;

		return error;
	}
}

export const JoomlaAjaxUtil = {
	/**
	 * Performs an HTTP POST using Joomla.request and resolves parsed data.
	 *
	 * @param {string} url Endpoint to call.
	 * @param {FormData} data Payload for the request.
	 */
	sendRequest(url, data) {
		return new Promise((resolve, reject) => {
			Joomla.request({
				url: url,
				data: data,
				method: 'POST',
				onSuccess: (response) => {
					try {
						return resolve(this.parseResponse(response, url));
					} catch (error) {
						return reject(this.parseError(error));
					}
				},
				onError: (error) => {
					reject(this.parseError(error));
				}
			});
		});
	},

	/**
	 * Что тут написать то
	 *
	 * @param {string} url Endpoint to call.
	 * @param {FormData} data Payload for the request.
	 */
	getHtmlPage(url, data) {
		return new Promise((resolve, reject) => {
			Joomla.request({
				url: url,
				data: data,
				method: 'POST',
				onSuccess: (response) => {
					let container = document.createElement('div');
					container.innerHTML = response;

					resolve((container.children.length === 1) ? container.firstElementChild : container);
				},
				onError: (error) => {
					reject(this.parseError(error));
				}
			});
		});
	},

	/**
	 * Normalizes various error formats into a single Error-like object.
	 *
	 * @param {string|Error|XMLHttpRequest} error Incoming error to normalize.
	 */
	parseError(error) {
		let result = new Error();
		result.message = 'Unknown error';
		result.code = 0;
		result.request_aborted = false;

		// String error
		if (typeof error === 'string') {
			result.message = error

			return result;
		}

		// Error
		if (error instanceof Error) {
			result.message = error.message;
		}

		// XMLHttpRequest error
		if (error instanceof XMLHttpRequest) {
			result.code = error.status;
			if (error.status === 0) {
				result.message = 'Request Aborted'
				result.request_aborted = true;
				return result;
			}

			let findMessage = false;
			if (error.response && error.response.trim().length > 0) {
				// Symphony error
				if (error.response.startsWith('<br />') && error.response.includes('<b>Fatal error</b>')) {
					let match = error.response.match(/<b>Fatal error<\/b>:(.*?)\n/s);
					if (match && match[1]) {
						findMessage = true;
						result.message = 'Fatal error: ' + match[1].trim();
					}
				}

				// HTML error
				if (!findMessage && error.response.includes('<!DOCTYPE html>')) {
					let html = document.createElement('div');
					html.innerHTML = error.response;

					let title = html.querySelector('title');
					if (title) {
						findMessage = true;
						result.message = title.textContent;
					}

					// Joomla administrator error
					let htmlError = false,
						generator = html.querySelector('meta[name="generator"]');
					if (generator
						&& generator.getAttribute('content')
						&& generator.getAttribute('content').includes('Joomla')
					) {
						let block = html.querySelector('section#content > .row > .col-md-12');
						if (block) {
							let blockquote = block.querySelector('blockquote');
							if (blockquote) {
								htmlError = true;
								findMessage = true;
								result.message = blockquote.textContent;
							}
						}
					}

					// YOOTheme site error
					if (error.response.includes('templates/yootheme') && title) {
						htmlError = true;
						result.message = title.textContent;
					}

					// blockquote error
					let blockquote = html.querySelector('blockquote');
					if (!htmlError && blockquote) {
						htmlError = true;
						findMessage = true;
						result.message = blockquote.textContent;
					}

					// Page h1 error
					let h1 = html.querySelector('h1');
					if (!htmlError && h1) {
						htmlError = true;
						findMessage = true;
						result.message = h1.textContent;
					}

					// Add file to error
					let td = html.querySelector('table[aria-describedby="caption"] tbody > tr > td:last-child');
					if (td) {
						result.message += ' in ' + td.textContent;
					}

					// Prepare message
					if (!result.message.includes(result.code.toString())) {
						result.message = result.code + ' - ' + result.message;
					}
					result.message = result.message.trim();
					result.message = result.message.replace(/\s+/g, ' ');
				}
			}

			// Standard status text
			if (!findMessage && error.statusText && error.statusText.trim().length > 0) {
				result.message = error.statusText;
			}
		}

		return result;
	},

	/**
	 * Parses a server response, extracting JSON and validating format.
	 *
	 * @param {string} response Raw server response string.
	 * @param {string|null} url Request URL for warning messages (optional).
	 */
	parseResponse(response, url = null) {
		let jsonObject = null,
			jsonString = null,
			otherString = '';
		if (typeof response !== 'string') {
			throw new Error('Incorrect response format');
		}

		if (response.startsWith('{') && response.endsWith('}')) {
			// Normal JSON
			jsonString = response;
		} else if (response.includes('{') && response.includes('}')) {
			// JSON with trash
			jsonString = response;

			// Remove trash from start
			let startIndex = jsonString.indexOf('{');
			if (startIndex > 0) {
				otherString += jsonString.slice(0, startIndex);
				jsonString = jsonString.slice(startIndex);
			}

			otherString += '\n<--- JSON OBJECT --->\n';

			// Remove trash from end
			let endIndex = jsonString.lastIndexOf('}');
			if (endIndex < jsonString.length - 1) {
				otherString += jsonString.slice(endIndex + 1);
				jsonString = jsonString.slice(0, endIndex + 1);
			}

			if (otherString.includes('<!DOCTYPE html>')) {
				throw new Error('HTML response');
			}
		} else {
			if (response.trim().length === 0) {
				throw new Error('Empty response string');
			}
			if (response.includes('<!DOCTYPE html>')) {
				throw new Error('HTML response');
			}
			jsonObject = {
				success: true,
				message: '',
				messages: null,
				data: response
			}
		}

		if (!jsonString && !jsonObject) {
			throw new Error('JSON not found in response');
		}

		if (jsonString) {
			if (otherString) {
				let warning = 'Warning: Response contains other text';
				if (url) {
					warning += 'in ' + url;
				}
				warning += '\n' + otherString;
				console.warn(warning);
			}
			try {
				jsonObject = JSON.parse(jsonString);
			} catch (error) {
				throw this.parseError(error);
			}
		}

		let result;
		if (this.isStandardJoomlaResponse(jsonObject)) {
			result = jsonObject;
		} else {
			let warning = 'Warning: Not Joomla Standard response';
			if (url) {
				warning += 'in ' + url;
			}
			warning += '\n' + JSON.stringify(jsonObject);
			console.warn(warning);

			let success = Object.prototype.hasOwnProperty.call(jsonObject, 'success') ? jsonObject.success : true,
				message = Object.prototype.hasOwnProperty.call(jsonObject, 'message') ? jsonObject.message : '';

			if (Object.prototype.hasOwnProperty.call(jsonObject, 'error') && jsonObject.error) {
				if (typeof jsonObject.error === 'string') {
					success = false;
					message = jsonObject.error;
				}
			}
			result = {
				success: success,
				message: message,
				messages: null,
				data: jsonObject
			}
		}

		if (!result.success) {
			throw this.parseError(result.message);
		}

		return result.data;
	},

	/**
	 * Converts a plain object (with nested values) to FormData entries.
	 *
	 * @param {object} data Source object to convert.
	 * @param {FormData|null} formData Existing FormData to append to (optional).
	 * @param {string|null} path Nested key path for recursion (optional).
	 */
	convertObjectToFormData(data = {}, formData = null, path = null) {
		if (formData === null) {
			formData = new FormData();
		}

		if (path === null) {
			path = '';
		}

		Object.keys(data).forEach((key) => {
			let name = (path) ? path + '[' + key + ']' : key,
				value = data[key];
			if (Array.isArray(value)) {
				value.forEach((val) => {
					formData.append(name + '[]', val)
				})
			} else if (value instanceof Object) {
				formData = this.convertObjectToFormData(value, formData, name);
			} else {
				formData.set(name, value);
			}
		});

		return formData;
	},

	/**
	 * Checks whether an object matches the Joomla standard response shape.
	 *
	 * @param {object} object Response object to validate.
	 */
	isStandardJoomlaResponse(object) {
		let keys = ['success', 'message', 'messages', 'data'];
		if (Object.keys(object).length !== keys.length) {
			return false;
		}
		keys.forEach((key) => {
			if (!Object.prototype.hasOwnProperty.call(object, key)) {
				return false;
			}
		})

		return true;
	}
}