<?php

/**
 * QrCardscan action for yeswiki, for scanning a qrcard and propose actions
 *
 * @category Wiki
 * @package  YesWikiQrcode
 * @author   2025 Florian Schmitt <mrflos@yeswiki.pro>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE version 3
 * @link     https://yeswiki.net
 */

use YesWiki\Core\YesWikiAction;
use YesWiki\Bazar\Service\EntryManager;

class QrCardscanAction extends YesWikiAction
{
    public function formatArguments($args): array
    {
        return [
            'speak' => (empty($args['speak']) || $args['speak'] == '0' || $args['speak'] == 'false' || $args['speak'] == 'no') ? 'false' : 'true'
        ];
    }

    public function run(): string
    {
        // Services init
        $entryManager = $this->wiki->services->get(EntryManager::class);

        $output = '';
        $output .= $this->render('@qrcode/qrcardscan.twig', [
            'speak' => $this->arguments['speak'],
        ]);
        return $output;
    }
}
