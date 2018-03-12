<#1>
    <?php
    require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/classes/Notification/class.srNotification.php');
    require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/classes/Notification/class.srNotificationLanguage.php');
    srNotification::updateDB();
    srNotificationLanguage::updateDB();
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
    require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/classes/Notification/class.srNotification.php');
    global $DIC;
    $ilDB = $DIC->database();
    $ilDB->modifyTableColumn(srNotification::TABLE_NAME, 'title', array('type' => 'text', 'length' => 1024));
    $ilDB->modifyTableColumn(srNotification::TABLE_NAME, 'description', array('type' => 'text', 'length' => 4000));
    $ilDB->modifyTableColumn(srNotification::TABLE_NAME, 'name', array('type' => 'text', 'length' => 1024));
    ?>