<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\EventSubscriber\AuhtorAddSubscriber;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Entity\Comment;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use App\Entity\BlogPost;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\MockObject\MockObject;

class AuthorAddSubscribedTest extends TestCase{

    public function testConfiguration(){
        $result = AuhtorAddSubscriber::getSubscribedEvents();
        $this->assertArrayHasKey(KernelEvents::VIEW, $result);
        $this->assertEquals(
            ['authorAdd', EventPriorities::PRE_WRITE],
            $result[KernelEvents::VIEW]  
        );
    }

    /**
     * @@dataProvider providerSetAuthorCall
     */
    public function testAuthorAddCaller(string $className, bool $shouldCallSetAuthor, string $method){

        $entityMock = $this->getEntityMock($className, $shouldCallSetAuthor);
        $tokenStorageMock = $this->getTokenStorageMock();
        $eventMock = $this->getEventMock($method, $entityMock);
        

        (new AuhtorAddSubscriber($tokenStorageMock))->authorAdd($eventMock);
    }

    public function getTokenStorageMock(){
        $tokenMock = $this->getMockBuilder(TokenInterface::class)
            ->getMockForAbstractClass();
        $tokenMock->expects($this->once())
            ->method('getUser')
            ->willReturn(new User());


        $tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)
            ->getMockForAbstractClass();
        $tokenStorageMock->expects($this->once())
            ->method('getToken')
            ->willReturn($tokenMock);
        return $tokenStorageMock;
    }

    public function getEventMock($method, $entity){
        $requestMock = $this->getMockBuilder(Request::class)
            ->getMock();
        $requestMock->expects($this->once())
            ->method('getMethod')
            ->willReturn($method);

        $eventMock = $this->getMockBuilder(GetResponseForControllerResultEvent::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eventMock->expects($this->once())
            ->method('getControllerResult')
            ->willReturn($entity);
        $eventMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($requestMock);
        return $eventMock;
    }

    /**
     * @return MockObject
     */
    private function getEntityMock(string $className, bool $shouldCallSetAuthor): MockObject
    {
        $entityMock = $this->getMockBuilder($className)
            ->setMethods(['setAuthor'])
            ->getMock();
        $entityMock->expects($shouldCallSetAuthor ? $this->once() : $this->never())
            ->method('setAuthor');
        return $entityMock;
    }

    public function providerSetAuthorCall(): array
    {
        return [
            [BlogPost::class, true, 'POST'],
            [BlogPost::class, false, 'GET'],
            ['NonExisting', false, 'POST'],
            [Comment::class, true, 'POST']
        ];
    }
}