<?php

namespace TYPO3\CMS\Backend\Tests\Unit\Http\Fixtures;

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

use Psr\Http\Message\ServerRequestInterface;

/**
 * Test fixture
 */
class RouteDispatcherClassInvokeFixture
{
    public function __invoke(ServerRequestInterface $request)
    {
        throw new \RuntimeException(
            'I have been called. Good!',
            1520756623
        );
    }
}
