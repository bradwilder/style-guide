module.exports =
{
	entry:
	{
		App_login: __dirname + "/app/assets/scripts/App_login.js",
		App_moodboard: __dirname + "/app/assets/scripts/App_moodboard.js",
		App_styleguide: __dirname + "/app/assets/scripts/App_styleguide.js",
		App_styleguide_config: __dirname + "/app/assets/scripts/App_styleguide_config.js",
		App_admin: __dirname + "/app/assets/scripts/App_admin.js",
		App_not_found: __dirname + "/app/assets/scripts/App_not_found.js",
		Vendor: __dirname + "/app/assets/scripts/Vendor.js"
	},
	output:
	{
		path: __dirname + "/app/temp/scripts",
		filename: "[name].js"
	},
	module:
	{
		loaders:
		[
			{
				loader: 'babel-loader',
				query:
				{
					presets: ['es2015']
				},
				test: /\.js$/,
				exclude: /node_modules/
			}
		]
	},
	resolve:
	{
		alias:
		{
			vue: 'vue/dist/vue.js'
		}
	}
}