<?php

require_once("./Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php");

if (is_file('./Services/ActiveRecord/class.ActiveRecord.php')) {
    require_once('./Services/ActiveRecord/class.ActiveRecord.php');
} elseif (is_file('./Customizing/global/plugins/Libraries/ActiveRecord/class.ActiveRecord.php')) {
    require_once('./Customizing/global/plugins/Libraries/ActiveRecord/class.ActiveRecord.php');
}

/**
 * Class ilNotifications4PluginsPlugin
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilNotifications4PluginsPlugin extends ilUserInterfaceHookPlugin
{

    /**
     * @var ilNotifications4PluginsPlugin
     */
    protected static $instance;


    /**
     * Singleton Access to this plugin
     *
     * @return ilNotifications4PluginsPlugin
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    

    /**
     * Get Plugin Name. Must be same as in class name il<Name>Plugin
     * and must correspond to plugins subdirectory name.
     *
     * Must be overwritten in plugin class of plugin
     * (and should be made final)
     *
     * @return    string    Plugin Name
     */
    public function getPluginName()
    {
        return 'Notifications4Plugins';
    }

} 