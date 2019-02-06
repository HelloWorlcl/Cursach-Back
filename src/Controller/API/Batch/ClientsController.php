<?php

namespace App\Controller\API\Batch;

use App\Controller\API\ControllerInterface;
use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ClientsController extends AbstractController implements ControllerInterface
{
    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     *
     * @return Response
     *
     * @Route("batch/clients", methods={"PUT", "PATCH"}, name="batch_clients_edit")
     */
    public function update(Request $request, SerializerInterface $serializer): Response
    {
        $data = $serializer->decode($request->getContent(), self::JSON_FORMAT);

        $clearMissing = $request->getMethod() === Request::METHOD_PUT;
        $em = $this->getDoctrine()->getManager();
        $updatedClients = [];

        foreach ($data as $id => $datum) {
            $client = $em->find(Client::class, $id);
            $form = $this->createForm(ClientType::class, $client);

            $form->submit($datum, $clearMissing);

            if (!$form->isValid()) {
                return $this->json($form->getErrors(true)->current()->getMessageParameters(), 400);
            }

            $updatedClients[] = $client;
        }

        try {
            $em->flush();
        } catch(\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        return $this->json($updatedClients);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("batch/clients", methods={"DELETE"}, name="batch_clients_delete")
     */
    public function delete(Request $request): Response
    {
        $ids = $request->get('ids');

        $em = $this->getDoctrine()->getManager();
        foreach($ids as $id) {
            $client = $em->find(Client::class, $id);
            $em->remove($client);
        }

        try {
            $em->flush();
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        return $this->json(null, 204);
    }
}
