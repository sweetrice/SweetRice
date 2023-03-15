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
    public function __construct($pluginConfig = array())
    {
        $this->pluginConfig = $pluginConfig;
    }

    public function beforeInstall()
    {

    }

    public function afterInstall()
    {

    }

    public function beforeDeInstall()
    {

    }

    public function afterDeInstall()
    {

    }
}
