<?php

$relation = empty($_GET['relation']) ? 'contact' : $_GET['relation'];
$refresh = empty($_GET['refresh']) ? '30000' : $_GET['refresh'];
$form = empty($_GET['form']) ? '1300' : $_GET['form'];

$output = '';
// on recupere les entetes html mais pas ce qu'il y a dans le body
$header = explode('<body', $this->Header());
$output .= $header[0] . '<body>'."\n";
$output .= '<canvas id="canvas-qrcodetroc" data-form="'.$form.'" data-relation="'.$relation.'" data-refresh="'.$refresh.'"></canvas>';
$this->addJavascriptFile('tools/qrcode/libs/vendor/processing-1.6.6.min.js');
$this->addJavascriptFile('tools/qrcode/libs/qrcodetroc-visualisation.js');

// on recupere juste les javascripts et la fin des balises body et html
$output .= preg_replace('/^.+<script/Us', '<script', $this->Footer());
die($output);
