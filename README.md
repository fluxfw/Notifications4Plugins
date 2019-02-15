# Notifications4Plugins

This plugin offers a quick and easy way to create notifications (subject & text) in any language in the configuration screen in ILIAS. The text of the notifications is parsed by default with the [Twig template engine!](http://twig.sensiolabs.org/), meaning the developer can replace placeholders and use if statements and loops. The API offers easy methods to send the notifications.

## API

### Create a notification
The easiest way to create new notifications is to use the GUI of this plugin, here is an example how to do it with the API:
```php
require_once __DIR__ . "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/vendor/autoload.php";

use srag\Plugins\Notifications4Plugins\Notification\srNotification;

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

$notification->store();
```

### Send a notification
This plugin introduces a dedicated interface for sending notifications. Currently there is implemented one concrete class which does send notifications to external E-Mail addresses using the class `ilMimeMail` from ILIAS. There could be a sender for internal mails in ILIAS, SMS and so on.

```php
require_once __DIR__ . "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/vendor/autoload.php";

use srag\Plugins\Notifications4Plugins\Notification\srNotification;
use srag\Plugins\Notifications4Plugins\NotificationSender\srNotificationMailSender;

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

### Dependencies
* ILIAS 5.2 or ILIAS 5.3
* PHP >=5.6
* [composer](https://getcomposer.org)
* [srag/dic](https://packagist.org/packages/srag/dic)
* [srag/librariesnamespacechanger](https://packagist.org/packages/srag/librariesnamespacechanger)
* [srag/removeplugindataconfirm](https://packagist.org/packages/srag/removeplugindataconfirm)
* [twig/twig](https://packagist.org/packages/twig/twig)

Please use it for further development!

### Adjustment suggestions
* Adjustment suggestions by pull requests on https://git.studer-raimann.ch/ILIAS/Plugins/Notifications4Plugins/tree/develop
* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/PLNOTIFICATION__
* Bug reports under https://jira.studer-raimann.ch/projects/PLNOTIFICATION
* For external users you can report it at https://plugins.studer-raimann.ch/goto.php?target=uihk_srsu_PLNOTIFICATION

### Development
If you want development in this plugin you should install this plugin like follow:

Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
git clone -b develop git@git.studer-raimann.ch:ILIAS/Plugins/Notifications4Plugins.git Notifications4Plugins
```

### ILIAS Plugin SLA

Wir lieben und leben die Philosophie von Open Source Software! Die meisten unserer Entwicklungen, welche wir im Kundenauftrag oder in Eigenleistung entwickeln, stellen wir öffentlich allen Interessierten kostenlos unter https://github.com/studer-raimann zur Verfügung.

Setzen Sie eines unserer Plugins professionell ein? Sichern Sie sich mittels SLA die termingerechte Verfügbarkeit dieses Plugins auch für die kommenden ILIAS Versionen. Informieren Sie sich hierzu unter https://studer-raimann.ch/produkte/ilias-plugins/plugin-sla.

Bitte beachten Sie, dass wir nur Institutionen, welche ein SLA abschliessen Unterstützung und Release-Pflege garantieren.
