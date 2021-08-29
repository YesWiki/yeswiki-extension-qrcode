<?php

namespace YesWiki\Qrcode\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use YesWiki\Bazar\Controller\EntryController;
use YesWiki\Bazar\Service\EntryManager;
use YesWiki\Core\ApiResponse;
use YesWiki\Core\YesWikiController;

class ApiController extends YesWikiController
{
    /**
     * @Route("/api/relations/{type}", methods={"GET"}, options={"acl":{"public"}})
     */
    public function getAllRelations(string $type = 'contact')
    {
        $entryCache = [];
        $options = [
            'formsIds' => $this->wiki->config['qrcode_config']['relation_form_id'],
        ];
        $query = $this->getService(EntryController::class)
                ->formatQuery(empty($type) ? [] : ['query' => ['bf_relation' => $type]], $_GET);
        if (!empty($query)) {
            $options['queries'] = $query;
        }
        $entries = $this->getService(EntryManager::class)->search($options, true, true);
        foreach ($entries as $k => $e) {
            $entryCache[$e['bf_fiche1']] = isset($entryCache[$e['bf_fiche1']]) ?
                $entryCache[$e['bf_fiche1']] :
                $this->getService(EntryManager::class)->getOne($e['bf_fiche2']);
            $entryCache[$e['bf_fiche2']] = isset($entryCache[$e['bf_fiche2']]) ?
                $entryCache[$e['bf_fiche2']] :
                $this->getService(EntryManager::class)->getOne($e['bf_fiche2']);
            $entries[$k]['entry1'] = $entryCache[$e['bf_fiche1']];
            $entries[$k]['entry2'] = $entryCache[$e['bf_fiche2']];
        }
        return new ApiResponse(empty($entries) ? null : $entries);
    }

    /**
     * @Route("/api/relations", methods={"POST"}, options={"acl":{"public"}})
     */
    public function createRelation()
    {
        $_POST['antispam'] = 1;
        $entry = $this->getService(EntryManager::class)->create(
            $this->wiki->config['qrcode_config']['relation_form_id'],
            $_POST,
            false,
            $_SERVER['HTTP_SOURCE_URL'] ?? null
        );
        if (!$entry) {
            throw new BadRequestHttpException();
        }

        return new ApiResponse(
            ['success' => $this->wiki->Href('', $entry['id_fiche'])],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display Qrcode api documentation
     *
     * @return string
     */
    public function getDocumentation()
    {
        $output = '<h2>'._t('QRCODE_EXTENSION').'</h2>' . "\n";

        $output .= '
        <p>
        <b><code>GET ' . $this->wiki->href('', 'api/relations/{type}') . '</code></b><br />
        '._t('QRCODE_DOC_GET_RELATIONS').'.
        </p>';

        $output .= '
        <p>
        <b><code>POST ' . $this->wiki->href('', 'api/relations') . '</code></b><br />
        '._t('QRCODE_DOC_POST_RELATIONS').'.
        </p>';

        return $output;
    }
}
