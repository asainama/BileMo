<?php

namespace App\Controller;

use App\Entity\Client;
use FOS\RestBundle\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\ResourceValidationException;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Generator\UrlGenerator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

class SecurityController extends AbstractFOSRestController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoderPassword;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ValidatorInterface $validator
     */
    private $validator;

    public function __construct(
        UserPasswordEncoderInterface $encoderPassword,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ) {
        $this->encoderPassword = $encoderPassword;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * Register client
     * @Rest\Post(
     *  "/api/registration",
     *  name="app_registration"
     * )
     * @ParamConverter(
     * "client",
     * class="App\Entity\Client",
     * converter="fos_rest.request_body",
     * options={
     *     "validator"={"groups"="CREATE"}
     *  }
     * )
     * @OA\Response(
     *      response=200,
     *      description="Register client",
     *      @Model(type=Client::class)
     * )
     * @OA\Tag(name="Clients")
     * @Rest\View(StatusCode=201)
     * @Security(name="Bearer")
     * @return \FOS\RestBundle\View\View
     */
    public function registration(Client $client, ConstraintViolationList $violations)
    {
        $this->showErrors($violations);
        $errors = $this->validator->validate($client);
        $this->showErrors($errors);
        $client
            ->setPassword(
                $this->encoderPassword->encodePassword($client, $client->getPassword())
            )
            ->setRoles(array('ROLE_USER'));
        $this->entityManager->persist($client);
        $this->entityManager->flush();
        $context = new Context();
        // $context->setVersion('1.0');
        $context->addGroup('public');
        return $this->view(
            $client,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl(
                    'app_client_detail',
                    ['id' => $client->getId()],
                    UrlGenerator::ABSOLUTE_URL
                )
            ]
        )->setContext($context);
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
}
