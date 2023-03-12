<?php
/**
 * App plugin installer for SweetRice.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
	defined('VALID_INCLUDE') or die();
	class pluginInstaller
	{
		private $pluginConfig = array();
		function __construct($pluginConfig = array()){
			$this->pluginConfig = $pluginConfig;
		}

		function beforeInstall(){
		
		}

		function afterInstall(){
			
		}
		
		function beforeDeInstall(){

		}

		function afterDeInstall(){
			
		}	
	}
?>