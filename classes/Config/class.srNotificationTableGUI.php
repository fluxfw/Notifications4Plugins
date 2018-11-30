<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use srag\DIC\Notifications4Plugins\DICTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class srNotificationTableGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotificationTableGUI extends ilTable2GUI {

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
	 * @param        $a_parent_obj
	 * @param string $a_parent_cmd
	 */
	public function __construct($a_parent_obj, $a_parent_cmd = "") {
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
	 *
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
		foreach ($this->columns as $col) {
			if ($this->isColumnSelected($col)) {
				$this->tpl->setCurrentBlock('td');
				$this->tpl->setVariable('VALUE', $this->getFormattedValue($a_set[$col], $col));
				$this->tpl->parseCurrentBlock();
			}
		}

		self::dic()->ctrl()->setParameterByClass(ilNotifications4PluginsConfigGUI::class, 'notification_id', $a_set['id']);
		$edit = self::dic()->ctrl()->getLinkTargetByClass(ilNotifications4PluginsConfigGUI::class, ilNotifications4PluginsConfigGUI::CMD_EDIT);
		$delete = self::dic()->ctrl()
			->getLinkTargetByClass(ilNotifications4PluginsConfigGUI::class, ilNotifications4PluginsConfigGUI::CMD_CONFIRM_DELETE);
		$this->tpl->setCurrentBlock('td');
		$this->tpl->setVariable('VALUE', "<a href='{$edit}'>" . self::plugin()->translate('edit') . "</a> / <a href='{$delete}'>" . self::plugin()
				->translate('delete') . "</a>");
		$this->tpl->parseCurrentBlock();
	}


	/**
	 * Build and set data for table
	 *
	 */
	protected function buildData() {
		$data = array();
		$notifications = srNotification::get();
		foreach ($notifications as $notification) {
			$row = array();
			$row['id'] = $notification->getId();
			$row['title'] = $notification->getTitle();
			$row['name'] = $notification->getName();
			$row['description'] = $notification->getDescription();
			$row['default_language'] = $notification->getDefaultLanguage();
			$row['languages'] = implode(', ', $notification->getLanguages());
			$data[] = $row;
		}
		$this->setData($data);
	}
}
