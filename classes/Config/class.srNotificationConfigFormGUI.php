<?php
require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Services/Form/classes/class.ilMultiSelectInputGUI.php');

/**
 * Class srNotificationConfigFormGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotificationConfigFormGUI extends ilPropertyFormGUI
{

    /**
     * @var
     */
    protected $parent_gui;

    /**
     * @var  ilCtrl
     */
    protected $ctrl;

    /**
     * @var ilNotifications4PluginsPlugin
     */
    protected $pl;

    /**
     * @var ilLanguage
     */
    protected $lng;

    /**
     * @var srNotification
     */
    protected $notification;


    /**
     * @param $parent_gui
     * @param srNotification $notification
     */
    public function __construct($parent_gui, srNotification $notification)
    {
        global $ilCtrl, $lng;

        $this->parent_gui = $parent_gui;
        $this->ctrl = $ilCtrl;
        $this->lng = $lng;
        $this->pl = ilNotifications4PluginsPlugin::getInstance();
        $this->notification = $notification;
        $this->setFormAction($this->ctrl->getFormAction($this->parent_gui));
        $this->initForm();
    }



    protected function initForm()
    {
        $this->setTitle('General');

        if ($id = $this->notification->getId()) {
            $item = new ilNonEditableValueGUI('ID');
            $item->setValue($id);
            $this->addItem($item);
        }

        $item = new ilTextInputGUI('Name', 'name');
        $item->setRequired(true);
        $item->setValue($this->notification->getName());
        $item->setInfo('The name is an additional unique identifier of a notification. This allows to easy retrieve srNotification objects by name, not only by ID');
        $this->addItem($item);

        $item = new ilTextInputGUI('Title', 'title');
        $item->setRequired(true);
        $item->setValue($this->notification->getTitle());
        $this->addItem($item);

        $item = new ilTextAreaInputGUI('Description', 'description');
        $item->setValue($this->notification->getDescription());
        $this->addItem($item);

        $item = new ilTextInputGUI('Default Language', 'default_language');
        $item->setInfo('This language is substituted if you try to load a non-existing language or no language is provided');
        $item->setValue($this->notification->getDefaultLanguage());
        $item->setRequired(true);
        $this->addItem($item);

        $item = new ilHiddenInputGUI('notification_id');
        $item->setValue($this->notification->getId());
        $this->addItem($item);

        foreach ($this->notification->getLanguages() as $language) {
            $this->addInputsForLanguage($language);
        }

        // For a new language
        $this->addInputsForLanguage();

        $this->addCommandButtons();
    }


    /**
     * @param string $language
     */
    protected function addInputsForLanguage($language = '')
    {
        $section = new ilFormSectionHeaderGUI();
        $section->setTitle($language ? strtoupper($language) : 'Add new Language');
        $this->addItem($section);

        if (!$language) {
            $item = new ilTextInputGUI('Language', 'language');
            $this->addItem($item);
        }

        $item = new ilTextInputGUI('Subject', 'subject_' . $language);
        $item->setValue($language ? $this->notification->getSubject($language) : '');
        $this->addItem($item);

        $item = new ilTextAreaInputGUI('Text', 'text_' . $language);
        $item->setValue($language ? $this->notification->getText($language) : '');
        $this->addItem($item);
    }


    protected function addCommandButtons()
    {
        $method = $this->notification->getId() ? 'update' : 'create';
        $this->addCommandButton($method, $this->lng->txt('save'));
        $this->addCommandButton('cancel', $this->lng->txt('cancel'));
    }
}