<?php

namespace App\Controller\API;

use App\Entity\Car;
use App\Entity\Client;
use App\Form\CarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CarsController extends AbstractController implements ControllerInterface
{
    /**
     * @return Response
     *
     * @Route("cars", methods={"GET"}, name="cars_get")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Car::class);

        return $this->json($repository->findAll());
    }

    /**
     * @param Car $car
     *
     * @return Response
     *
     * @Route("cars/{id}", methods={"GET"}, name="car_show", requirements={"id"="\d+"})
     */
    public function show(Car $car): Response
    {
        return $this->json($car);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     *
     * @return Response
     *
     * @Route("cars", methods={"POST"}, name="car_new")
     */
    public function new(Request $request, SerializerInterface $serializer): Response
    {
        $data = $serializer->decode($request->getContent(), self::JSON_FORMAT);

        $form = $this->createForm(CarType::class, new Car());
        $form->submit($data);
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true)->current()->getMessageParameters(), 400);
        }

        $em = $this->getDoctrine()->getManager();
        $car = $form->getData();
        $extraData = $form->getExtraData();
        if (!empty($extraData['owner_id'])) {
            $owner = $em->find(Client::class, $extraData['owner_id']);
            $car->setOwner($owner);
        }

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
     * @param Car $car
     *
     * @return Response
     *
     * @Route("cars/{id}", methods={"PUT"}, name="car_edit", requirements={"id"="\d+"})
     */
    public function update(Request $request, SerializerInterface $serializer, Car $car): Response
    {
        /** @var Car $updatedCar */
        $data = $serializer->decode($request->getContent(), self::JSON_FORMAT);

        $form = $this->createForm(CarType::class, $car);
        $form->submit($data);
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true)->current()->getMessageParameters(), 400);
        }

        $em = $this->getDoctrine()->getManager();

        $extraData = $form->getExtraData();
        if (!empty($extraData['owner_id'])) {
            $owner = $em->find(Client::class, $extraData['owner_id']);
            $car->setOwner($owner);
        }

        try {
            $em->flush();
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        return $this->json($car);
    }

    /**
     * @param Car $car
     *
     * @return Response
     *
     * @Route("cars/{id}", methods={"DELETE"}, name="car_delete", requirements={"id"="\d+"})
     */
    public function delete(Car $car): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($car);

        try {
            $em->flush();
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        return $this->json(null, 204);
    }
}
