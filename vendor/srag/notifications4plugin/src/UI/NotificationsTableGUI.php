<?php

namespace srag\Notifications4Plugin\Notifications4Plugins\UI;

use srag\CustomInputGUIs\Notifications4Plugins\TableGUI\TableGUI;
use srag\DIC\Notifications4Plugins\Plugin\PluginInterface;
use srag\Notifications4Plugin\Notifications4Plugins\Ctrl\CtrlInterface;
use srag\Notifications4Plugin\Notifications4Plugins\Utils\Notifications4PluginTrait;

/**
 * Class NotificationsTableGUI
 *
 * @package srag\Notifications4Plugin\Notifications4Plugins\UI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class NotificationsTableGUI extends TableGUI {

	use Notifications4PluginTrait;
	const LANG_MODULE = CtrlInterface::LANG_MODULE_NOTIFICATIONS4PLUGIN;
	/**
	 * @var PluginInterface
	 */
	protected $plugin;
	/**
	 * @var callable
	 */
	protected $getNotifications;


	/**
	 * NotificationsTableGUI constructor
	 *
	 * @param PluginInterface $plugin
	 * @param CtrlInterface   $parent
	 * @param string          $parent_cmd
	 * @param callable        $getNotifications
	 */
	public function __construct(PluginInterface $plugin, CtrlInterface $parent, string $parent_cmd, callable $getNotifications) {
		$this->plugin = $plugin;
		$this->getNotifications = $getNotifications;

		parent::__construct($parent, $parent_cmd);
	}


	/**
	 * @inheritdoc
	 */
	protected function getColumnValue(/*string*/
		$column, /*array*/
		$row, /*int*/
		$format = self::DEFAULT_FORMAT): string {
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
		self::dic()->toolbar()->addComponent(self::dic()->ui()->factory()->button()->standard($this->txt("add_notification"), self::dic()->ctrl()
			->getLinkTarget($this->parent_obj, CtrlInterface::CMD_ADD_NOTIFICATION)));
	}


	/**
	 * @inheritdoc
	 */
	protected function initData()/*: void*/ {
		$getNotifications = $this->getNotifications;

		$this->setData($getNotifications());
	}


	/**
	 * @inheritdoc
	 */
	protected function initFilterFields()/*: void*/ {

	}


	/**
	 * @inheritdoc
	 */
	protected function initId()/*: void*/ {
		$this->setId(strtolower(CtrlInterface::NAME) . "_" . $this->plugin->getPluginObject()->getId());
	}


	/**
	 * @inheritdoc
	 */
	protected function initTitle()/*: void*/ {

	}


	/**
	 * @param array $row
	 */
	protected function fillRow(/*array*/
		$row)/*: void*/ {
		self::dic()->ctrl()->setParameter($this->parent_obj, CtrlInterface::GET_PARAM, $row["id"]);

		parent::fillRow($row);

		$this->tpl->setVariable("COLUMN", self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard([
			self::dic()->ui()->factory()->button()->shy($this->txt("edit"), self::dic()->ctrl()
				->getLinkTarget($this->parent_obj, CtrlInterface::CMD_EDIT_NOTIFICATION)),
			self::dic()->ui()->factory()->button()->shy($this->txt("duplicate"), self::dic()->ctrl()
				->getLinkTarget($this->parent_obj, CtrlInterface::CMD_DUPLICATE_NOTIFICATION)),
			self::dic()->ui()->factory()->button()->shy($this->txt("delete"), self::dic()->ctrl()
				->getLinkTarget($this->parent_obj, CtrlInterface::CMD_DELETE_NOTIFICATION_CONFIRM))
		])->withLabel($this->txt("actions"))));
	}


	/**
	 * @inheritdoc
	 */
	public function txt(/*string*/
		$key,/*?string*/
		$default = null): string {
		if ($default !== null) {
			return $this->plugin->translate($key, self::LANG_MODULE, [], true, "", $default);
		} else {
			return $this->plugin->translate($key, self::LANG_MODULE);
		}
	}
}
