<?php

namespace App\Controller\API;

use App\Entity\Breakdown;
use App\Entity\Car;
use App\Entity\CarBreakdown;
use App\Form\CarBreakdownType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\SerializerInterface;

class CarsBreakdownsController extends AbstractController implements ControllerInterface
{
    /**
     * @param Car $car
     *
     * @return Response
     *
     * @Route("/cars/{id}/breakdowns", methods={"GET"}, name="car_breakdowns_list", requirements={"id"="\d+"})
     */
    public function index(Car $car): Response
    {
        $em = $this->getDoctrine()->getRepository(CarBreakdown::class);

        return $this->json($em->findBy(['car' => $car]));
    }

    /**
     * @param Car $car
     * @param CarBreakdown $carBreakdown
     *
     * @return Response
     *
     * @Route(
     *     "/cars/{carId}/breakdowns/{carBreakdownId}",
     *     methods={"GET"},
     *     name="car_breakdown_get",
     *     requirements={"carId"="\d+", "carBreakdownId"="\d+"}
     * )
     *
     * @ParamConverter("car", options={"mapping": {"carId" : "id"}})
     * @ParamConverter("carBreakdown", options={"mapping": {"carBreakdownId" : "id"}})
     */
    public function show(Car $car, CarBreakdown $carBreakdown): Response
    {
        return $this->json($carBreakdown);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param Car $car
     *
     * @return Response
     *
     * @Route("/cars/{id}/breakdowns", methods={"POST"}, name="car_breakdown_new", requirements={"id"="\d+"})
     */
    public function new(Request $request, SerializerInterface $serializer, Car $car): Response
    {
        $data = $serializer->decode($request->getContent(), self::JSON_FORMAT);

        $form = $this->createForm(CarBreakdownType::class, new CarBreakdown());
        $form->submit($data);
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true)->current()->getMessageParameters(), 400);
        }

        $carBreakdown = $form->getData();
        $extraData = $form->getExtraData();

        if (!empty($extraData['breakdown_id'])) {
            $em = $this->getDoctrine()->getManager();
            $breakdown = $em->find(Breakdown::class, $extraData['breakdown_id']);

            if (!$breakdown) {
                if (!empty($extraData['breakdown_name'])) {
                    $breakdown = new Breakdown();
                    $breakdown->setname($extraData['breakdown_name']);
                } else {
                    return $this->json(
                        'Breakdown not found. Please provide breakdown_name to create it',
                        400
                    );
                }
            }

            $carBreakdown->setCar($car);
            $carBreakdown->setBreakdown($breakdown);

            $em->persist($carBreakdown);
            try {
                $em->flush();
            } catch (\Exception $e) {
                return $this->json($e->getMessage(), 400);
            }

            return $this->json($carBreakdown);
        }

        return $this->json('Provide car and breakdown ids', 400);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param Car $car
     * @param CarBreakdown $carBreakdown
     *
     * @return Response
     *
     * @Route(
     *     "/cars/{carId}/breakdowns/{carBreakdownId}",
     *     methods={"PATCH"},
     *     name="car_breakdown_patch",
     *     requirements={"carId"="\d+", "carBreakdownId"="\d+"}
     * )
     *
     * @ParamConverter("car", options={"mapping": {"carId" : "id"}})
     * @ParamConverter("carBreakdown", options={"mapping": {"carBreakdownId" : "id"}})
     */
    public function update(
        Request $request,
        SerializerInterface $serializer,
        Car $car,
        CarBreakdown $carBreakdown
    ): Response {
        $data = $serializer->decode($request->getContent(), self::JSON_FORMAT);

        $form = $this->createForm(CarBreakdownType::class, $carBreakdown);
        $form->submit($data, false);
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true)->current()->getMessageParameters(), 400);
        }

        $em = $this->getDoctrine()->getManager();
        $extraData = $form->getExtraData();
        if (!empty($extraData['breakdown_id'])) {
            $breakdown = $em->find(Breakdown::class, $extraData['breakdown_id']);

            if (!$breakdown) {
                if (!empty($extraData['breakdown_name'])) {
                    $breakdown = new Breakdown();
                    $breakdown->setname($extraData['breakdown_name']);
                } else {
                    return $this->json(
                        'Breakdown not found. Please provide breakdown_name to create it',
                        400
                    );
                }
            }
        }

        try {
            $em->flush();
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        return $this->json($carBreakdown);
    }

    /**
     * @param Car $car
     * @param CarBreakdown $carBreakdown
     *
     * @return Response
     *
     * @Route(
     *     "/cars/{carId}/breakdowns/{carBreakdownId}",
     *     methods={"DELETE"},
     *     name="car_breakdown_delete",
     *     requirements={"carId"="\d+", "carBreakdownId"="\d+"}
     * )
     *
     * @ParamConverter("car", options={"mapping": {"carId" : "id"}})
     * @ParamConverter("carBreakdown", options={"mapping": {"carBreakdownId" : "id"}})
     */
    public function delete(Car $car, CarBreakdown $carBreakdown): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($carBreakdown);

        try {
            $em->flush();
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        return $this->json(null, 204);
    }
}
