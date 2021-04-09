<?php

namespace App\Controller;

use App\Entity\Client;
use FOS\RestBundle\Context\Context;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class ClientController extends AbstractFOSRestController
{
    /**
     * Return client by id
     * @Rest\Get(
     *  path = "/api/clients/{id}",
     *  name = "app_client_detail",
     *  requirements = {"id"="\d+"}
     * )
     * @Rest\View
     */
    public function getClientById(Client $client)
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
