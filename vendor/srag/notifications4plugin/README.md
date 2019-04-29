This library offers a quick and easy way to create and send notifications in any language. The notifications are usually configured in the ui of Notifications4Plugin and can then be sent for instance as an email by other plugins dynamic

The text of the notifications is parsed by default with the [Twig template engine!](https://twig.symfony.com/doc/1.x/templates.html), meaning the developer can replace placeholders and use if statements and loops

The development interface offers easy methods to create, modify and send notifications

### Usage

#### Composer
First add the following to your `composer.json` file:
```json
"require": {
  "srag/notifications4plugin": ">=0.1.0"
},
```
And run a `composer install`.

If you deliver your plugin, the plugin has it's own copy of this library and the user doesn't need to install the library.

Tip: Because of multiple autoloaders of plugins, it could be, that different versions of this library exists and suddenly your plugin use an older or a newer version of an other plugin!

So I recommand to use [srag/librariesnamespacechanger](https://packagist.org/packages/srag/librariesnamespacechanger) in your plugin.

#### Notification ActiveRecord
First you need to implement a `Notification` and `NotificationLanguage` active record class with your own table name
```php
...
use srag\NotificationsUI\x\Notification\AbstractNotification;
use srag\Plugins\x\Notification\Language\NotificationLanguage;
...
class Notification extends AbstractNotification {

	const TABLE_NAME = "x";
	const LANGUAGE_CLASS_NAME = NotificationLanguage::class;
}
```
```php
...
use srag\NotificationsUI\x\Notification\Language\AbstractNotificationLanguage;
...
class NotificationLanguage extends AbstractNotificationLanguage {

	const TABLE_NAME = "x";
}
```

Add an update step to your `dbupdate.php`
```php
...
<#x>
<?php
\srag\Plugins\x\Notification\Notification::updateDB();
\srag\Plugins\x\Notification\Language\NotificationLanguage::updateDB();
?>
```

and not forget to add an uninstaller step in your plugin class too
```php
...
use srag\Plugins\x\Notification\Notification;
use srag\Plugins\x\Notification\Language\NotificationLanguage
...
self::dic()->database()->dropTable(Notification::TABLE_NAME, false);
self::dic()->database()->dropTable(NotificationLanguage::TABLE_NAME, false);
...
```

#### Ctrl class
```php
...
use srag\NotificationsUI\x\Ctrl\AbstractCtrl;
use srag\Plugins\x\Notification\Notification;
use srag\Plugins\x\Notification\Language\NotificationLanguage;
...
/**
 * ...
 *
 * @ilCtrl_isCalledBy srag\Plugins\x\Notification\Ctrl\XCtrl: ilUIPluginRouterGUI
 */
class XCtrl extends AbstractCtrl {
	...
	const NOTIFICATION_CLASS_NAME = Notification::class;
	const LANGUAGE_CLASS_NAME = NotificationLanguage::class;
	const PLUGIN_CLASS_NAME = ilXPlugin::class;
	...
}
```

#### Languages
Expand you plugin class for installing languages of the library to your plugin
```php
...

	/**
	 * @inheritdoc
	 */
	public function updateLanguages($a_lang_keys = null) {
		parent::updateLanguages($a_lang_keys);

		LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__ . "/../vendor/srag/notifications4plugin/lang")
			->updateLanguages($a_lang_keys);
	}
...
```

#### Migrate from old global plugin
Add to your `dbupdate.php` like:
```php
if (\srag\Notifications4Plugin\Notifications4Plugins\x\Notification\Repository::getInstance(\srag\Plugins\x\Notification\Notification\Notification::class, \srag\Plugins\x\Notification\Notification\Language\NotificationLanguage::class)
		->migrateFromOldGlobalPlugin(x::TEMPLATE_NAME) === null) {

	$notification = \srag\Notifications4Plugin\Notifications4Plugins\x\Notification\Repository::getInstance(\srag\Plugins\x\Notification\Notification\Notification::class, \srag\Plugins\x\Notification\Notification\Language\NotificationLanguage::class)
		->factory()->newInstance();

	$notification->setName(x::TEMPLATE_NAME);

	// TODO: Fill $notification with your default values

	\srag\Notifications4Plugin\Notifications4Plugins\x\Notification\Repository::getInstance(\srag\Plugins\x\Notification\Notification\Notification::class, \srag\Plugins\x\Notification\Notification\Language\NotificationLanguage::class)
		->storeInstance($notification);
}
```

#### Using trait
Your class in this you want to use Notifications4Plugin needs to use the trait `Notifications4PluginTrait`
```php
...
use srag\Notifications4Plugin\Notifications4Plugins\x\Utils\Notifications4PluginTrait;
...
class x {
...
use Notifications4PluginTrait;
...
```

##### Get notification(s)
Main
```php
// Get the notification by name
$notification = self::notification(Notification::class, NotificationLanguage::class)->getNotificationByName(self::MY_UNIQUE_NAME);

// Get notifications for a selection list (For instance the options for an `ilSelectInputGUI`)
$notifications = self::notification(Notification::class, NotificationLanguage::class)->getArrayForSelection($notifications);
```
Other
```php
// Get the notification by id
$notification = self::notification(Notification::class, NotificationLanguage::class)->getNotificationById(self::MY_UNIQUE_ID);

// Get notifications for a table
$notifications = self::notification(Notification::class, NotificationLanguage::class)->getArrayForTable($notifications);

// Get the notifications
$notifications = self::notification(Notification::class, NotificationLanguage::class)->getNotifications();
```

##### Send a notification
```php
// Send the notification as external mail
$sender = self::sender()->factory()->externalMail('from_email', 'to_email');

// Send the notification as internal mail
$sender = self::sender()->factory()->internalMail('from_user', 'to_user');

// vcalendar
$sender = self::sender()->factory()->vcalendar(...);

// Implement a custom sender object
// Your class must implement the interface `srag\Notifications4Plugin\Notifications4Plugins\x\Sender\Sender`
```

```php
// Prepare placeholders, note that the keys are the same like deklared in the notification template
$placeholders = array(
  'user' => new ilObjUser(6),
  'course' => new ilObjCourse(12345)
);
```

```php
// Sent the notification in english first (default langauge) and in german again
self::sender()->send($sender, $notification, $placeholders);
self::sender()->send($sender, $notification, $placeholders, 'de');
```

##### Create a notification
```php
$notification = self::notification(Notification::class, NotificationLanguage::class)->factory()->newInstance();

$notification->setName(self::MY_UNIQUE_NAME); // Use the name as unique identifier to retrieve this object later
$notification->setDefaultLanguage('en'); // The text of the default language gets substituted if you try to get the notification of a langauge not available
$notification->setTitle('My first notification');
$notification->setDescription("I'm a description");

// Add subject and text for english and german
$notification->setSubject('Hi {{ user.getFullname }}', 'en');
$notification->setText('You joined the course {{ course.getTitle }}', 'en');
$notification->setSubject('Hallo {{ user.getFullname }}', 'de');
$notification->setText('Sie sind nun Mitglied in folgendem Kurs {{ course.getTitle }}', 'de');

self::notification(Notification::class, NotificationLanguage::class)->storeInstance($notification);
```

##### Duplicate a notification
```php
$duplicated_notification = self::notification(Notification::class, NotificationLanguage::class)->duplicateNotification($notification, self::plugin());
```

##### Delete a notification
```php
self::notification(Notification::class, NotificationLanguage::class)->deleteNotification($notification);
```

##### Get parsed subject and text of a notification
You can get the parsed subject and text from a notification, for example to display it on screen.

```php
$placeholders = array(
  'course' => new ilObjCourse(1234),
  'user' => new ilObjUser(6)
);

$parser = self::parser()->getParserForNotification($notification);

$subject = self::parser()->parseSubject($parser, $notification, $placeholders);
$text = self::parser()->parseText($parser, $notification, $placeholders);
```

##### Implement a custom parser
Your class must implement the interface `srag\Notifications4Plugin\Notifications4Plugins\x\Parser\Parser`

You can add it
```php
self::parser()->addParser(new CustomParser());
```

##### UI
ActiveRecordConfigGUI
```php
/**
 * @var array
 */
protected static $tabs = [
	XCtrl::TAB_NOTIFICATIONS => [
		XCtrl::class,
		XCtrl::CMD_LIST_NOTIFICATIONS
	]
];
```

```php
// Table
$table = self::notificationUI()->withPlugin(self::plugin())->notificationTable($this, $parent_cmd, function () {
			return self::notification(Notification::class, NotificationLanguage::class)->getArrayForTable($notifications);
		});
		
// Form
$form = self::notificationUI()->withPlugin(self::plugin())->notificationForm($this, $notification);

// Delete confirmation
$confirm = self::notificationUI()->withPlugin(self::plugin())->notificationDeleteConfirmation($this, $notification);

// Template selection
self::notificationUI()->withPlugin(self::plugin())->templateSelection($notifications, 'post_key', array(
  'user' => 'object ' . ilObjUser::class,
  'course' => 'object ' . ilObjCourse::class,
  'id' => 'int'
));
```

### Dependencies
* ILIAS 5.3 or ILIAS 5.4
* PHP >=7.0
* [composer](https://getcomposer.org)
* [srag/custominputguis](https://packagist.org/packages/srag/custominputguis)
* [srag/dic](https://packagist.org/packages/srag/dic)
* [twig/twig](https://packagist.org/packages/twig/twig)

Please use it for further development!

### Adjustment suggestions
* Adjustment suggestions by pull requests
* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/PLNOTIFICATION
* Bug reports under https://jira.studer-raimann.ch/projects/PLNOTIFICATION
* For external users you can report it at https://plugins.studer-raimann.ch/goto.php?target=uihk_srsu_PLNOTIFICATION
