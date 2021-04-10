<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use FOS\RestBundle\Request\ParamFetcher;
use App\Representation\UsersRepresentation;
use Symfony\Component\HttpFoundation\Request;
use App\Exception\ResourceValidationException;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Generator\UrlGenerator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends AbstractFOSRestController
{
    /**
     * Return the user by id
     * @Rest\Get(
     *      path = "/api/users/{id}",
     *      name = "app_user_detail",
     *      requirements = {"id"="\d+"}
     * )
     * @Rest\View()
     */
    public function getUserById(User $user = null)
    {
        $isExist = $this->ifExists($user, User::class);
        if ($isExist) {
            return $isExist;
        }
        return $user;
    }

    /**
     * Return all users
     * @Rest\Get(
     *      path = "/api/users",
     *      name = "app_users_list"
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
     * @Rest\View(serializerGroups={"list"})
     */
    public function getAllUsers(ParamFetcher $paramFetcher, Request $request)
    {
        $page = $request->query->getInt('page', 1);
        /** @var UserRepository $userRepository */
        $userRepository = $this
            ->getDoctrine()
            ->getRepository(User::class);

        $pager = $userRepository
            ->search(
                $paramFetcher->get('keyword'),
                $paramFetcher->get('order'),
                $paramFetcher->get('limit'),
                $paramFetcher->get('offset'),
                $page
            );
        return new UsersRepresentation($pager);
    }

    /**
     * Return all users by client
     * @Rest\Get(
     *      path = "/api/clients/{id}/users",
     *      name = "app_users_list_by_client",
     *      requirements = {"id"="\d+"}
     * )
     * @Rest\View()
     */
    public function getAllUsersByClient(Client $client = null)
    {
        $isExist = $this->ifExists($client, Client::class);
        if ($isExist) {
            return $isExist;
        }
        return ($isExist) ? $isExist : $client->getUsers();
    }

    /**
     * Create User by Client
     * @Rest\Post(
     *      path = "/api/clients/{id}/users",
     *      name = "app_users_create_by_client",
     *      requirements = {"id"="\d+"}
     * )
     * @ParamConverter(
     * "user",
     *  class="App\Entity\User",
     *  converter="fos_rest.request_body",
     *  options={
     *     "validator"={"groups"="CREATEUSER"}
     *  }
     * )
     * @Rest\View(StatusCode=201,serializerGroups={"list"})
     */
    public function setUsersByClient(
        Client $client = null,
        User $user = null,
        ConstraintViolationList $violations,
        ValidatorInterface $validator
    ) {
        $isExist = $this->ifExists($client, Client::class);
        if ($isExist) {
            return $isExist;
        }
        $this->showErrors($violations);
        $errors = $validator->validate($user);
        $this->showErrors($errors);
        $client->addUser($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($client);
        $entityManager->flush();
        return $this->view(
            $user,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl(
                    'app_user_detail',
                    ['id' => $user->getId()],
                    UrlGenerator::ABSOLUTE_URL
                ),
            ]
        );
    }

    /**
     * Delete user by id and by client
     * @Rest\Delete(
     *      path = "/api/clients/{id}/users/{userid}",
     *      name = "app_users_delete_by_client",
     *      requirements = {"id"="\d+", "userid"="\d+"}
     * )
     * @Rest\View()
     */
    public function deleteUserByClient(Client $client = null, int $userid)
    {
        $isExist = $this->ifExists($client, Client::class);
        if ($isExist) {
            return $isExist;
        }
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->findBy(
            ["id" => $userid]
        )[0];
        $isExist = $this->ifExists($user, User::class);
        if ($isExist) {
            return $isExist;
        }
        $client->removeUser($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($client);
        $entityManager->flush();
    }

    /**
     * Delete user by id
     * @Rest\Delete(
     *      path = "/api/users/{id}",
     *      name = "app_users_delete",
     *      requirements = {"id"="\d+"}
     * )
     * @Rest\View()
     */
    public function deleteUser(User $user = null)
    {
        $isExist = $this->ifExists($user, User::class);
        if ($isExist) {
            return $isExist;
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
    }

    /**
     * Handle ResourceValidationException
     * @param ConstraintViolationList $errors
     */
    private function showErrors($errors)
    {
        $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
        if (count($errors)) {
            foreach ($errors as $error) {
                $message .= sprintf(
                    "Field %s: %s",
                    $error->getPropertyPath(),
                    $error->getMessage()
                );
            }
            throw new ResourceValidationException($message);
        }
    }

    /**
     * Check if object is null
     * @param Client|User $object
     * @return \FOS\RestBundle\View\View
     */
    private function ifExists($object, $class)
    {
        if (!$object) {
            return $this->view(
                ($class instanceof User) ? "User not exists" : "Client not exists",
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
