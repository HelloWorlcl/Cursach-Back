<?php

namespace App\Controller\API;

use App\Entity\Breakdown;
use App\Form\BreakdownType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BreakdownsController extends AbstractController implements ControllerInterface
{
    /**
     * @return Response
     *
     * @Route("/breakdowns", methods={"GET"}, name="breakdowns_list")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getRepository(Breakdown::class);

        return $this->json($em->findAll());
    }

    /**
     * @param Breakdown $breakdown
     *
     * @return Response
     *
     * @Route("/breakdowns/{id}", methods={"GET"}, name="breakdown_show", requirements={"id"="\d+"})
     */
    public function show(Breakdown $breakdown): Response
    {
        return $this->json($breakdown);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     *
     * @return Response
     *
     * @Route("/breakdowns", methods={"POST"}, name="breakdown_new")
     */
    public function new(Request $request, SerializerInterface $serializer): Response
    {
        $data = $serializer->decode($request->getContent(), self::JSON_FORMAT);

        $form = $this->createForm(BreakdownType::class, new Breakdown());
        $form->submit($data);
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true)->current()->getMessage(), 400);
        }

        $em = $this->getDoctrine()->getManager();
        $breakdown = $form->getData();
        $em->persist($breakdown);
        try {
            $em->flush();
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        return $this->json($breakdown);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param Breakdown $breakdown
     *
     * @return Response
     *
     * @Route("/breakdowns/{id}", methods={"PUT"}, name="breakdown_put", requirements={"id"="\d+"})
     */
    public function update(
        Request $request,
        SerializerInterface $serializer,
        Breakdown $breakdown
    ): Response  {
        $data = $serializer->decode($request->getContent(), self::JSON_FORMAT);

        $form = $this->createForm(BreakdownType::class, $breakdown);
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

        return $this->json($breakdown);
    }

    /**
     * @param Breakdown $breakdown
     *
     * @return Response
     *
     * @Route("/breakdowns/{id}", methods={"DELETE"}, name="breakdown_delete", requirements={"id"="\d+"})
     */
    public function delete(Breakdown $breakdown): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($breakdown);

        try {
            $em->flush();
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }

        return $this->json(null, 204);
    }
}
