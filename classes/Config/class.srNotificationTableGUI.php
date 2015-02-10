<?php

require_once('./Services/Table/classes/class.ilTable2GUI.php');
require_once(dirname(dirname(__FILE__)) . '/Notification/class.srNotification.php');

/**
 * Class srNotificationTableGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotificationTableGUI extends ilTable2GUI
{

    /**
     * @var ilNotifications4PluginsPlugin
     */
    protected $pl;

    /**
     * @var ilCtrl
     */
    protected $ctrl;

    /**
     * @var ilLanguage
     */
    protected $lng;

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
     * @param $a_parent_obj
     * @param string $a_parent_cmd
     */
    public function __construct($a_parent_obj, $a_parent_cmd = "")
    {
        global $ilCtrl, $ilUser, $lng;

        $this->ctrl = $ilCtrl;
        $this->lng = $lng;
        $this->pl = ilNotifications4PluginsPlugin::getInstance();
        parent::__construct($a_parent_obj, $a_parent_cmd, '');

        $this->setRowTemplate('tpl.row_generic.html', $this->pl->getDirectory());
        $this->setFormAction($this->ctrl->getFormAction($a_parent_obj));
        $this->addColumns();
        $this->buildData();
    }


    /**
     * @return array
     */
    public function getSelectableColumns()
    {
        $columns = array();
        foreach ($this->columns as $column) {
            $columns[$column] = array('txt' => $this->pl->txt($column), 'default' => true);
        }

        return $columns;
    }


    /**
     * Add selected columns to table
     *
     */
    protected function addColumns()
    {
        foreach ($this->columns as $col) {
            if (in_array($col, self::$available_columns) && $this->isColumnSelected($col)) {
                $this->addColumn($this->pl->txt($col), $col);
            }
        }

        $this->addColumn($this->pl->txt('actions'));
    }


    /**
     * Return the formatted value
     *
     * @param string $value
     * @param string $col
     * @return string
     */
    protected function getFormattedValue($value, $col)
    {
        switch ($col) {
            default:
                $value = ($value) ? $value : "&nbsp;";
        }

        return $value;
    }


    /**
     * @param array $a_set
     */
    protected function fillRow(array $a_set)
    {
        foreach ($this->columns as $col) {
            if ($this->isColumnSelected($col)) {
                $this->tpl->setCurrentBlock('td');
                $this->tpl->setVariable('VALUE', $this->getFormattedValue($a_set[$col], $col));
                $this->tpl->parseCurrentBlock();
            }
        }

        $this->ctrl->setParameterByClass('ilNotifications4PluginsConfigGUI', 'notification_id', $a_set['id']);
        $edit = $this->ctrl->getLinkTargetByClass('ilNotifications4PluginsConfigGUI', 'edit');
        $delete = $this->ctrl->getLinkTargetByClass('ilNotifications4PluginsConfigGUI', 'confirmDelete');
        $this->tpl->setCurrentBlock('td');
        $this->tpl->setVariable('VALUE', "<a href='{$edit}'>Edit</a> / <a href='{$delete}'>Delete</a>");
        $this->tpl->parseCurrentBlock();
    }


    /**
     * Build and set data for table
     *
     */
    protected function buildData()
    {
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