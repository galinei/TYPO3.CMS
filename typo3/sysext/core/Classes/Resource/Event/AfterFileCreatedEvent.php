<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Resource\Event;

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

use TYPO3\CMS\Core\Resource\Folder;

/**
 * This event is fired before a file was created within a Resource Storage / Driver.
 * The folder represents the "target folder".
 *
 * Example: This allows to modify a file or check for an appropriate signature after a file was created in TYPO3.
 */
final class AfterFileCreatedEvent
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var Folder
     */
    private $folder;

    public function __construct(string $fileName, Folder $folder)
    {
        $this->fileName = $fileName;
        $this->folder = $folder;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getFolder(): Folder
    {
        return $this->folder;
    }
}
