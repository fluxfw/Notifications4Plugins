# Notifications4Plugins

This plugin offers a quick and easy way to create notifications (subject & text) in any language in the configuration screen in ILIAS. The text of the notifications is parsed by default with the [Twig template engine!](http://twig.sensiolabs.org/), meaning the developer can replace placeholders and use if statements and loops. The API offers easy methods to send the notifications.

## Requirements
* ActiveRecord for ILIAS < 5 (https://github.com/studer-raimann/ActiveRecord)

## API

### Create a notification
The easiest way to create new notifications is to use the GUI of this plugin, here is an example how to do it with the API:
```php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/classes/Notification/class.srNotification.php');

$notification = new srNotification();
$notification->setName('my_unique_name'); // Use the name as unique identifier to retrieve this object later
$notification->setDefaultLanguage('en'); // The text of the default language gets substituted if you try to get the notification of a langauge not available
$notification->setTitle('My first notification');
$notification->setDescription("I'm a description");

// Add subject and text for english and german
$notification->setSubject('Hi {{ user.getFullname }}', 'en');
$notification->setText('You joined the course {{ course.getTitle }}', 'en');
$notification->setSubject('Hallo {{ user.getFullname }}', 'de');
$notification->setText('Sie sind nun Mitglied in folgendem Kurs {{ course.getTitle }}', 'de');

$notification->save();
```

### Send a notification
This plugin introduces a dedicated interface for sending notifications. Currently there is implemented one concrete class which does send notifications to external E-Mail addresses using the class `ilMimeMail` from ILIAS. There could be a sender for internal mails in ILIAS, SMS and so on.

```php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/classes/NotificationSender/class.srNotificationMailSender.php');

// Setup the sender object, in this case we send the notification as external mail to sw@studer-raimann.ch
$sender = new srNotificationMailSender('sw@studer-raimann.ch', 'no-reply@studer-raimann.ch');

// Prepare placeholders, note that the keys are the same 
$placeholders = array(
  'user' => new ilObjUser(6),
  'course' => new ilObjCourse(12345),
);

// Get the notification by name and sent it in english first (default langauge) and in german again
$notification = srNotification::getInstanceByName('my_unique_name');
$notification->send($sender, $placeholders);
$notification->send($sender, $placeholders, 'de');
```

### Get parsed subject and text of a notification
You can get the parsed subject and text from a notification, for example to display it on screen.

```php
$notification = srNotification::getInstanceByName('my_unique_name');
$text = $notification->parseText(array(
  'course' => new ilObjCourse(1234),
  'user' => new ilObjUser(6),
));
```

### Implement a new sender object
Your class must implement the interface `srNotificationsender` and implement the following methods:
```php
    /**
     * Send the notification
     *
     * @return bool
     */
    public function send();

    /**
     * Set the message to send
     *
     * @param string $message
     */
    public function setMessage($message);

    /**
     * Set the subject for the message
     *
     * @param string $subject
     */
    public function setSubject($subject);
```

### Hinweis Plugin-Patenschaft
Grundsätzlich veröffentlichen wir unsere Plugins (Extensions, Add-Ons), weil wir sie für alle Community-Mitglieder zugänglich machen möchten. Auch diese Extension wird der ILIAS Community durch die studer + raimann ag als open source zur Verfügung gestellt. Diese Plugin hat noch keinen Plugin-Paten. Das bedeutet, dass die studer + raimann ag etwaige Fehlerbehebungen, Supportanfragen oder die Release-Pflege lediglich für Kunden mit entsprechendem Hosting-/Wartungsvertrag leistet. Falls Sie nicht zu unseren Hosting-Kunden gehören, bitten wir Sie um Verständnis, dass wir leider weder kostenlosen Support noch Release-Pflege für Sie garantieren können.

Sind Sie interessiert an einer Plugin-Patenschaft (https://studer-raimann.ch/produkte/ilias-plugins/plugin-patenschaften/ ) Rufen Sie uns an oder senden Sie uns eine E-Mail.
