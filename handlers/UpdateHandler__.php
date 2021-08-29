<?php
/**
 * Handler called after the 'update' handler. Check if the different parts of the Qrcode module exists, and install the
 * needed ones.
 *
 * @category YesWiki
 * @package  qrcode
 * @author   Florian Schmitt <mrflos@lilo.org>
 * @license  https://www.gnu.org/licenses/agpl-3.0.en.html AGPL 3.0
 * @link     https://yeswiki.net
 */

use YesWiki\Core\Service\Performer;
use YesWiki\Core\YesWikiHandler;

class UpdateHandler__ extends YesWikiHandler
{
    public function run()
    {
        /**
         * Constants to define the contents of the LMS forms
         */
        !defined('RELATION_FORM_NAME') && define('RELATION_FORM_NAME', _t('QRCODE_RELATION_FORM_NAME'));
        !defined('RELATION_FORM_DESCRIPTION') && define(
            'RELATION_FORM_DESCRIPTION',
            _t('QRCODE_RELATION_FORM_DESCRIPTION')
        );
        !defined('RELATION_FORM_TEMPLATE') && define('RELATION_FORM_TEMPLATE', 'titre***Relation "{{bf_relation}}" entre {{bf_fiche1}} et {{bf_fiche2}}*** ***
texte***bf_fiche1***Fiche 1***255***255*** *** ***text***1*** *** *** *** *** *** ***
texte***bf_fiche2***Fiche 2***255***255*** *** ***text***1*** *** *** *** *** *** ***
texte***bf_relation***Relation***255***255***contact*** ***text***1*** *** *** *** *** *** ***
acls*** * ***@admins***@admins*** *** *** *** *** *** ***');

        if (!function_exists('YesWiki\checkAndAddForm')) {
            /**
             * Check if a form exists and if not, add it to the nature table
             * @param $plugin_output_new the buffer in which to write
             * @param $formId the ID of the form
             * @param $formName the name of the form
             * @param $formeDescription the description of the form
             * @param $formTemplate the template which describe the fields of the form
             */
            function checkAndAddForm(&$plugin_output_new, $formId, $formName, $formeDescription, $formTemplate)
            {
                // test if the activite form exists, if not, install it
                $result = $GLOBALS['wiki']->Query('SELECT 1 FROM ' . $GLOBALS['wiki']->config['table_prefix'] . 'nature WHERE bn_id_nature = '
        . $formId . ' LIMIT 1');
                if (mysqli_num_rows($result) == 0) {
                    $plugin_output_new .= 'ℹ️ Adding <em>' . $formName . '</em> form into <em>' . $GLOBALS['wiki']->config['table_prefix']
            . 'nature</em> table.<br />';

                    $GLOBALS['wiki']->Query('INSERT INTO ' . $GLOBALS['wiki']->config['table_prefix'] . 'nature (`bn_id_nature` ,`bn_ce_i18n` ,'
            . '`bn_label_nature` ,`bn_template` ,`bn_description` ,`bn_sem_context` ,`bn_sem_type` ,`bn_sem_use_template` ,'
            . '`bn_condition`)'
            . ' VALUES (' . $formId . ', \'fr-FR\', \'' . mysqli_real_escape_string(
                $GLOBALS['wiki']->dblink,
                $formName
            ) . '\', \''
            . mysqli_real_escape_string($GLOBALS['wiki']->dblink, $formTemplate) . '\', \''
            . mysqli_real_escape_string($GLOBALS['wiki']->dblink, $formeDescription) . '\', \'\', \'\', 1, \'\')');

                    $plugin_output_new .= '✅ Done !<br />';
                } else {
                    $plugin_output_new .= '✅ The <em>' . $formName . '</em> form already exists in the <em>' .
            $GLOBALS['wiki']->config['table_prefix'] . 'nature</em> table.<br />';
                }
            }
        }

        $output = '';
        if ($GLOBALS['wiki']->userIsAdmin()) {
            $output .= '<strong>Extension Qrcode</strong><br/>';

            // test if the relation form exists, if not, install it
            checkAndAddForm(
                $output,
                $GLOBALS['wiki']->config['qrcode_config']['relation_form_id'],
                RELATION_FORM_NAME,
                RELATION_FORM_DESCRIPTION,
                RELATION_FORM_TEMPLATE
            );

            // if the QRcodeBadges page doesn't exist, create it with a default version
            if (!$this->wiki->LoadPage('QRcodeBadges')) {
                $output .= 'ℹ️ Adding the <em>QRcodeBadges</em> page<br />';
                $this->wiki->SavePage(
                    'QRcodeBadges',
                    '{{bazarliste search="1" id="1" template="qrcode-badges-vcard.tpl.html"}}'
                );
                $output .= '✅ Done !<br />';
            } else {
                $output .= '✅ The <em>QRcodeBadges</em> page already exists.<br />';
            }

            // if the QRcodeScan page doesn't exist, create it with a default version
            if (!$this->wiki->LoadPage('QRcodeScan')) {
                $output .= 'ℹ️ Adding the <em>QRcodeScan</em> page<br />';
                $this->wiki->SavePage(
                    'QRcodeScan',
                    '{{qrscan}}'
                );
                $output .= '✅ Done !<br />';
            } else {
                $output .= '✅ The <em>QRcodeScan</em> page already exists.<br />';
            }

            // if the PageRapideHaut page doesn't contain QRcodeBadges, we add pages to the menu
            $pageRapideHaut = $this->wiki->LoadPage('PageRapideHaut');
            if (!strstr($pageRapideHaut['body'], 'QRcodeBadges')) {
                $output .= 'ℹ️ Adding menu item in <em>PageRapideHaut</em> for the Qrcode<br />';
                $this->wiki->SavePage(
                    'PageRapideHaut',
                    str_replace(
                        '{{end elem="buttondropdown"}}',
                        ' - ------
 - {{button nobtn="1" icon="fas fa-qrcode" text="Badges Qrcode" link="QRcodeBadges"}}
 - {{button nobtn="1" icon="fas fa-camera" text="Scanner Qrcode" link="QRcodeScan"}}
 - {{button nobtn="1" icon="fab fa-flickr" text="Visualisation Qrcode Troc" link="'.$GLOBALS['wiki']->href('qrcodetroc', 'QRcodeBadges').'"}}
            {{end elem="buttondropdown"}}',
                        $pageRapideHaut['body']
                    )
                );
                $output .= '✅ Done !<br />';
            } else {
                $output .= '✅ The menu items in <em>PageRapideHaut</em> already exist.<br />';
            }
            $output .= '<hr />';
        }

        // add the content before footer
        $this->output = str_replace(
            '<!-- end handler /update -->',
            $output . '<!-- end handler /update -->',
            $this->output
        );
    }
}
