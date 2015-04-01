<?php
/**
 * App config.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
	$plugin_config['name'] = 'App';
	$plugin_config['version'] = '1.0';
	$plugin_config['install_sql'] = 'install.sql';
	$plugin_config['deinstall_sql'] = 'deinstall.sql';
	$plugin_config['install_pgsql'] = 'install_pgsql.sql';
	$plugin_config['deinstall_pgsql'] = 'deinstall_pgsql.sql';
	$plugin_config['install_sqlite'] = 'install_sqlite.sql';
	$plugin_config['deinstall_sqlite'] = 'deinstall_sqlite.sql';
	$plugin_config['home'] = 'home.php';
	$plugin_config['description'] = array('en-us' => 'A basic plugin for developer,you can build website using it',
	'zh-cn' => '为开发者提供的一个基础插件,您可以使用它开始网站的建设',
	'big5' => '為開發者提供的一個基礎插件,您可以使用它開始網站的建設'
	);
	$plugin_config['author'] = 'Basic-cms.org';
	$plugin_config['contact'] = 'support@basic-cms.org';
	$plugin_config['home_page'] = 'http://www.basic-cms.org/sweetrice-plugins/App/';
?>