<?php
declare(strict_types = 1);

namespace Tests\Integration;

use Illuminate\Http\Request;
use Tests\Integration\Stub\JsonRequestDataStub;
use Tests\Integration\Stub\AllRequestDataStub;
use Tests\Integration\Stub\HeaderRequestDataStub;

/**
 * Class DtoResolvingTest
 *
 * @package Tests\Integration
 */
class DtoResolvingTest extends TestCase
{
    public function testAllRequestData(): void
    {
        $this->modifyQueryRequest();

        /** @var AllRequestDataStub $dto */
        $dto = $this->app->make(AllRequestDataStub::class);
        $this->assertInstanceOf(AllRequestDataStub::class, $dto);
        $this->assertSame('title1', $dto->getTitle());
        $this->assertSame(2, $dto->getAge());
    }

    /**
     * @expectedException \Maksi\RequestMapperL\Exception\RequestMapperException
     */
    public function testInvalidAllRequestData(): void
    {
        $this->modifyJsonRequest();
        /** @var AllRequestDataStub $dto */
        $this->app->make(AllRequestDataStub::class);
    }

    public function testJsonRequestData(): void
    {
        $this->modifyJsonRequest();
        /** @var AllRequestDataStub $dto */
        $dto = $this->app->make(JsonRequestDataStub::class);

        $this->assertInstanceOf(JsonRequestDataStub::class, $dto);
        $this->assertSame('title1', $dto->getTitle());
        $this->assertSame(2, $dto->getAge());
    }

    /**
     * @expectedException \Maksi\RequestMapperL\Exception\RequestMapperException
     */
    public function testInvalidJsonRequestData(): void
    {
        $this->modifyQueryRequest();
        /** @var AllRequestDataStub $dto */
        $this->app->make(JsonRequestDataStub::class);
    }

    public function testHeaderRequestData(): void
    {
        $this->modifyHeaderRequest();
        /** @var HeaderRequestDataStub $dto */
        $dto = $this->app->make(HeaderRequestDataStub::class);

        $this->assertInstanceOf(HeaderRequestDataStub::class, $dto);
        $this->assertSame(['title1'], $dto->getTitle());
        $this->assertSame([2], $dto->getAge());
    }

    /**
     * @expectedException \Maksi\RequestMapperL\Exception\RequestMapperException
     */
    public function testInvalidHeaderData(): void
    {
        $this->modifyQueryRequest();
        /** @var AllRequestDataStub $dto */
        $this->app->make(HeaderRequestDataStub::class);
    }

    private function modifyQueryRequest(): void
    {
        /** @var Request $request */
        $request = $this->app->make(Request::class);
        $request->query->set('title', 'title1');
        $request->query->set('age', 2);
    }

    private function modifyJsonRequest(): void
    {
        /** @var Request $request */
        $request = $this->app->make(Request::class);
        $request->json()->set('title', 'title1');
        $request->json()->set('age', 2);
    }

    private function modifyHeaderRequest(): void
    {
        /** @var Request $request */
        $request = $this->app->make(Request::class);
        $request->headers->set('title', 'title1');
        $request->headers->set('age', 2);
    }
}