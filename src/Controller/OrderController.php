<?php

namespace OrderManagementApi\Controller;

use OrderManagementApi\Exception\DatabaseException;
use OrderManagementApi\Http\Request;
use OrderManagementApi\Http\Response;
use OrderManagementApi\Repository\OrderRepositoryInterface;

/**
* Controller pro správu objednávek.
 *
 * Poskytuje metody pro získání seznamu všech objednávek a detailu jedné objednávky.
 */
class OrderController
{
    private OrderRepositoryInterface $orderRepository;

    /**
     * Konstruktor controlleru.
     *
     * @param OrderRepositoryInterface $orderRepository Repozitář pro práci s objednávkami.
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Získá seznam všech objednávek a nastaví je do těla odpovědi.
     *
     * @param Request $req HTTP požadavek
     * @param Response $res HTTP odpověď
     *
     * @return void
     */
    public function index(Request $req, Response $res): void
    {
        try {
            $orders = array_map(fn($o) => $o->toArray(), $this->orderRepository->findAll());
            $res->setBody($orders);
        } catch (DatabaseException $e) {
            $res->setStatusCode(500)
                ->setBody(['error' => $e->getMessage()]);
        }
    }

    /**
     * Získá detail objednávky podle ID a nastaví do těla odpovědi.
     * Pokud objednávka neexistuje, vrací HTTP status 404.
     *
     * @param int $id Identifikátor objednávky
     * @param Request $req HTTP požadavek
     * @param Response $res HTTP odpověď
     *
     * @return void
     */
    public function show(int $id, Request $req, Response $res): void
    {
        try {
            $order = $this->orderRepository->findByIdWithItems($id);
            if ($order) {
                $res->setBody($order->toArray());
            } else {
                $res->setStatusCode(404)
                    ->setBody(['error' => 'Order not found']);
            }
        } catch (DatabaseException $e) {
            $res->setStatusCode(500)
                ->setBody(['error' => $e->getMessage()]);
        }
    }
}