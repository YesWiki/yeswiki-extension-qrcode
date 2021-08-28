<?php

// Verification de securite
if (!defined("WIKINI_VERSION")) {
    die("acc&egrave;s direct interdit");
}

// creation et affichage QRcode du lien de page
$url = $this->Href();
$cacheImage = 'cache/qrcode-'.$this->getPageTag().'-url.svg';
$GLOBALS['qrcode']->generate($url, $cacheImage);
$html = '<img class="right" src="'.$cacheImage.'" title="QRcode de l\'adresse de cette page " alt="'.$url.'" />'."\n";

// Agrégation du QRcode dans le buffer du handler share
$plugin_output_new = preg_replace('/<div class="page">/', '<div class="page">'."\n".utf8_encode($html)."\n", $plugin_output_new);
