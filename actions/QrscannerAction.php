<?php

/**
 * Qrscan action for yeswiki, for scanning a qrcode pair and save their relation in a bazar entry
 *
 * @category Wiki
 * @package  YesWikiQrcode
 * @author   2018-2021 Florian Schmitt <mrflos@lilo.org>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE version 3
 * @link     https://yeswiki.net
 */

use YesWiki\Core\YesWikiAction;
use YesWiki\Bazar\Service\EntryManager;

class QrscannerAction extends YesWikiAction
{
    public function run()
    {
        // Services init
        $entryManager = $this->wiki->services->get(EntryManager::class);

        // Parameters init
        $speak = $this->wiki->getParameter('speak');
        if ($speak == '0' or $speak == 'false' or $speak == 'no') {
            $speak = 'false';
        } else {
            $speak = 'true';
        }

        $output = '';
        $output .= $this->render('@qrcode/qrscanner.twig', [
            'speak' => $speak,
        ]);
        return $output;
    }
}
