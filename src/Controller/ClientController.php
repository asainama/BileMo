<?php

namespace App\Controller;

use App\Entity\Client;
use FOS\RestBundle\Context\Context;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

class ClientController extends AbstractFOSRestController
{
    /**
     * Return client by id
     * @Rest\Get(
     *  path = "/api/clients/{id}",
     *  name = "app_client_detail",
     *  requirements = {"id"="\d+"}
     * )
     * @OA\Response(
     *      response=200,
     *      description="Return client by id",
     *      @Model(type=Client::class)
     * )
     * @OA\Tag(name="Clients")
     * @Security(name="Bearer")
     * @Rest\View
     */
    public function getClientById(Client $client = null)
    {
        if (!$client) {
            return $this->view(
                "Client not exists",
                Response::HTTP_BAD_REQUEST
            );
        }
        return $client;
    }

    /**
     * Return all clients
     * @Rest\Get(
     *  "/api/clients",
     *  name = "app_client_list"
     * )
     * @OA\Response(
     *      response=200,
     *      description="Return all clients",
     *      @Model(type=Client::class)
     * )
     * @OA\Tag(name="Clients")
     * @Security(name="Bearer")
     * @Rest\View()
     */
    public function getAllClients()
    {
        $clients = $this->getDoctrine()->getRepository(Client::class)->findAll();
        $context = new Context();
        $context->addGroup('public');
        return $this->view(
            $clients,
            Response::HTTP_OK
        )->setContext($context);
    }
}
