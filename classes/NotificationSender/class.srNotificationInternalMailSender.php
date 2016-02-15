<?php

require_once('srNotificationSender.php');
require_once('./Services/Mail/classes/class.ilMail.php');

/**
 * Class srNotificationInternalMailSender
 *
 * Sends the notification internal in ILIAS. Based on the settings, the mail is also forwarded to the users external e-mail address
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotificationInternalMailSender implements srNotificationSender
{

    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var string
     */
    protected $subject = '';

    /**
     * User-ID or login of sender
     *
     * @var int|string
     */
    protected $user_from = 0;

    /**
     * User-ID or login of receiver
     *
     * @var int|string
     */
    protected $user_to = '';

    /**
     * @var ilMail
     */
    protected $mailer;

    /**
     * User-ID or login of cc
     *
     * @var string|int
     */
    protected $cc;

    /**
     * User-ID or login of bcc
     *
     * @var string|int
     */
    protected $bcc;

    /**
     * Store the mail in the sent box of the sender
     *
     * @var bool
     */
    protected $save_in_sent_box = true;


    /**
     * @param int|string $user_from Should be the user-ID from the sender, you can also pass the login
     * @param int|string $user_to Should be the login of the receiver, you can also pass a user-ID
     */
    public function __construct($user_from = 0, $user_to = '')
    {
        if ($user_from) {
            $this->setUserFrom($user_from);
        }
        if ($user_to) {
            $this->setUserTo($user_to);
        }
    }


    /**
     * Save email in sent box of sender?
     *
     * @param bool $state
     */
    public function setSaveInSentBox($state)
    {
        $this->save_in_sent_box = $state;
    }


    /**
     * @return boolean
     */
    public function isSaveInSentBox()
    {
        return $this->save_in_sent_box;
    }


    /**
     * Convert User-ID to login
     *
     * @param $id
     * @return mixed
     */
    protected function id2login($id)
    {
        if (is_numeric($id)) {
            // Need login
            $data = ilObjUser::_lookupName($id);

            return $data['login'];
        }

        return $id;
    }


    /**
     * Send the notification
     *
     * @return bool
     */
    public function send()
    {
        $this->mailer = new ilMail($this->getUserFrom());
        $this->mailer->setSaveInSentbox($this->isSaveInSentBox());

        // Inverted logic: sendMail returns an empty string on success and a error message otherwise ;)
        return !$this->mailer->sendMail(
            $this->getUserTo(),
            $this->getCc(),
            $this->getBcc(),
            $this->getSubject(),
            $this->getMessage(),
            array(), // No attachments supported atm
            array('normal')
        );
    }


    /**
     * Set the message to send
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }


    /**
     * Set the subject for the message
     *
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }


    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }


    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }


    /**
     * @return int|string
     */
    public function getUserFrom()
    {
        return $this->user_from;
    }


    /**
     * @param int|string $user_from
     * @return $this
     */
    public function setUserFrom($user_from)
    {
        if (is_string($user_from) && !is_numeric($user_from)) {
            // Need user-ID
            $user_from = ilObjUser::_lookupId($user_from);
        }
        $this->user_from = (int) $user_from;

        return $this;
    }


    /**
     * @return int|string
     */
    public function getUserTo()
    {
        return $this->user_to;
    }


    /**
     * @param int|string $user_to
     * @return $this
     */
    public function setUserTo($user_to)
    {
        $this->user_to = $this->id2login($user_to);

        return $this;
    }


    /**
     * @return array|string
     */
    public function getCc()
    {
        return $this->cc;
    }


    /**
     * @param array|string $cc
     * @return $this
     */
    public function setCc($cc)
    {
        $this->cc = $this->id2login($cc);

        return $this;
    }


    /**
     * @return array|string
     */
    public function getBcc()
    {
        return $this->bcc;
    }


    /**
     * @param array|string $bcc
     * @return $this
     */
    public function setBcc($bcc)
    {
        $this->bcc = $this->id2login($bcc);

        return $this;
    }


    /**
     * Reset internal state of object, e.g. clear all data (from, to, subject, message etc.)
     *
     * @return $this
     */
    public function reset()
    {
        $this->message = '';
        $this->subject = '';
        $this->user_from = 0;
        $this->user_to = '';
        $this->bcc = '';
        $this->bcc = '';
        $this->save_in_sent_box = true;
        $this->mailer = null;
    }
}