const entry = {
	"field-ajax-search": {
		import: './com_radicalmart_search/es6/field/ajax-search.es6',
		filename: 'field-ajax-search.js'
	},
};

const webpackConfig = require('./webpack.config.js');
const publicPath = '../com_radicalmart_search/media';
const development = webpackConfig(entry, publicPath, 'development');
module.exports = [ development]