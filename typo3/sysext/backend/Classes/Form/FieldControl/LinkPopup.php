<?php

declare(strict_types=1);
namespace TYPO3\CMS\Backend\Form\FieldControl;

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

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

/**
 * Renders the icon with link parameters to open the element browser.
 * Used in InputLinkElement.
 */
class LinkPopup extends AbstractNode
{
    /**
     * Link popup control
     *
     * @return array As defined by FieldControl class
     */
    public function render(): array
    {
        $options = $this->data['renderData']['fieldControlOptions'];

        $title = $options['title'] ?? 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.link';

        $parameterArray = $this->data['parameterArray'];
        $itemName = $parameterArray['itemFormElName'];

        $linkBrowserArguments = [];
        if (isset($options['blindLinkOptions'])) {
            $linkBrowserArguments['blindLinkOptions'] = $options['blindLinkOptions'];
        }
        if (isset($options['blindLinkFields'])) {
            $linkBrowserArguments['blindLinkFields'] = $options['blindLinkFields'];
        }
        if (isset($options['allowedExtensions'])) {
            $linkBrowserArguments['allowedExtensions'] = $options['allowedExtensions'];
        }
        $urlParameters = [
            'P' => [
                'params' => $linkBrowserArguments,
                'table' => $this->data['tableName'],
                'uid' => $this->data['databaseRow']['uid'],
                'pid' => $this->data['databaseRow']['pid'],
                'field' => $this->data['fieldName'],
                'formName' => 'editform',
                'itemName' => $itemName,
                'hmac' => GeneralUtility::hmac('editform' . $itemName, 'wizard_js'),
                'fieldChangeFunc' => $parameterArray['fieldChangeFunc'],
                'fieldChangeFuncHash' => GeneralUtility::hmac(serialize($parameterArray['fieldChangeFunc'])),
            ],
        ];
        /** @var \TYPO3\CMS\Backend\Routing\UriBuilder $uriBuilder */
        $uriBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class);
        $url = (string)$uriBuilder->buildUriFromRoute('wizard_link', $urlParameters);

        $id = StringUtility::getUniqueId('t3js-formengine-fieldcontrol-');

        return [
            'iconIdentifier' => 'actions-wizard-link',
            'title' => $title,
            'linkAttributes' => [
                'id' => htmlspecialchars($id),
                'href' => $url,
                'data-item-name' => htmlspecialchars($itemName),
            ],
            'requireJsModules' => [
                ['TYPO3/CMS/Backend/FormEngine/FieldControl/LinkPopup' => 'function(LinkPopup) {new LinkPopup(' . GeneralUtility::quoteJSvalue('#' . $id) . ');}'],
            ],
        ];
    }
}
