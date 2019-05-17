<#1>
<?php
\srag\Plugins\Notifications4Plugins\Notification\Notification::updateDB();
\srag\Plugins\Notifications4Plugins\Notification\Language\NotificationLanguage::updateDB();
?>
<#2>
<?php
\srag\Plugins\Notifications4Plugins\Notification\Notification::updateDB();
?>
<#3>
<?php
\srag\Plugins\Notifications4Plugins\Notification\Notification::updateDB();
?>
<#4>
<?php
\srag\DIC\Notifications4Plugins\DICStatic::dic()->database()
	->modifyTableColumn(\srag\Plugins\Notifications4Plugins\Notification\Notification::TABLE_NAME, "title", [
		"type" => \ilDBConstants::T_TEXT,
		"length" => 1024
	]);
\srag\DIC\Notifications4Plugins\DICStatic::dic()->database()
	->modifyTableColumn(\srag\Plugins\Notifications4Plugins\Notification\Notification::TABLE_NAME, "description", [
		"type" => \ilDBConstants::T_TEXT,
		"length" => 4000
	]);
\srag\DIC\Notifications4Plugins\DICStatic::dic()->database()
	->modifyTableColumn(\srag\Plugins\Notifications4Plugins\Notification\Notification::TABLE_NAME, "name", [
		"type" => \ilDBConstants::T_TEXT,
		"length" => 1024
	]);
?>
<#5>
<?php
\srag\Plugins\Notifications4Plugins\Notification\Notification::updateDB();
\srag\Plugins\Notifications4Plugins\Notification\Language\NotificationLanguage::updateDB();
?>
<#6>
<?php
\srag\Plugins\Notifications4Plugins\Notification\Notification::updateDB();
?>
<#7>
<?php
foreach (\srag\Plugins\Notifications4Plugins\Notification\Notification::get() as $notification) {
	/**
	 * @var \srag\Plugins\Notifications4Plugins\Notification\Notification $notification
	 */
	if (empty($notification->getParser())) {
		$notification->setParser(\srag\Notifications4Plugin\Notifications4Plugins\Parser\twigParser::class);

		$notification->store();
	}
}
?>
