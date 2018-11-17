<?php

namespace App\Controller\API;

use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientsController extends Controller implements ControllerInterface
{
    /**
     * @return Response
     *
     * @Route("clients", methods={"GET"}, name="clients_get")
     */
    public function index(): Response
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
    public function show(Client $client): Response
    {
        return $this->json($client);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     *
     * @return Response
     *
     * @Route("clients", methods={"POST"}, name="client_new")
     */
    public function new(Request $request, SerializerInterface $serializer): Response
    {
        $data = $serializer->decode($request->getContent(), self::JSON_FORMAT);

        $form = $this->createForm(ClientType::class, new Client());
        $form->submit($data);
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true)->current()->getMessageParameters(), 400);
        }

        $em = $this->getDoctrine()->getManager();
        $client = $form->getData();
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
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param Client $client
     *
     * @return Response
     *
     * @Route("clients/{id}", methods={"PUT"}, name="client_edit_put", requirements={"id"="\d+"})
     */
    public function updatePUT(
        Request $request,
        SerializerInterface $serializer,
        Client $client
    ): Response {
        $data = $serializer->decode($request->getContent(), self::JSON_FORMAT);

        $form = $this->createForm(ClientType::class, $client);
        $form->submit($data);
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true)->current()->getMessageParameters(), 400);
        }

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
     * @param SerializerInterface $serializer
     * @param Client $client
     *
     * @return Response
     *
     * @Route("clients/{id}", methods={"PATCH"}, name="client_edit_patch", requirements={"id"="\d+"})
     */
    public function updatePATCH(
        Request $request,
        SerializerInterface $serializer,
        Client $client
    ): Response {
        $data = $serializer->decode($request->getContent(), self::JSON_FORMAT);

        $form = $this->createForm(ClientType::class, $client);
        $form->submit($data, false);
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true)->current()->getMessageParameters(), 400);
        }

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
    public function delete(Client $client): Response
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
