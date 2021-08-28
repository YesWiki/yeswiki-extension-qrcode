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

class QrscanAction extends YesWikiAction
{
    public function run()
    {
        $relation = $this->wiki->getParameter('relation');
        if (empty($relation)) {
            $relation = 'contact';
        }
        $speak = $this->wiki->getParameter('speak');
        if ($speak == '0' or $speak == 'false' or $speak == 'no') {
            $speak = 'false';
        } else {
            $speak = 'true';
        }
        return $this->render('@qrcode/qrscan.twig', [
          'speak' => $speak
        ]);
    }
}
