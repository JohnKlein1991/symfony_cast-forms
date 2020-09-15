<?php


namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class AdminUtilityController
 * @package App\Controller
 */
class AdminUtilityController extends AbstractController
{
    /**
     * @Route("admin/utility/users", methods={"GET"}, name="admin_utility_users")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     * @param UserRepository $userRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function getUsersApi(UserRepository $userRepository, Request $request)
    {
        $users = $userRepository->getAllMatching($request->get('query'));
        return $this->json([
            'users' => $users
        ], 200, [], [
            'groups' => ['main']
        ]);
    }
}