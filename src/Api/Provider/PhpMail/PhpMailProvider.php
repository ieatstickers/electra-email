<?php

namespace Electra\Email\Api\Provider\PhpMail;

use Electra\Email\Api\Email;
use Electra\Email\Api\Provider\EmailProviderInterface;

class PhpMailProvider implements EmailProviderInterface
{
  private function __construct() { }

  public static function init(): EmailProviderInterface
  {
    return new PhpMailProvider();
  }

  /**
   * @param Email $email
   *
   * @return bool
   */
  public function send(Email $email): bool
  {
    $to = implode(', ', $email->getRecipients());
    $content = $email->getContent();

    // Default headers
    $headers = [
      "MIME-Version: 1.0",
    ];

    // If attachments
    if ($email->getAttachments())
    {
      // Generate unique id
      $attachmentsUid = md5(uniqid(time()));
      // Add attachment content type header
      $headers[] = "Content-Type: multipart/mixed;boundary=\"{$attachmentsUid}\"";
      // Format content to include attachments
      $content = $this->addAttachmentsToContent($email, $attachmentsUid);
    }
    else
    {
      // Else add default content type header
      $headers[] = "Content-type:text/html;charset=UTF-8";
    }

    // From
    $headers[] = $this->generateFromHeader($email);

    // CC
    if ($email->getCc())
    {
      $headers[] = $this->generateCcHeader($email);
    }

    // BCC
    if ($email->getBcc())
    {
      $headers[] = $this->generateBccHeader($email);
    }

    return mail($to, $email->getSubject(), $content, implode("\r\n", $headers));
  }

  /**
   * @param Email $email
   *
   * @return string
   */
  private function generateFromHeader(Email $email): string
  {
    $fromHeader = "From: ";

    if ($email->getFromName())
    {
      $fromHeader .= $email->getFromName();
    }

    if ($email->getFromEmail())
    {
      if ($email->getFromName())
      {
        $fromHeader .= " ";
      }

      $fromHeader .= "<{$email->getFromEmail()}>";
    }

      return $fromHeader;
  }

  /**
   * @param Email $email
   *
   * @return string
   */
  private function generateCcHeader(Email $email): string
  {
    $ccRecipients = implode(', ', $email->getCc());
    return "Cc: $ccRecipients";
  }

  /**
   * @param Email $email
   *
   * @return string
   */
  private function generateBccHeader(Email $email): string
  {
    $bccRecipients = implode(', ', $email->getBcc());
    return "Bcc: $bccRecipients";
  }

  /**
   * @param Email  $email
   * @param string $attachmentsUid
   *
   * @return string
   */
  private function addAttachmentsToContent(Email $email, string $attachmentsUid): string
  {
    $content = $email->getContent();

    $prependToContent = [
      "--$attachmentsUid",
      "Content-type:text/html;charset=UTF-8",
      "Content-Transfer-Encoding: 7bit"
    ];

    $content = implode("\r\n", $prependToContent)
      . "\r\n\r\n"
      . $content
      . "\r\n\r\n";

    foreach ($email->getAttachments() as $filename => $attachment)
    {
      $encodedFileContents = chunk_split(base64_encode($attachment));

      $attachmentAppendToContent = [
        "--$attachmentsUid",
        "Content-Type: application/octet-stream; name=\"$filename\"",
        "Content-Transfer-Encoding: base64",
        "Content-Disposition: attachment; filename=\"$filename\"\r\n",
        "$encodedFileContents\r\n"
      ];

      $content .= implode("\r\n", $attachmentAppendToContent);
    }

    $content .= "--{$attachmentsUid}--";

    return $content;
  }
}
