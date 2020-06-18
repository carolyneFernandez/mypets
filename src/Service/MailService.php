<?php


namespace App\Service;


use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailService extends AbstractController
{

    /**
     * @var Swift_Mailer $mailer
     */
    private $mailer;

    /**
     * @var Swift_Message $mail
     */
    private $mail;

    public function __construct(Swift_Mailer $swift_Mailer)
    {
        $this->mailer = $swift_Mailer;
    }

    /**
     * @param $to
     * @param $subject
     * @param $template
     * @param array $param
     * @param array $attachement
     * @param null $mailFrom
     * @param null $nameFrom
     * @return bool
     */
    public function setAndSendMail($to, $subject, $template, $param = array(), $attachement = array(), $mailFrom = null, $nameFrom = null)
    {

        $this->setMail($to, $subject, $template, $param, $attachement, $mailFrom, $nameFrom);

        return $this->sendMail();

    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $template
     * @param array $param
     * @param array $attachement
     * @param string $mailFrom
     * @param string $nameFrom
     */
    public function setMail($to, $subject, $template, $param = array(), $attachement = array(), $mailFrom = null, $nameFrom = null)
    {
        $this->mail = new Swift_Message($subject);
        if ($mailFrom == null) {
            $this->mail->setFrom($_SERVER['MAILFROM'], $_SERVER['NAMEMAILFROM']);
        } else {
            $this->mail->setFrom($mailFrom, $nameFrom);
        }

        if (!empty($attachement)) {
            $pieceJointe = new \Swift_Attachment($attachement['piece'], $attachement['filename'], $attachement['type']);
            $this->mail->attach($pieceJointe);
        }

        $this->mail->setTo($to)
                   ->setBody($this->renderView($template, $param), 'text/html')
        ;
    }

    /**
     * @return bool
     */
    public function sendMail()
    {
            $this->mailer->send($this->mail);
        try {

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


}