<?php

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

namespace TYPO3\CMS\Recordlist\Tree\View;

use TYPO3\CMS\Core\Utility\HttpUtility;

/**
 * Extension class for the TBE record browser
 * @internal
 */
class ElementBrowserPageTreeView extends \TYPO3\CMS\Backend\Tree\View\ElementBrowserPageTreeView
{
    /**
     * Returns TRUE if a doktype can be linked (which is always the case here).
     *
     * @param int $doktype Doktype value to test
     * @param int $uid uid to test.
     * @return bool
     */
    public function ext_isLinkable($doktype, $uid)
    {
        return true;
    }

    /**
     * Wrapping the title in a link, if applicable.
     *
     * @param string $title Title, ready for output (already html-escaped)
     * @param array $v The record
     * @param bool $ext_pArrPages If set, pages clicked will return immediately, otherwise reload page.
     * @return string Wrapped title string
     */
    public function wrapTitle($title, $v, $ext_pArrPages = false)
    {
        if ($ext_pArrPages && $v['uid']) {
            $out = '<span data-uid="' . htmlspecialchars($v['uid']) . '" data-table="pages" data-title="' . htmlspecialchars($v['title']) . '">';
            $out .= '<a href="#" data-close="1">' . $title . '</a>';
            $out .= '</span>';
            return $out;
        }

        $parameters = HttpUtility::buildQueryString($this->linkParameterProvider->getUrlParameters(['pid' => $v['uid']]));
        return '<a href="' . htmlspecialchars($this->getThisScript() . $parameters) . '">' . $title . '</a>';
    }
}
