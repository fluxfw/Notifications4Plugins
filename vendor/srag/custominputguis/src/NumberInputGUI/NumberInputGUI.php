<?php

namespace srag\CustomInputGUIs\Notifications4Plugins\NumberInputGUI;

use ilNumberInputGUI;
use ilTableFilterItem;
use srag\DIC\Notifications4Plugins\DICTrait;

/**
 * Class NumberInputGUI
 *
 * @package srag\CustomInputGUIs\Notifications4Plugins\NumberInputGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class NumberInputGUI extends ilNumberInputGUI implements ilTableFilterItem {

	use DICTrait;


	/**
	 * @inheritdoc
	 */
	public function getTableFilterHTML()/*: string*/ {
		return $this->render();
	}
}
