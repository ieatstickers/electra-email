<?php

namespace Electra\Email\Api;

use Electra\Email\Api\Provider\EmailProviderInterface;

class EmailApi
{
  /** @var EmailProviderInterface */
  private $provider;

  private function __construct(EmailProviderInterface $provider)
  {
    $this->provider = $provider;
  }

  /**
   * @param EmailProviderInterface $provider
   *
   * @return EmailApi
   */
  public static function init(EmailProviderInterface $provider): EmailApi
  {
    return new EmailApi($provider);
  }

  /**
   * @param Email $email
   *
   * @return bool
   */
  public function send(Email $email): bool
  {
    return $this->provider->send($email);
  }



}
