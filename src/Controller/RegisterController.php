<?php
namespace App\Controller;

use App\Entity\User;
use App\Helpers\TwoFactorHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/api/auth/register', name: 'user_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, TwoFactorHelper $twoFactorHelper): Response {

        $body = $request->getContent();
        $data = json_decode($body);
        if(!$data->login || !$data->email){
            $resp = new Response();
            $resp->setContent(json_encode(['message' => 'Bad request']));
            $resp->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $resp->send();
        }
        $user = new User();
        $user->setUsername($data->login);
        $twoFactorHelper->sendCode($user);
        $entityManager->persist($user);
        $entityManager->flush();

        $resp = new Response();
        $resp->setContent(json_encode(['message' => 'code sent to user']));
        $resp->setStatusCode(Response::HTTP_OK);
        return $resp->send();
    }

    #[Route('/api/auth/register/code', name: 'user_register_code')]
    public function code(Request $request, EntityManagerInterface $entityManager, JWTTokenManagerInterface $JWTManager): Response {
        $body = $request->getContent();
        $data = json_decode($body);

        $userRepo = $entityManager->getRepository(User::class);
        $user = $userRepo->findByCode($data->code);
        if($user) {
            $resp = new Response();
            $token = $JWTManager->create($user);
            $resp->setContent(json_encode([
                'access_token' => $token
            ]));
            $resp->setStatusCode(Response::HTTP_OK);
            return $resp->send();
        }

        $resp = new Response();
        $resp->setContent(json_encode(['message' => 'Incorrect code']));
        $resp->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $resp->send();

    }

    #[Route('/api/auth/register/complete', name: 'user_resister_complete')]
    public function complete(
        Request $request,
        EntityManagerInterface $entityManager,
        JWTTokenManagerInterface $JWTTokenManager,
        UserPasswordHasherInterface $passwordHasher
    ){

        $token = $request->headers->get('X-Token');
        $decoded = $JWTTokenManager->parse($token);
        $body = $request->getContent();
        $data = json_decode($body);
        $resp = new Response();
        if(!$data->password || !$data->confirm || $data->password !== $data->confirm){
            $resp->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $resp->send();
        }
        $userRepo = $entityManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['username' => $decoded['username']]);
        $user->setPassword($passwordHasher->hashPassword($user, $data->password));
        $entityManager->persist($user);
        $entityManager->flush();

        $resp->setStatusCode(Response::HTTP_OK);
        return $resp->send();

    }
}
