<?php

namespace App\Controller\API;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientsController extends Controller
{
    const JSON_FORMAT = 'json';

    /**
     * @return Response
     *
     * @Route("clients", methods={"GET"}, name="clients_get")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Client::class);

        return $this->json($repository->findAll());
    }

    /**
     * @param Client $client
     *
     * @return Response
     *
     * @Route("clients/{id}", methods={"GET"}, name="client_show", requirements={"id"="\d+"})
     */
    public function show(Client $client)
    {
        return $this->json($client);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("clients", methods={"POST"}, name="client_new")
     */
    public function new(Request $request, ValidatorInterface $validator)
    {
        $serializer = $this->get('serializer');
        $client = $serializer->deserialize(
            $request->getContent(),
            Client::class,
            self::JSON_FORMAT
        );

        $errors = $validator->validate($client);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        try {
            $em->flush();
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        return $this->json($client);
    }

    /**
     * @param Request $request
     * @param Client $client
     *
     * @return Response
     *
     * @Route("clients/{id}", methods={"PUT"}, name="client_edit_put", requirements={"id"="\d+"})
     */
    public function updatePUT(Request $request, ValidatorInterface $validator, Client $client)
    {
        $serializer = $this->get('serializer');
        /** @var Client $updatedClient */
        $updatedClient = $serializer->deserialize(
            $request->getContent(),
            Client::class,
            self::JSON_FORMAT
        );

        $errors = $validator->validate($updatedClient);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $client->setName($updatedClient->getName())
            ->setEmail($updatedClient->getEmail())
            ->setPhone($updatedClient->getPhone())
            ->setAddress($updatedClient->getAddress())
            ->setAvatar($updatedClient->getAvatar());

        $em = $this->getDoctrine()->getManager();
        try {
            $em->flush();
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        return $this->json($client);
    }

    /**
     * @param Request $request
     * @param Client $client
     *
     * @return Response
     *
     * @Route("clients/{id}", methods={"PATCH"}, name="client_edit_patch", requirements={"id"="\d+"})
     */
    public function updatePATCH(Request $request, ValidatorInterface $validator, Client $client)
    {
        $serializer = $this->get('serializer');
        /** @var Client $updatedClient */
        $updatedClient = $serializer->deserialize(
            $request->getContent(),
            Client::class,
            self::JSON_FORMAT
        );

        $errors = $validator->validate($updatedClient);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        // TODO: implement PATCH logic
        $client->setName($updatedClient->getName())
            ->setEmail($updatedClient->getEmail())
            ->setPhone($updatedClient->getPhone())
            ->setAddress($updatedClient->getAddress())
            ->setAvatar($updatedClient->getAvatar());

        $em = $this->getDoctrine()->getManager();
        try {
            $em->flush();
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        return $this->json($client);
    }

    /**
     * @param Client $client
     *
     * @return Response
     *
     * @Route("clients/{id}", methods={"DELETE"}, name="client_delete", requirements={"id"="\d+"})
     */
    public function delete(Client $client)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($client);

        try {
            $em->flush();
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        return $this->json(null, 204);
    }
}
