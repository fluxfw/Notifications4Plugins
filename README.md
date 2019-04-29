OUTDATED!!! The global Notifications4Plugins plugin was replaced by the library (https://github.com/studer-raimann/Notifications4Plugin).

Plugins now have to implement them themselves.

Please do not uninstall this plugin yet so that a migration is possible

## Installation

### Install Notifications4Plugins-Plugin
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
git clone https://github.com/studer-raimann/Notifications4Plugins.git Notifications4Plugins
```
Update, activate and config the plugin in the ILIAS Plugin Administration

### Some screenshots
TODO

## Development interface
See more
https://github.com/studer-raimann/Notifications4Plugin/blob/master/README.md#using-trait

### Dependencies
* ILIAS 5.3 or ILIAS 5.4
* PHP >=7.0
* [composer](https://getcomposer.org)
* [srag/activerecordconfig](https://packagist.org/packages/srag/activerecordconfig)
* [srag/dic](https://packagist.org/packages/srag/dic)
* [srag/librariesnamespacechanger](https://packagist.org/packages/srag/librariesnamespacechanger)
* [srag/notifications4plugin](https://packagist.org/packages/srag/notifications4plugin)
* [srag/removeplugindataconfirm](https://packagist.org/packages/srag/removeplugindataconfirm)

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
