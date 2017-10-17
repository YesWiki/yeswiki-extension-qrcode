# yeswiki-extension-qrcode
Générateur de QRcodes pour YesWiki.
Scanner de QRcode pour visualiser les échanges de contact **QRcode troc**

## Comment générer des QRcodes
Dans le mode edition d'une page, ajouter l'action `{{qrcode text="le texte du qrcode"}}`

## Configuration du Qrcode troc
1. créer une fiche participant (dans l'exemple id="1")
```
texte***bf_titre***Prénom***60***60*** *** *** ***1***0***
texte***bf_nom***Nom***60***60*** *** *** ***1***0
texte***bf_structure***Structure***60***60*** *** *** ***0***0***
champs_mail***bf_mail***Courriel***40***255*** *** *** ***1*** *** ***+***
texte***bf_telephone***Téléphone***15***15*** *** *** ***0***0
texte***adresse1***Adresse postale***50***50*** *** *** ***1***0
texte***bf_code_postal***Code postal***8***8*** *** *** ***1***0
texte***bf_ville***Ville***50***80*** *** *** ***1***0
texte***bf_pays***Pays***50***80*** *** *** ***1***0
carte_google***bf_latitude***bf_longitude***cartogoogle***
```
2. créer une fiche mise en relation (dans l'exemple id="2")
```
titre***Contact entre {{listefiche1part1}} et {{listefiche1part2}}*** ***
listefiche***1***participant 1*** ***  *** ***part1*** ***1***
listefiche***1***participant 2*** ***  *** ***part2*** ***1***
champs_cache***relation***contact***
```
3. créer un template pour afficher les badges qrcodes
```
<?php
$i = 0;
if (count($fiches)>0) : ?>
<div class="badge-container">
    <?php foreach($fiches as $fiche): $i = $i+1; ?>
          <div class="qrbadge bazar-entry" <?php echo $fiche['html_data'];?>>
            <?php
            $fiche = array_map('trim', $fiche);
            //$fiche = array_map('addslashes', $fiche);
            $qrcode = 'BEGIN:VCARD'."\n";
            $qrcode .= 'VERSION:2.1'."\n";
            $qrcode .= 'FN:'.$fiche['bf_titre'].' '.$fiche['bf_nom']."\n";
            $qrcode .= 'N:'.$fiche['bf_titre'].';'.$fiche['bf_nom']."\n";
            if (!empty($fiche['bf_telephone'])) {
              $qrcode .= 'TEL;HOME;VOICE:'.$fiche['bf_telephone']."\n";
            }
            if (!empty($fiche['bf_mail'])) {
              $qrcode .= 'EMAIL;HOME;INTERNET:'.$fiche['bf_mail']."\n";
            }
            $qrcode .= 'URL:'.$GLOBALS['wiki']->href('', $fiche['id_fiche'])."\n";
            $qrcode .= 'ADR:;;'.$fiche['adresse1'].';'.$fiche['bf_ville'].';;'.$fiche['bf_code_postal'].';'.$fiche['bf_pays']."\n";
            if (!empty($fiche['bf_structure'])) {
              $qrcode .= 'ORG:'.$fiche['bf_structure']."\n";
            }
            $qrcode .= 'END:VCARD'."\n";

            include_once 'tools/qrcode/libs/qrlib.php';
            $cache_image = 'cache'.DIRECTORY_SEPARATOR.'qrcode-'.$GLOBALS['wiki']->getPageTag().'-'.md5($qrcode).'.png';
            if (!file_exists($cache_image)) {
                QRcode::png($qrcode, $cache_image, QR_CORRECTION, 4, 2);
            }
            ?>
          <div class="badge-info">
            <h4><?php echo $fiche['bf_titre'].' '.$fiche['bf_nom']; ?></h4>

            <?php if (!empty($fiche['bf_structure'])) : ?><strong><?php echo $fiche['bf_structure']; ?></strong><br><br><?php endif; ?>
            <?php echo $fiche['bf_fonction']; ?>
          </div>
          <img class="img-qrcode" src="<?php echo $cache_image; ?>" alt="qrcode">
        </div>
        <?php if (($i % 4 == 0)) { echo '<div class="break"></div>'; } ?>
    <?php endforeach; ?>
  </div>
  <style>
  .badge-container {width:210mm;margin:0 auto;}
  .qrbadge {
    border:1px solid #ddd;
    margin: 30mm 9mm 9mm 9mm; 
    padding: 2mm;
    display: inline-block;
    width: 85mm;
    height: 108mm;
    overflow:hidden;
    position: relative;
    font-size: 10pt;
  }
  .badge-info {text-align: center;}
  .img-qrcode {position: absolute; width:60mm; height:60mm; bottom: 0mm; left: 12mm;}
  .qrbadge h4 { font-size: 16pt; color:#61b32e !important; margin: 3mm 0mm; }

  @media print {
     #yw-header, #yw-footer, #yw-topnav, .footer, #search-form, .export-links { display:none; }
     .page, #yw-container, .badge-container {padding:0;width:100%;margin:0;}
     .qrbadge { border:none;}
     /*.qrbadge { width:85mm; height:108mm; margin:30mm 9mm 9mm 9mm; padding: 2mm; font-size: 10pt; border:none; }
     .qrbadge h4 { font-size: 16pt; color:#61b32e; margin: 3mm 5mm; }
     .img-qrcode {position: absolute; width:60mm; height:60mm; bottom: 0mm; left: 12mm;}*/

     .break {
        page-break-after: always;
        page-break-inside: avoid;
        break-after: always;
      }
  }
  </style>
<?php endif;
```
4. faire une page pour gerer les impression et y mettre l'action :
 `{{bazar vue="recherche" voirmenu="0"  id="1" template="qrcode-badges-portrait.tpl.html"}}`
5. faire une page pour scanner les qrcodes et y mettre l'action `{{qrscan idusers="1" idlinks="2"}}`
6. visualiser les resultats sur `https://monsite.com/wakka.php?wiki=PagePrincipale/qrcodetroc`