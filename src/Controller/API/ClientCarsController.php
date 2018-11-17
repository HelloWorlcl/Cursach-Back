<?php

namespace App\Controller\API;

use App\Entity\Car;
use App\Entity\Client;
use App\Form\CarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\SerializerInterface;

class ClientCarsController extends AbstractController implements ControllerInterface
{
    /**
     * @param Client $client
     *
     * @return Response
     *
     * @Route("/clients/{id}/cars", methods={"GET"}, name="client_cars_list", requirements={"id"="\d+"})
     */
    public function index(Client $client): Response
    {
        $em = $this->getDoctrine()->getRepository(Car::class);

        return $this->json($em->findBy(['owner' => $client]));
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param Client $client
     *
     * @return Response
     *
     * @Route(
     *     "/clients/{id}/cars",
     *     methods={"POST"},
     *     name="client_car_new",
     *     requirements={"id"="\d+"}
     * )
     */
    public function new(Request $request, SerializerInterface $serializer, Client $client): Response
    {
        $data = $serializer->decode($request->getContent(), self::JSON_FORMAT);

        $form = $this->createForm(CarType::class, new Car());
        $form->submit($data);
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true)->current()->getMessageParameters(), 400);
        }

        $em = $this->getDoctrine()->getManager();
        $car = $form->getData();
        $car->setOwner($client);
        $em->persist($car);
        try {
            $em->flush();
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        return $this->json($car);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param Client $client
     * @param Car $car
     *
     * @return Response
     *
     * @Route(
     *     "/clients/{clientId}/cars/{carId}",
     *     methods={"PATCH"},
     *     name="client_car_put",
     *     requirements={"client_id"="\d+", "car_id"="\d+"}
     * )
     *
     * @ParamConverter("client", options={"mapping": {"clientId" : "id"}})
     * @ParamConverter("car", options={"mapping": {"carId" : "id"}})
     */
    public function update(
        Request $request,
        SerializerInterface $serializer,
        Client $client,
        Car $car
    ): Response {
        $data = $serializer->decode($request->getContent(), self::JSON_FORMAT);

        $form = $this->createForm(CarType::class, new Car());
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

        return $this->json($car);
    }

    /**
     * @param Client $client
     * @param Car $car
     *
     * @return Response
     *
     * @Route(
     *     "/clients/{clientId}/cars/{carId}",
     *     methods={"DELETE"},
     *     name="client_car_delete",
     *     requirements={"clientId"="\d+", "carId"="\d+"}
     * )
     *
     * @ParamConverter("client", options={"mapping": {"clientId" : "id"}})
     * @ParamConverter("car", options={"mapping": {"carId" : "id"}})
     */
    public function delete(Client $client, Car $car): Response
    {
        $em = $this->getDoctrine()->getManager();
        $owner = $car->getOwner();
        if ($owner && $owner->getId() === $client->getId()) {
            $car->setOwner(null);

            try {
                $em->flush();
            } catch (\Exception $e) {
                return $this->json($e->getMessage(), 400);
            }

            return $this->json($car);
        }

        return $this->json('Current user has not such permission', 403);
    }
}
