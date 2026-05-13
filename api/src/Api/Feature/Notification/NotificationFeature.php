<?php

namespace App\Api\Feature\Notification;

use App\Api\Security\Exception\InvalidTokenException;
use App\Api\Security\Jwt\JwtService;
use App\Application\Feature\Notification\GetUnreadNotification\GetUnreadNotificationsCommand;
use App\Application\Feature\Notification\GetUnreadNotification\GetUnreadNotificationsCommandHandler;
use App\Application\Feature\Notification\MarkAsReadNotification\MarkAsReadNotificationCommand;
use App\Application\Feature\Notification\MarkAsReadNotification\MarkAsReadNotificationCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotificationFeature extends AbstractController
{
    #[Route('/api/notifications', name: 'get_notification', methods: ['GET'])]
    public function getUnreadNotifications(
        Request $request,
        JwtService $jwtService,
        GetUnreadNotificationsCommandHandler $handler
    ): JsonResponse {
        $accessToken = $request->cookies->get('access_token')
            ?? throw new InvalidTokenException('Brak access_token');

        $payload = $jwtService->decode($accessToken);
        $username = $payload['username'];

        $notifications = $handler->handle(new GetUnreadNotificationsCommand($username));

        return new JsonResponse($notifications, Response::HTTP_OK);
    }

    #[Route('/api/notifications', name: 'patch_notification', methods: ['PATCH'])]
    public function markAsReadNotifications(
        Request $request,
        JwtService $jwtService,
        MarkAsReadNotificationCommandHandler $handler
    ): JsonResponse {
        $accessToken = $request->cookies->get('access_token')
            ?? throw new InvalidTokenException('Brak access_token');

        $payload = $jwtService->decode($accessToken);
        $username = $payload['username'];

        $handler->handle(new MarkAsReadNotificationCommand($username));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}