<?php

require_once('./Services/Mail/classes/class.ilMimeMail.php');
require_once('srNotificationSender.php');

/**
 * Class srNotificationMailSender
 *
 * Sends the notification to an external E-Mail address using the ILIAS mailer class
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srNotificationMailSender implements srNotificationSender
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
     * @var string|array
     */
    protected $to;

    /**
     * @var string
     */
    protected $from = '';

    /**
     * @var ilMimeMail
     */
    protected $mailer;

    /**
     * @var array
     */
    protected $attachments = array();

    /**
     * @var string|array
     */
    protected $cc = array();

    /**
     * @var string|array
     */
    protected $bcc = array();

    /**
     * @param string|array $to
     * @param string $from
     */
    public function __construct($to, $from = '')
    {
        $this->to = $to;
        $this->from = $from;
        $this->mailer = new ilMimeMail();
    }


    /**
     * Send the notification
     *
     * @return bool
     */
    public function send()
    {
        global $ilias;

        $this->mailer->To($this->to);
        $from = ($this->from) ? $this->from : $ilias->getSetting('mail_external_sender_noreply');
        $this->mailer->From($from);
        $this->mailer->Cc($this->cc);
        $this->mailer->Bcc($this->bcc);
        $this->mailer->Subject($this->subject);
        $this->mailer->Body($this->message);
        foreach ($this->attachments as $attachment) {
            $this->mailer->Attach($attachment);
        }

        return $this->mailer->Send();
    }


    /**
     * Add an attachment
     *
     * @param string $file Full path of the file to attach
     */
    public function addAttachment($file)
    {
        if (is_file($file)) {
            $this->attachments[] = $file;
        }
    }


    /**
     * Set the message to send
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


    /**
     * Set the subject for the message
     *
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
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
     */
    public function setCc($cc)
    {
        $this->cc = $cc;
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
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
    }

}