<?php

require_once('srAttributesProvider.php');
require_once('./Services/Link/classes/class.ilLink.php');

/**
 * Class srCourseAttributesProvider
 *
 * Provides attributes for the ILIAS course object
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srCourseAttributesProvider implements srAttributesProvider
{

    /**
     * @var ilObjCourse
     */
    protected $course;

    /**
     * @var string
     */
    protected $date_format = 'H:m:i';

    /**
     * @var string
     */
    protected $time_format = 'd.m.Y';


    /**
     * @param ilObjCourse $course
     * @param string $date_format
     * @param string $time_format
     */
    public function __construct(ilObjCourse $course, $date_format = 'd.m.Y', $time_format = 'H:m:i')
    {
        $this->course = $course;
        $this->date_format = $date_format;
        $this->time_format = $time_format;
    }

    /**
     * Return an attribute by the given name as string
     * If no attribute exists, return null
     *
     * @param string $name
     * @return string|null
     */
    public function getAttribute($name)
    {
        $attributes = array(
            'title' => $this->course->getTitle(),
            'description' => $this->course->getDescription(),
            'important' => $this->course->getImportantInformation(),
            'contact_name' => $this->course->getContactName(),
            'contact_email' => $this->course->getContactEmail(),
            'contact_consultation' => $this->course->getContactConsultation(),
            'contact_phone' => $this->course->getContactPhone(),
            'contact_responsibility' => $this->course->getContactResponsibility(),
            'activation_start' => $this->course->getActivationStart(),
            'activation_end' => $this->course->getActivationEnd(),
            'activation_offline' => $this->course->getOfflineStatus(),
            'subscription_start' => $this->course->getSubscriptionStart(),
            'subscription_end' => $this->course->getSubscriptionEnd(),
            'subscription_password' => $this->course->getSubscriptionPassword(),
            'link' => ilLink::_getStaticLink($this->course->getRefId()),
        );

        return (isset($attributes[$name])) ? (string) $attributes[$name] : null;
    }
}