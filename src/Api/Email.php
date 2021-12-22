<?php

namespace Electra\Email\Api;

class Email
{
  /** @var string[] */
  private $recipients = [];
  /** @var string */
  private $fromName;
  /** @var string */
  private $fromEmail;
  /** @var string */
  private $subject;
  /** @var string */
  private $content;
  /** @var string[]  */
  private $cc = [];
  /** @var string[]  */
  private $bcc = [];
  /** @var string[]  */
  private $attachments = [];

  /** @return Email */
  public static function create(): Email
  {
    return new Email();
  }

  /** @return string[] */
  public function getRecipients(): array
  {
    return $this->recipients;
  }

  /**
   * @param string ...$recipient
   *
   * @return $this
   */
  public function addRecipient(string ...$recipient)
  {
    $this->recipients = array_merge($this->recipients, $recipient);
    return $this;
  }

  /** @return string */
  public function getFromName(): string
  {
    return $this->fromName;
  }

  /**
   * @param string $fromName
   *
   * @return $this
   */
  public function setFromName(string $fromName)
  {
    $this->fromName = $fromName;
    return $this;
  }

  /** @return string */
  public function getFromEmail(): string
  {
    return $this->fromEmail;
  }

  /**
   * @param string $fromEmail
   *
   * @return $this
   */
  public function setFromEmail(string $fromEmail)
  {
    $this->fromEmail = $fromEmail;
    return $this;
  }

  /** @return string */
  public function getSubject(): string
  {
    return $this->subject;
  }

  /**
   * @param string $subject
   *
   * @return $this
   */
  public function setSubject(string $subject)
  {
    $this->subject = $subject;
    return $this;
  }

  /** @return string */
  public function getContent(): string
  {
    return $this->content;
  }

  /**
   * @param string $content
   *
   * @return $this
   */
  public function setContent($content)
  {
    $this->content = $content;
    return $this;
  }

  /** @return string[] */
  public function getCc(): array
  {
    return $this->cc;
  }

  /**
   * @param string ...$cc
   *
   * @return $this
   */
  public function addCc(string ...$cc)
  {
    $this->cc = array_merge($this->cc, $cc);
    return $this;
  }

  /** @return string[] */
  public function getBcc(): array
  {
    return $this->bcc;
  }

  /**
   * @param string ...$bcc
   *
   * @return $this
   */
  public function addBcc(string ...$bcc)
  {
    $this->bcc = array_merge($this->bcc, $bcc);
    return $this;
  }

  /** @return string[] */
  public function getAttachments(): array
  {
    return $this->attachments;
  }

  /**
   * @param string $filename
   * @param string $fileContents
   *
   * @return $this
   */
  public function addAttachment(string $filename, string $fileContents)
  {
    $this->attachments[$filename] = $fileContents;
    return $this;
  }


}
