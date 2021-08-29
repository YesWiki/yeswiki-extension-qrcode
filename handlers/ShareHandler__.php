<?php

use YesWiki\Core\YesWikiHandler;

class ShareHandler__ extends YesWikiHandler
{
    public function run()
    {
        // creation et affichage QRcode du lien de page
        $url = $this->wiki->Href();
        $cacheImage = 'cache/qrcode-'.$this->wiki->getPageTag().'-url.svg';
        $GLOBALS['qrcode']->generate($url, $cacheImage);
        $html = '<img class="right" src="'.$cacheImage.'" title="QRcode de l\'adresse de cette page " alt="'.$url.'" />'."\n";

        // Agrégation du QRcode dans le buffer du handler share
        $this->output = preg_replace(
            '/<div class="page">/',
            '<div class="page">'."\n".utf8_encode($html)."\n",
            $this->output
        );
    }
}
