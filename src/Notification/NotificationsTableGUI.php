<?php

namespace srag\Plugins\Notifications4Plugins\Notification;

use ilLinkButton;
use ilNotifications4PluginsConfigGUI;
use ilNotifications4PluginsPlugin;
use srag\ActiveRecordConfig\Notifications4Plugins\ActiveRecordConfigTableGUI;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class NotificationsTableGUI
 *
 * @package srag\Plugins\Notifications4Plugins\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class NotificationsTableGUI extends ActiveRecordConfigTableGUI {

	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
	const LANG_MODULE = ilNotifications4PluginsConfigGUI::LANG_MODULE_NOTIFICATIONS4PLUGIN;


	/**
	 * @inheritdoc
	 */
	protected function getColumnValue(/*string*/
		$column, /*array*/
		$row, /*bool*/
		$raw_export = false): string {
		switch ($column) {
			default:
				$column = $row[$column];
				break;
		}

		return strval($column);
	}


	/**
	 * @inheritdoc
	 */
	public function getSelectableColumns2(): array {
		$columns = [
			"title" => "title",
			"description" => "description",
			"name" => "name",
			"default_language" => "default_language",
			"languages" => "languages"
		];

		$columns = array_map(function (string $key): array {
			return [
				"id" => $key,
				"default" => true,
				"sort" => ($key !== "languages")
			];
		}, $columns);

		return $columns;
	}


	/**
	 * @inheritdoc
	 */
	protected function initColumns()/*: void*/ {
		parent::initColumns();

		$this->addColumn($this->txt("actions"));

		$this->setDefaultOrderField("title");
		$this->setDefaultOrderDirection("asc");
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		$add_notification = ilLinkButton::getInstance();
		$add_notification->setCaption($this->txt("add_notification"), false);
		$add_notification->setUrl(self::dic()->ctrl()->getLinkTarget($this->parent_obj, ilNotifications4PluginsConfigGUI::CMD_ADD_NOTIFICATION));
		self::dic()->toolbar()->addButtonInstance($add_notification);
	}


	/**
	 * @inheritdoc
	 */
	protected function initData()/*: void*/ {
		$this->setData(self::notification()->getArrayForTable());
	}


	/**
	 * @inheritdoc
	 */
	protected function initId()/*: void*/ {
		$this->setId("notifications4plugin");
	}


	/**
	 * @param array $row
	 */
	protected function fillRow(/*array*/
		$row)/*: void*/ {
		self::dic()->ctrl()->setParameterByClass(ilNotifications4PluginsConfigGUI::class, ilNotifications4PluginsConfigGUI::GET_PARAM, $row["id"]);

		parent::fillRow($row);

		$this->tpl->setVariable("COLUMN", self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard([
			self::dic()->ui()->factory()->button()->shy($this->txt("edit"), self::dic()->ctrl()
				->getLinkTargetByClass(ilNotifications4PluginsConfigGUI::class, ilNotifications4PluginsConfigGUI::CMD_EDIT_NOTIFICATION)),
			self::dic()->ui()->factory()->button()->shy($this->txt("duplicate"), self::dic()->ctrl()
				->getLinkTargetByClass(ilNotifications4PluginsConfigGUI::class, ilNotifications4PluginsConfigGUI::CMD_DUPLICATE_NOTIFICATION)),
			self::dic()->ui()->factory()->button()->shy($this->txt("delete"), self::dic()->ctrl()
				->getLinkTargetByClass(ilNotifications4PluginsConfigGUI::class, ilNotifications4PluginsConfigGUI::CMD_DELETE_NOTIFICATION_CONFIRM))
		])->withLabel($this->txt("actions"))));
	}
}
