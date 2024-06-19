<?php
namespace App\Controller;

use App\Entity\RefreshToken;
use App\Entity\User;
use App\Helpers\TwoFactorHelper;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenManager;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use http\Cookie;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/api/auth/login', name: 'user_login')]
    public function login(Request $request, EntityManagerInterface $entityManager, TwoFactorHelper $twoFactorHelper): Response {

        $body = $request->getContent();
        $data = json_decode($body);
        $resp = new Response();
        if(!$data->login || !$data->password){
            $resp->setContent(json_encode(['message' => 'Bad request']));
            $resp->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $resp->send();
        }
        $userRepo = $entityManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['username' => $data->login]);
        if(!$user){
            $resp->setContent(json_encode(['message' => 'User not found']));
            $resp->setStatusCode(Response::HTTP_NOT_FOUND);
            return $resp->send();
        }

        $twoFactorHelper->sendCode($user);
        $entityManager->persist($user);
        $entityManager->flush();

        $resp->setContent(json_encode(['message' => 'Code sent to user']));
        $resp->setStatusCode(Response::HTTP_OK);
        return $resp->send();
    }

    #[Route('/api/auth/login/code', name: 'user_code')]
    public function code(
        Request $request,
        EntityManagerInterface $entityManager,
        JWTTokenManagerInterface $JWTManager,
        RefreshTokenGeneratorInterface $refreshTokenGenerator
    ): Response {
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
            $refresh = new RefreshToken();
            $refresh->setUsername($user->getUsername());
            $refresh->setRefreshToken($refreshTokenGenerator->createForUserWithTtl($user, 3600));
            $refresh->setValid(new \DateTime('+3 days'));
            $entityManager->persist($refresh);
            $entityManager->flush();

            $resp->headers->setCookie(\Symfony\Component\HttpFoundation\Cookie::create('refresh_token', $refresh ));
            $resp->setStatusCode(Response::HTTP_OK);
            return $resp->send();
        }

        $resp = new Response();
        $resp->setContent(json_encode(['message' => 'Incorrect code']));
        $resp->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $resp->send();
    }



    #[Route('/api/auth/refresh', name: 'token_refresh')]
    public function refresh(
        Request $request,
        EntityManagerInterface $entityManager,
        JWTTokenManagerInterface $tokenManager,
    ): Response
    {
        $cookie = $request->cookies->get('refresh_token');

        $tokenRepo = $entityManager->getRepository(RefreshToken::class);
        $token = $tokenRepo->findOneBy(['refreshToken' => $cookie]);
        $resp = new Response();
        if(!$token){
            $resp->setStatusCode(Response::HTTP_FORBIDDEN);
            return $resp->send();
        }
        $userRepo = $entityManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['username' => $token->getUsername()]);
        $token = $tokenManager->create($user);
        $resp->setContent(json_encode([
            'access_token' => $token
        ]));

        return $resp->send();
    }
}
