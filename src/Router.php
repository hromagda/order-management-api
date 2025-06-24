<?php

namespace OrderManagementApi;

use OrderManagementApi\Controller\OrderController;
use OrderManagementApi\Repository\OrderRepositoryInterface;
use OrderManagementApi\Http\Request;
use OrderManagementApi\Http\Response;

class Router
{
    private OrderRepositoryInterface $repository;

    public function __construct(OrderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handleRequest(): void
    {
        $request = new Request();
        $response = new Response();

        // Zjisti požadovaný formát
        $accept = $_SERVER['HTTP_ACCEPT'] ?? 'application/json';
        if (str_contains($accept, 'application/xml')) {
            $response->setFormat('xml');
        } else {
            $response->setFormat('json');
        }

        try {
            $method = $request->getMethod();
            $uri = $request->getUri();
            $controller = new OrderController($this->repository);

            if ($method === 'GET' && preg_match('#^/orders/?$#', $uri)) {
                $controller->index($request, $response);
            } elseif ($method === 'GET' && preg_match('#^/orders/(\d+)$#', $uri, $m)) {
                $controller->show($m[1], $request, $response);
            } else {
                $response->setStatusCode(404)
                    ->setBody(['error' => 'Not Found'])
                    ->send();
                return;
            }
        } catch (\Exception $e) {
            $response->setStatusCode(500)
                ->setBody(['error' => 'Internal server error', 'details' => $e->getMessage()])
                ->send();
            return;
        }

        $response->send();
    }
}