<?php

namespace srag\Plugins\Notifications4Plugins\Notification;

use ilNotifications4PluginsConfigGUI;
use ilNotifications4PluginsPlugin;
use ilTable2GUI;
use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class NotificationsTableGUI
 *
 * @package srag\Plugins\Notifications4Plugins\Notification
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class NotificationsTableGUI extends ilTable2GUI {

	use DICTrait;
	use Notifications4PluginsTrait;
	const PLUGIN_CLASS_NAME = ilNotifications4PluginsPlugin::class;
	/**
	 * @var array
	 */
	protected $filter = array();
	/**
	 * All possible columns to display
	 *
	 * @var array
	 */
	protected static $available_columns = array(
		'title',
		'description',
		'name',
		'default_language',
		'languages',
	);
	/**
	 * Columns displayed by table
	 *
	 * @var array
	 */
	protected $columns = array(
		'title',
		'description',
		'name',
		'default_language',
		'languages',
	);


	/**
	 * NotificationsTableGUI constructor
	 *
	 * @param ilNotifications4PluginsConfigGUI $a_parent_obj
	 * @param string                           $a_parent_cmd
	 */
	public function __construct(ilNotifications4PluginsConfigGUI $a_parent_obj, $a_parent_cmd = "") {
		parent::__construct($a_parent_obj, $a_parent_cmd, '');

		$this->setRowTemplate('tpl.row_generic.html', self::plugin()->directory());
		$this->setFormAction(self::dic()->ctrl()->getFormAction($a_parent_obj));
		$this->addColumns();
		$this->buildData();
	}


	/**
	 * @return array
	 */
	public function getSelectableColumns() {
		$columns = array();
		foreach ($this->columns as $column) {
			$columns[$column] = array( 'txt' => self::plugin()->translate($column), 'default' => true );
		}

		return $columns;
	}


	/**
	 * Add selected columns to table
	 */
	protected function addColumns() {
		foreach ($this->columns as $col) {
			if (in_array($col, self::$available_columns) && $this->isColumnSelected($col)) {
				$this->addColumn(self::plugin()->translate($col), $col);
			}
		}

		$this->addColumn(self::plugin()->translate('actions'));
	}


	/**
	 * Return the formatted value
	 *
	 * @param string $value
	 * @param string $col
	 *
	 * @return string
	 */
	protected function getFormattedValue($value, $col) {
		switch ($col) {
			default:
				$value = ($value) ? $value : "&nbsp;";
		}

		return $value;
	}


	/**
	 * @param array $a_set
	 */
	protected function fillRow($a_set) {
		$this->tpl->setCurrentBlock('td');

		foreach ($this->columns as $col) {
			if ($this->isColumnSelected($col)) {
				$this->tpl->setVariable('VALUE', $this->getFormattedValue($a_set[$col], $col));

				$this->tpl->parseCurrentBlock();
			}
		}

		self::dic()->ctrl()->setParameterByClass(ilNotifications4PluginsConfigGUI::class, 'notification_id', $a_set['id']);

		$this->tpl->setVariable("VALUE", self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard([
			self::dic()->ui()->factory()->button()->shy(self::plugin()->translate('edit'), self::dic()->ctrl()
				->getLinkTargetByClass(ilNotifications4PluginsConfigGUI::class, ilNotifications4PluginsConfigGUI::CMD_EDIT)),
			self::dic()->ui()->factory()->button()->shy(self::plugin()->translate('delete'), self::dic()->ctrl()
				->getLinkTargetByClass(ilNotifications4PluginsConfigGUI::class, ilNotifications4PluginsConfigGUI::CMD_CONFIRM_DELETE))
		])->withLabel((self::plugin()->translate("actions")))));
	}


	/**
	 * Build and set data for table
	 */
	protected function buildData() {
		$this->setData(self::notification()->getArrayForTable());
	}
}
