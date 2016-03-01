<#1>
    <?php
    require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/classes/Notification/class.srNotification.php');
    require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/classes/Notification/class.srNotificationLanguage.php');
    srNotification::installDB();
    srNotificationLanguage::installDB();
    ?>
<#2>
    <?php
    require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/classes/Notification/class.srNotification.php');
    srNotification::updateDB();
    ?>
<#3>
    <?php
    require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/classes/Notification/class.srNotification.php');
    srNotification::updateDB();
    ?>
<#4>
    <?php
    global $ilDB;
    $ilDB->modifyTableColumn('sr_notification', 'title', array('type' => 'text', 'length' => 1024));
    $ilDB->modifyTableColumn('sr_notification', 'description', array('type' => 'text', 'length' => 4000));
    $ilDB->modifyTableColumn('sr_notification', 'name', array('type' => 'text', 'length' => 1024));
    ?>