<?php

declare(strict_types=1);

namespace TYPO3\CMS\FrontendLogin\Event;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Notification before a redirect is made.
 */
final class BeforeRedirectEvent
{
    /**
     * @var string
     */
    private $loginType;

    /**
     * @var string
     */
    private $redirectUrl;

    public function __construct(string $loginType, string $redirectUrl)
    {
        $this->loginType = $loginType;
        $this->redirectUrl = $redirectUrl;
    }

    public function getLoginType(): string
    {
        return $this->loginType;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }
}
