<?php
/*vim: set expandtab tabstop=4 shiftwidth=4: */
// +------------------------------------------------------------------------------------------------------+
// | PHP version 5                                                                                        |
// +------------------------------------------------------------------------------------------------------+
// | Copyright (C) 2012 Outils-Réseaux (accueil@outils-reseaux.org)                                       |
// +------------------------------------------------------------------------------------------------------+
// | This library is free software; you can redistribute it and/or                                        |
// | modify it under the terms of the GNU Lesser General Public                                           |
// | License as published by the Free Software Foundation; either                                         |
// | version 2.1 of the License, or (at your option) any later version.                                   |
// |                                                                                                      |
// | This library is distributed in the hope that it will be useful,                                      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of                                       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU                                    |
// | Lesser General Public License for more details.                                                      |
// |                                                                                                      |
// | You should have received a copy of the GNU Lesser General Public                                     |
// | License along with this library; if not, write to the Free Software                                  |
// | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA                            |
// +------------------------------------------------------------------------------------------------------+
//
/**
* Fichier de traduction en francais de l'extension Login
*
*@package       qrcode
*@author        Florian Schmitt <mrflos@lilo.org>
*@copyright     2017 Outils-Réseaux
*/

$GLOBALS['translations'] = array_merge(
    $GLOBALS['translations'],
    array(
        'QR_CODE_ERROR_MISSING_PARAM' => 'ERREUR action qrcode : pas de texte saisi (parametre text=\"\" manquant)',
        'QR_MISSING_PARAM_IDLINKS' => 'le paramêtre "idlinks", indiquant la base de donnees des liens, est manquant.',
        'QR_MISSING_PARAM_IDUSERS' => 'le paramêtre "idusers", indiquant la base de donnees des participants, est manquant.',
        'QR_CODE_PAGE' => 'QRcode de l\'adresse de cette page',
        'QR_SCAN_FIRST' => 'Scanner le Q.R. code du premier participant',
        'QR_FIRST_INSTRUCTIONS' => 'Veuillez passer votre badge d\'inscription près de la webcam afin que le Q.R. code soit visible dans la vidéo.',
        'QR_SCAN_SECOND' => 'Scanner le Q.R. code du second participant',
        'QR_SECOND_INSTRUCTIONS' => 'Veuillez passer un second badge d\'inscription près de la webcam afin que le Q.R. code soit visible dans la vidéo.',
        'QR_FINISH' => 'C\'est fini !',
        'QR_FINAL_INSTRUCTIONS' => 'Votre mise en lien est effective! Un email de contact a été envoyé à chacun des participants.',
        'QR_RESET' => 'Recommencer',
    )
);
