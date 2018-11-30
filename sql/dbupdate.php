<#1>
<?php
srNotification::updateDB();
srNotificationLanguage::updateDB();
?>
<#2>
<?php
srNotification::updateDB();
?>
<#3>
<?php
srNotification::updateDB();
?>
<#4>
<?php
\srag\DIC\Notifications4Plugins\DICStatic::dic()->database()->modifyTableColumn(srNotification::TABLE_NAME, 'title', array(
	'type' => 'text',
	'length' => 1024
));
\srag\DIC\Notifications4Plugins\DICStatic::dic()->database()->modifyTableColumn(srNotification::TABLE_NAME, 'description', array(
	'type' => 'text',
	'length' => 4000
));
\srag\DIC\Notifications4Plugins\DICStatic::dic()->database()->modifyTableColumn(srNotification::TABLE_NAME, 'name', array(
	'type' => 'text',
	'length' => 1024
));
?>
