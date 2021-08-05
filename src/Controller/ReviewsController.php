<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Playground;
use App\Entity\User;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ReviewsController extends AbstractController
{
    #[Route('/add_review', name: 'add_review', methods: ['POST'])]
    public function addreview(Request $request, FileUploader $fileUploader): Response
    {
        $reviewData = $request->request;

        $entityManager = $this->getDoctrine()->getManager();
        $playground = $entityManager->getRepository(Playground::class)->find($reviewData->get('playground_id'));

        $review = new Review();

        if ($request->files->get('photo') != null) {
            $photo = $fileUploader->upload($request->files->get('photo'));
            $playground->setImage($photo);
        }

        $review->setText($reviewData->get('text'));
        $review->setRating($reviewData->get('rating'));

        $user = $this->getUser();

        $review->setUser($user);
        $review->setPlayground($playground);

        $entityManager->persist($review);
        $entityManager->refresh($playground);
        $entityManager->flush();
        return $this->json([
            'message' => 'success'
        ]);
    }


    #[Route('/reviewlist', name: 'get_review_list', methods: ['POST'])]
    public function getReviewList(Request $request): Response
    {
        $data = $request->request;
        $index = $data->get('index');

        $entityManager = $this->getDoctrine()->getManager();
        $reviewRepository = $entityManager->getRepository(Review::class);
        $reviewslist = $reviewRepository->getReviewByIdPlay($index);
        return $this->json([
            'reviews' => $reviewslist,
        ]);
    }
}