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
        $options = [
            'formsIds' => $this->wiki->config['qrcode_config']['relation_form_id'],
        ];
        $query = $this->getService(EntryController::class)
                ->formatQuery(empty($type) ? [] : ['query' => ['bf_relation' => $type]], $_GET);
        if (!empty($query)) {
            $options['queries'] = $query;
        }
        $entries = $this->getService(EntryManager::class)->search($options, true, true);

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
        $output = '<h2>Extension Qrcode</h2>' . "\n";

        $output .= '
        <p>
        <b><code>GET ' . $this->wiki->href('', 'api/relations/{type}') . '</code></b><br />
        Retourne la liste de toutes les relations (dont on peut préciser le type ou laisser vide).
        </p>';

        $output .= '
        <p>
        <b><code>POST ' . $this->wiki->href('', 'api/relations') . '</code></b><br />
        Ajoute une fiche de type relation dans la base de données.
        </p>';

        return $output;
    }
}
