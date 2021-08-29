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
        'QRCODE_RELATION_FORM_NAME' => 'Relations QRcodeTROC',
        'QRCODE_RELATION_FORM_DESCRIPTION' => 'Établi un lien entre une fiche bazar et une autre afin d\'être restitué visuellement',
        'QRCODE_EXTENSION' => 'Extension Qrcode',
        'QRCODE_DOC_GET_RELATIONS' => 'Retourne la liste de toutes les relations (dont on peut préciser le type ou laisser vide)',
        'QRCODE_DOC_POST_RELATIONS' => 'Ajoute une fiche de type relation dans la base de données',
        'EDIT_CONFIG_HINT_relation_form_id' => 'Id du formulaire des relations à utiliser par défaut',
        'EDIT_CONFIG_HINT_default_user_form' => 'Id du formulaire d\'annuaire à utiliser par défaut',
        'EDIT_CONFIG_HINT_default_relation_type' => 'Qualification par défaut de la relation',
        'EDIT_CONFIG_HINT_visualisation_refresh_period' => 'Durée en secondes entre chaque requête API de mise à jour',
    )
);
