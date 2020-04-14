<?php

namespace TYPO3\CMS\Scheduler\Example;

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
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\Mailer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\TemplatePaths;

/**
 * Provides testing procedures
 * @internal This class is an example is not considered part of the Public TYPO3 API.
 */
class TestTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
    /**
     * An email address to be used during the process
     *
     * @var string $email
     */
    public $email;

    /**
     * Function executed from the Scheduler.
     * Sends an email
     *
     * @return bool
     */
    public function execute()
    {
        if (!empty($this->email)) {
            // If an email address is defined, send a message to it
            $this->logger->info('[TYPO3\\CMS\\Scheduler\\Example\\TestTask]: Test email sent to "' . $this->email . '"');

            $templateConfiguration = $GLOBALS['TYPO3_CONF_VARS']['MAIL'];
            $templateConfiguration['templateRootPaths'][20] = 'EXT:scheduler/Resources/Private/Templates/Email/';

            if (Environment::isCli()) {
                $calledBy = 'CLI module dispatcher';
                $site = '-';
            } else {
                $calledBy = 'TYPO3 backend';
                $site = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
            }
            $email = GeneralUtility::makeInstance(
                FluidEmail::class,
                GeneralUtility::makeInstance(TemplatePaths::class, $templateConfiguration)
            );
            $email
                ->to($this->email)
                ->subject('SCHEDULER TEST-TASK')
                ->from(new Address($this->email, 'SCHEDULER TEST-TASK'))
                ->setTemplate('TestTask')
                ->assignMultiple(
                    [
                        'data' => [
                            'uid' => $this->taskUid,
                            'site' => $site,
                            'calledBy' => $calledBy,
                            'tstamp' => time(),
                            'maxLifetime' => $this->scheduler->extConf['maxLifetime'],
                        ],
                        'exec' => $this->getExecution()
                    ]
                );

            if ($GLOBALS['TYPO3_REQUEST'] instanceof ServerRequestInterface) {
                $email->setRequest($GLOBALS['TYPO3_REQUEST']);
            }
            GeneralUtility::makeInstance(Mailer::class)->send($email);
            return true;
        }
        // No email defined, just log the task
        $this->logger->warning('[TYPO3\\CMS\\Scheduler\\Example\\TestTask]: No email address given');

        return false;
    }

    /**
     * This method returns the destination mail address as additional information
     *
     * @return string Information to display
     */
    public function getAdditionalInformation()
    {
        return $this->getLanguageService()->sL('LLL:EXT:scheduler/Resources/Private/Language/locallang.xlf:label.email') . ': ' . $this->email;
    }
}
