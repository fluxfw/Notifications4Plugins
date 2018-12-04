<#1>
<?php
\srag\Plugins\Notifications4Plugins\Notification\srNotification::updateDB();
\srag\Plugins\Notifications4Plugins\Notification\srNotificationLanguage::updateDB();
?>
<#2>
<?php
\srag\Plugins\Notifications4Plugins\Notification\srNotification::updateDB();
?>
<#3>
<?php
\srag\Plugins\Notifications4Plugins\Notification\srNotification::updateDB();
?>
<#4>
<?php
\srag\DIC\Notifications4Plugins\DICStatic::dic()->database()
	->modifyTableColumn(\srag\Plugins\Notifications4Plugins\Notification\srNotification::TABLE_NAME, 'title', array(
		'type' => 'text',
		'length' => 1024
	));
\srag\DIC\Notifications4Plugins\DICStatic::dic()->database()
	->modifyTableColumn(\srag\Plugins\Notifications4Plugins\Notification\srNotification::TABLE_NAME, 'description', array(
		'type' => 'text',
		'length' => 4000
	));
\srag\DIC\Notifications4Plugins\DICStatic::dic()->database()
	->modifyTableColumn(\srag\Plugins\Notifications4Plugins\Notification\srNotification::TABLE_NAME, 'name', array(
		'type' => 'text',
		'length' => 1024
	));
?>
