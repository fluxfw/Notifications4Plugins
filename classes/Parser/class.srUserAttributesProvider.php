<?php

require_once('srAttributesProvider.php');

/**
 * Class srUserAttributesProvider
 *
 * Provides attributes for the ILIAS user object
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srUserAttributesProvider implements srAttributesProvider
{

    /**
     * @var ilObjUser
     */
    protected $user;


    /**
     * @param ilObjUser $user
     */
    public function __construct(ilObjUser $user)
    {
        $this->user = $user;
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
            'title' => $this->user->getTitle(),
            'gender' => $this->user->getGender(),
            'firstname' => $this->user->getFirstname(),
            'lastname' => $this->user->getLastname(),
            'name' => $this->user->getFullname(),
            'full_name' => $this->user->getFullname(),
            'language' => $this->user->getLanguage(),
            'email' => $this->user->getEmail(),
            'phone_home' => $this->user->getPhoneHome(),
            'phone_mobile' => $this->user->getPhoneMobile(),
            'phone_office' => $this->user->getPhoneOffice(),
            'active' => $this->user->getActive(),
            'birthday' => $this->user->getBirthday(),
            'agree_date' => $this->user->getAgreeDate(),
            'city' => $this->user->getCity(),
            'street' => $this->user->getStreet(),
            'country' => $this->user->getCountry(),
            'department' => $this->user->getDepartment(),
            'fax' => $this->user->getFax(),
            'login' => $this->user->getLogin(),
            'zipcode' => $this->user->getZipcode(),
        );

        return (isset($attributes[$name])) ? (string) $attributes[$name] : null;
    }
}