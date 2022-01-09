<?php

namespace Electra\Email\Api\Provider\PhpMailer;

use Electra\Email\Api\Email;
use Electra\Email\Api\Provider\EmailProviderInterface;
use PHPMailer\PHPMailer\PHPMailer;

class PhpMailerProvider implements EmailProviderInterface
{
  /** @var string */
  protected $smtpUsername;
  /** @var string */
  protected $smtpPassword;
  /** @var string */
  protected $smtpHost;
  /** @var int */
  protected $smtpPort;

  private function __construct(
    string $smtpUsername,
    string $smtpPassword,
    string $smtpHost,
    int $smtpPort
  )
  {
    $this->smtpUsername = $smtpUsername;
    $this->smtpPassword = $smtpPassword;
    $this->smtpHost = $smtpHost;
    $this->smtpPort = $smtpPort;
  }

  public static function init(
    string $smtpUsername,
    string $smtpPassword,
    string $smtpHost,
    int $smtpPort
  ): EmailProviderInterface
  {
    return new PhpMailerProvider($smtpUsername, $smtpPassword, $smtpHost, $smtpPort);
  }

  /**
   * @param Email $email
   *
   * @return bool
   */
  public function send(Email $email): bool
  {
    try {
      // Setup PHPMailer
      $mail = new PHPMailer(true);
      $mail->isSMTP();
      $mail->Username = $this->smtpUsername;
      $mail->Password = $this->smtpPassword;
      $mail->Host = $this->smtpHost;
      $mail->Port = $this->smtpPort;
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = 'tls';

      // From
      $mail->setFrom($email->getFromEmail(), $email->getFromName());

      // Add recipients
      foreach($email->getRecipients() as $recipient)
      {
        $mail->addAddress($recipient);
      }

      // Add CC
      foreach($email->getCc() as $cc)
      {
        $mail->addCC($cc);
      }

      // Add BCC
      foreach($email->getBcc() as $bcc)
      {
        $mail->addBCC($bcc);
      }

      // Subject
      $mail->Subject = $email->getSubject();

      // Body/Content
      $mail->isHTML(true);
      $mail->Body = $email->getContent();

      // Attachments
      foreach($email->getAttachments() as $attachmentFilename => $attachment)
      {
        $mail->addStringAttachment(base64_encode($attachment), $attachmentFilename);
      }

      $mail->send();
    }
    catch(\Exception $exception)
    {
      return false;
    }

    return true;
  }
}
