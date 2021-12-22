<?php

namespace Electra\Email\Api\Provider;

use Electra\Email\Api\Email;

interface EmailProviderInterface
{
  /** @return EmailProviderInterface */
  public static function init(): EmailProviderInterface;

  /**
   * @param Email $email
   *
   * @return bool
   */
  public function send(Email $email): bool;
}
