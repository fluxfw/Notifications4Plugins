# Notifications4Plugins

### Install Notifications4Plugins-Plugin
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
git clone https://github.com/studer-raimann/Notifications4Plugins.git Notifications4Plugins
```
Update, activate and config the plugin in the ILIAS Plugin Administration

This plugin offers a quick and easy way to create notifications (subject & text) in any language in the configuration screen in ILIAS. The text of the notifications is parsed by default with the [Twig template engine!](https://twig.symfony.com/doc/1.x/templates.html), meaning the developer can replace placeholders and use if statements and loops. The API offers easy methods to send the notifications

## Interface
First include the `Notifications4Plugins` autoloader relative in your main plugin class file
```php
...
require_once __DIR__ . "/../../Notifications4Plugins/vendor/autoload.php";
...
```

Your class in this you want to use Notifications4Plugins needs to use the Trait `Notifications4PluginsTrait`
```php
...
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;
...
class x {
...
use Notifications4PluginsTrait;
...
```

### Get notification(s)
Main
```php
// Get the notification by name
$notification = self::notification()->getNotificationByName(self::MY_UNIQUE_NAME);

// Get notifications for a selection list (For instance the options for an `ilSelectInputGUI`)
$notifications = self::notification()->getArrayForSelection();
```
Other
```php
// Get the notification by id
$notification = self::notification()->getNotificationById(self::MY_UNIQUE_ID);

// Get notifications for a table
$notifications = self::notification()->getArrayForTable();

// Get the notifications
$notifications = self::notification()->getNotifications();
```

### Send a notification
```php
// Send the notification as external mail
$sender = self::sender()->factory()->externalMail('to_email', 'from_email');

// Send the notification as internal mail
$sender = self::sender()->factory()->internalMail('from_user', 'to_user');

// vcalendar
$sender = self::sender()->factory()->vcalendar(...);

// Implement a custom sender object
// Your class must implement the interface `srag\Plugins\Notifications4Plugins\Sender\Sender`
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

### Create a notification
```php
$notification = self::notification()->newInstance();

$notification->setName(self::MY_UNIQUE_NAME); // Use the name as unique identifier to retrieve this object later
$notification->setDefaultLanguage('en'); // The text of the default language gets substituted if you try to get the notification of a langauge not available
$notification->setTitle('My first notification');
$notification->setDescription("I'm a description");

// Add subject and text for english and german
$notification->setSubject('Hi {{ user.getFullname }}', 'en');
$notification->setText('You joined the course {{ course.getTitle }}', 'en');
$notification->setSubject('Hallo {{ user.getFullname }}', 'de');
$notification->setText('Sie sind nun Mitglied in folgendem Kurs {{ course.getTitle }}', 'de');

self::notification()->storeInstance($notification);
```

### Delete a notification
```php
self::notification()->deleteNotification($notification);
```

### Get parsed subject and text of a notification
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

### Some screenshots
TODO

### Dependencies
* ILIAS 5.3 or ILIAS 5.4
* PHP >=5.6
* [composer](https://getcomposer.org)
* [srag/dic](https://packagist.org/packages/srag/dic)
* [srag/librariesnamespacechanger](https://packagist.org/packages/srag/librariesnamespacechanger)
* [srag/removeplugindataconfirm](https://packagist.org/packages/srag/removeplugindataconfirm)
* [twig/twig](https://packagist.org/packages/twig/twig)

Please use it for further development!

### Adjustment suggestions
* Adjustment suggestions by pull requests
* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/PLNOTIFICATION
* Bug reports under https://jira.studer-raimann.ch/projects/PLNOTIFICATION
* For external users you can report it at https://plugins.studer-raimann.ch/goto.php?target=uihk_srsu_PLNOTIFICATION

### ILIAS Plugin SLA
Wir lieben und leben die Philosophie von Open Source Software! Die meisten unserer Entwicklungen, welche wir im Kundenauftrag oder in Eigenleistung entwickeln, stellen wir öffentlich allen Interessierten kostenlos unter https://github.com/studer-raimann zur Verfügung.

Setzen Sie eines unserer Plugins professionell ein? Sichern Sie sich mittels SLA die termingerechte Verfügbarkeit dieses Plugins auch für die kommenden ILIAS Versionen. Informieren Sie sich hierzu unter https://studer-raimann.ch/produkte/ilias-plugins/plugin-sla.

Bitte beachten Sie, dass wir nur Institutionen, welche ein SLA abschliessen Unterstützung und Release-Pflege garantieren.
