# Notifications4Plugins

This plugin offers a quick and easy way to create notifications (subject & text) in any language in the configuration screen in ILIAS. The text of the notifications is parsed by default with the Twig template engine, meaning the developer can replace placeholders and use if statements and loops. The API offers easy methods to send the notifications.

## Requirements
* ActiveRecord for ILIAS < 5 (https://github.com/studer-raimann/ActiveRecord)

## API

### Creating a notification
The easiest way is to use the configuration GUI of this plugin, here is an example how to do it with the API:
```php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/classes/Notification/class.srNotification.php');

$notification = new srNotification();
$notification->setName('my_unique_name'); // Use the name as unique identifier to retrieve this object
$notification->setDefaultLanguage('en');
$notification->setTitle('My first notification');
$notification->setDescription("I'm a description");

// Add subject and text for english and german
$notification->setSubject('Hi {{ user.getFullname }}', 'en');
$notification->setText('You joined the course {{ course.getTitle }}', 'en');
$notification->setSubject('Hallo {{ user.getFullname }}', 'de');
$notification->setText('Sie sind nun Mitglied in folgendem Kurs {{ course.getTitle }}', 'de');

$notification->save();
```

### Sending a notification
This plugin offers a interface for sending notifications. Currently there is implemented one concrete class which does send notifications to external E-Mail addresses using the class *ilMimeMail* from ILIAS. There could be a sender for internal mails in ILIAS, SMS, ...

```php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/classes/NotificationSender/class.srNotificationMailSender.php');

// Setup the sender object
$sender = new srNotificationMailSender('sw@studer-raimann.ch', 'no-reply@studer-raimann.ch');

// Get the notification
$notification = srNotification::getInstanceByName('my_unique_name');

// Prepare placeholders
$placeholders = array(
  'user' => new ilObjUser(6),
  'course' => new ilObjCourse(12345),
);

// Send it!
$notification->send($sender, $placeholders);
```

