<?php

namespace OrderManagementApi\Tests\Controller;

use OrderManagementApi\Controller\OrderController;
use OrderManagementApi\Exception\DatabaseException;
use OrderManagementApi\Http\Request;
use OrderManagementApi\Http\Response;
use OrderManagementApi\Model\Order;
use OrderManagementApi\Repository\OrderRepositoryInterface;
use PHPUnit\Framework\TestCase;

class OrderControllerTest extends TestCase
{
    public function testIndexReturnsOrders()
    {
        // Mock objednávky s metodou toArray()
        $orderMock = $this->createMock(Order::class);
        $orderMock->method('toArray')->willReturn(['id' => 1, 'description' => 'Test order']);

        // Mock repozitáře - vrací pole objednávek
        $repoMock = $this->createMock(OrderRepositoryInterface::class);
        $repoMock->method('findAll')->willReturn([$orderMock]);

        // Mock request (není v controlleru použit)
        $requestMock = $this->createMock(Request::class);

        // Mock response s metodami pro setBody a setStatusCode, které vrací $this (pro chaining)
        $responseMock = $this->getMockBuilder(Response::class)
            ->onlyMethods(['setBody', 'setStatusCode'])
            ->getMock();

        // Očekáváme, že setBody bude zavoláno s polem objednávek (pole polí)
        $responseMock->expects($this->once())
            ->method('setBody')
            ->with([['id' => 1, 'description' => 'Test order']])
            ->willReturnSelf();

        // setStatusCode by nemělo být voláno (protože nedošlo k chybě)
        $responseMock->expects($this->never())
            ->method('setStatusCode');

        $controller = new OrderController($repoMock);
        $controller->index($requestMock, $responseMock);
    }

    public function testIndexHandlesDatabaseException()
    {
        $repoMock = $this->createMock(OrderRepositoryInterface::class);
        $repoMock->method('findAll')->willThrowException(new DatabaseException('DB error'));

        $requestMock = $this->createMock(Request::class);
        $responseMock = $this->getMockBuilder(Response::class)
            ->onlyMethods(['setBody', 'setStatusCode'])
            ->getMock();

        $responseMock->expects($this->once())
            ->method('setStatusCode')
            ->with(500)
            ->willReturnSelf();

        $responseMock->expects($this->once())
            ->method('setBody')
            ->with(['error' => 'DB error'])
            ->willReturnSelf();

        $controller = new OrderController($repoMock);
        $controller->index($requestMock, $responseMock);
    }

    public function testShowReturnsOrder()
    {
        $orderMock = $this->createMock(Order::class);
        $orderMock->method('toArray')->willReturn(['id' => 1, 'description' => 'Test order']);

        $repoMock = $this->createMock(OrderRepositoryInterface::class);
        $repoMock->method('findByIdWithItems')->with(1)->willReturn($orderMock);

        $requestMock = $this->createMock(Request::class);
        $responseMock = $this->getMockBuilder(Response::class)
            ->onlyMethods(['setBody', 'setStatusCode'])
            ->getMock();

        $responseMock->expects($this->once())
            ->method('setBody')
            ->with(['id' => 1, 'description' => 'Test order'])
            ->willReturnSelf();

        $responseMock->expects($this->never())
            ->method('setStatusCode');

        $controller = new OrderController($repoMock);
        $controller->show(1, $requestMock, $responseMock);
    }

    public function testShowReturns404WhenNotFound()
    {
        $repoMock = $this->createMock(OrderRepositoryInterface::class);
        $repoMock->method('findByIdWithItems')->with(999)->willReturn(null);

        $requestMock = $this->createMock(Request::class);
        $responseMock = $this->getMockBuilder(Response::class)
            ->onlyMethods(['setBody', 'setStatusCode'])
            ->getMock();

        $responseMock->expects($this->once())
            ->method('setStatusCode')
            ->with(404)
            ->willReturnSelf();

        $responseMock->expects($this->once())
            ->method('setBody')
            ->with(['error' => 'Order not found'])
            ->willReturnSelf();

        $controller = new OrderController($repoMock);
        $controller->show(999, $requestMock, $responseMock);
    }

    public function testShowHandlesDatabaseException()
    {
        $repoMock = $this->createMock(OrderRepositoryInterface::class);
        $repoMock->method('findByIdWithItems')->willThrowException(new DatabaseException('DB error'));

        $requestMock = $this->createMock(Request::class);
        $responseMock = $this->getMockBuilder(Response::class)
            ->onlyMethods(['setBody', 'setStatusCode'])
            ->getMock();

        $responseMock->expects($this->once())
            ->method('setStatusCode')
            ->with(500)
            ->willReturnSelf();

        $responseMock->expects($this->once())
            ->method('setBody')
            ->with(['error' => 'DB error'])
            ->willReturnSelf();

        $controller = new OrderController($repoMock);
        $controller->show(1, $requestMock, $responseMock);
    }
}