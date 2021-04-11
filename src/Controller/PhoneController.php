<?php

namespace App\Controller;

use App\Entity\Phone;
use FOS\RestBundle\Request\ParamFetcher;
use App\Representation\PhonesRepresentation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

class PhoneController extends AbstractFOSRestController
{
    /**
     * Return all phones
     * @Rest\Get(
     *      path = "/api/phones",
     *      name = "app_phone_list"
     * )
     * @Rest\QueryParam(
     *     name="keyword",
     *     requirements="[a-zA-Z0-9]",
     *     nullable=true,
     *     description="The keyword to search for."
     * )
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="5",
     *     description="Max number of movies per page."
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="0",
     *     description="The pagination offset"
     * )
     * @OA\Response(
     *      response=200,
     *      description="Return all phones",
     *      @Model(type=Phone::class)
     * )
     * @OA\Tag(name="Phones")
     * @Security(name="Bearer")
     * @Rest\View()
     */
    public function getAllPhones(ParamFetcher $paramFetcher, Request $request)
    {
        $page = $request->query->getInt('page', 1);
        /** @var PhoneRepository $phoneRepository */
        $phoneRepository = $this
            ->getDoctrine()
            ->getRepository(Phone::class);
        $pager = $phoneRepository->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset'),
            $page
        );
        return new PhonesRepresentation($pager);
    }

    /**
     * Return phone by id
     * @Rest\Get(
     *  path = "/api/phones/{id}",
     *  name = "app_phone_detail",
     *  requirements = {"id"="\d+"}
     * )
     * @OA\Response(
     *      response=200,
     *      description="Return the phone by id",
     *      @Model(type=Phone::class)
     * )
     * @OA\Tag(name="Phones")
     * @Security(name="Bearer")
     * @Rest\View()
     */
    public function showAction(Phone $phone = null)
    {
        if (!$phone) {
            return $this->view(
                "Phone not exists",
                Response::HTTP_BAD_REQUEST
            );
        }
        return $phone;
    }
}
