<?php

// 2019 : 10 inscrits, 15 liens
// 2017 : 5 inscrits, 7 liens
if (empty($_GET['annuaire'])) {
  $annuaire = '10';
} else {
  $annuaire = $_GET['annuaire'];
}
if (empty($_GET['liens'])) {
  $liens = '15';
} else {
  $liens = $_GET['liens'];
}

$output = '';
// on recupere les entetes html mais pas ce qu'il y a dans le body
$header = explode('<body', $this->Header());
$output .= $header[0] . '<body>'."\n";
$output .= '<div id="msg"></div>
<canvas id="canvas-qrcodetroc"></canvas>';
$js = "
  // stockage des utilisateurs pour la visualisation
  var tabUtilisateurs = new Array();

  // informations des utilisateurs
  var tabUsers = new Array();

  // récupération des liens
  var tabLiens = new Array();

  // stockage des nombres de liens
  var tabNbrLiens = new Array();

  // stockage des liens
  var tabAssociations = new Array();

  // stockage des fiches
  var tabFiches = new Array();

  var leSwitch = true;

  function lancement() {
  	getUtilisateurs();
    setTimeout(function() {
      console.log('init canvas');
      var sourceList = ['tools/qrcode/libs/vendor/qrcodetroc.pde','tools/qrcode/libs/vendor/Listeur.pde','tools/qrcode/libs/vendor/Pulseur.pde','tools/qrcode/libs/vendor/Bouton.pde'];
      var canvas = document.querySelector('#canvas-qrcodetroc');
      Processing.loadSketchFromSources(canvas, sourceList);
    }, 200);
  }

  function getUtilisateurs()
  {
  	$.getJSON('wakka.php?wiki=Bazar/json&demand=entries&form=".$annuaire."', function(dataUtilisateurs) {
      tabUsers = dataUtilisateurs;
      var data = Object.values(dataUtilisateurs);
      for (var i=0; i<data.length; i++) {
        tabUtilisateurs[i]=data[i]['bf_titre']+' '+data[i]['bf_nom'];
      }
  	}).then(function() {
      getLiens();
    });
  }


  function getLiens()
  {
  	$.getJSON('wakka.php?wiki=Bazar/json&demand=entries&form=".$liens."', function(dataLiens) {
      var data = Object.values(dataLiens);
      for (var i=0; i<data.length; i++) {
        tabLiens[i] = new Array();
        var u1 = data[i]['listefiche".$annuaire."part1'];
        tabLiens[i][0]=tabUsers[u1]['bf_titre']+' '+tabUsers[u1]['bf_nom'];
        var u2 = data[i]['listefiche".$annuaire."part2'];
        tabLiens[i][1]=tabUsers[u2]['bf_titre']+' '+tabUsers[u2]['bf_nom'];
      }
  	});
  }

  setInterval(getUtilisateurs, 30000);

  lancement();";
//$this->addJavascriptFile('tools/qrcode/libs/vendor/processing-1.2.1.min.js');
$this->addJavascript($js);
$this->addJavascriptFile('tools/qrcode/libs/vendor/processing-1.4.8.min.js');

// on recupere juste les javascripts et la fin des balises body et html
$output .= preg_replace('/^.+<script/Us', '<script', $this->Footer());
die($output);
