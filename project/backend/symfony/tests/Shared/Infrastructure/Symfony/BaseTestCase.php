<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\Symfony;

//use Api\Tests\Utils\SmsSenderMock;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

//use \PHPUnit\Framework\TestCase;
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SymfonyWebTestCase;
abstract class BaseTestCase extends WebTestCase
{
//    use ApiResponseAssertionsTrait;
//    use GivenWhenThenTrait;
//    use DbModificationsTrait;

    private KernelBrowser $client;
    private array|null $allTraitsCache = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bootKernelBrowserClient();

        $this->tryBootConsoleApplication();

        $defaultCredentials = static::defaultCredentials();
        if (null !== $defaultCredentials) {
            $this->loggedAs(...$defaultCredentials);
        }

        $this->setupTraits();
    }

    protected function tearDown(): void
    {
//        $traits = $this->allClassTraitsRecursively();
//        if (inArray(CleanCachePoolsTrait::class, $traits)) {
//            /* @phpstan-ignore-next-line */
//            $this->cleanCachePools();
//        }
        restore_exception_handler();
        parent::tearDown();
    }

    private function tryBootConsoleApplication(): void
    {
//        $traits = $this->allClassTraitsRecursively();
//        if (inArray(ConsoleCommandTrait::class, $traits)) {
//            /* @phpstan-ignore-next-line */
//            $this->bootConsoleApplication($this->client->getKernel());
//        }
    }

    private function setupTraits(): void
    {
//        $traits = $this->allClassTraitsRecursively();
//        if (inArray(DbModificationsTrait::class, $traits)) {
//            $this->enableDryRunQueryMode();
//        }
//
//        if (inArray(SmsAssertionTrait::class, $traits)) {
//            SmsSenderMock::instance()->usingNewSpy();
//        }
//
//        if (inArray(CleanCachePoolsTrait::class, $traits)) {
//            /* @phpstan-ignore-next-line */
//            $this->cleanCachePools();
//        }
    }

    protected function bootKernelBrowserClient(): void
    {
        $client = $this->createClient();
        $client->catchExceptions(false);
        $client->followRedirects(true);

        $this->client = $client;
    }

    protected function client(): KernelBrowser
    {
        return $this->client;
    }

    protected function makeJsonRequest(string $method, string $uri, array $requestBody = null, array $headers = null): void
    {
        if (null !== $requestBody) {
            $content = json_encode($requestBody);
            assert(false !== $content);
        } else {
            $content = null;
        }

        $headersFormed = ['CONTENT_TYPE' => 'application/json'];

        if (is_array($headers)) {
            $headersFormed = array_merge($headersFormed, $headers);
        }

        $this->client()->request(
            method: $method,
            uri: $uri,
            server: $headersFormed,
            content: $content
        );
    }

    protected function getJsonResponseContents(): array
    {
        $response         = $this->client()->getResponse();
        $responseContents = $response->getContent();
        assert(false !== $responseContents);

        return json_decode($responseContents, true);
    }

    protected function getJsonResponseData(): ?array
    {
        $jsonResponse = $this->getJsonResponseContents();
        assert(array_key_exists('data', $jsonResponse));

        return $this->getJsonResponseContents()['data'];
    }

    private function allClassTraitsRecursively(): array
    {
        if (null === $this->allTraitsCache) {
            $traits = [];
            $class  = $this::class;

            do {
                $traits = array_merge((array) class_uses($class), $traits);
            } while ($class = get_parent_class($class));

            $this->allTraitsCache = $traits;
        }

        return $this->allTraitsCache;
    }

    protected function loggedAs(string $user, string $password): void
    {
        $this->client->setServerParameters([
            'PHP_AUTH_USER' => $user,
            'PHP_AUTH_PW'   => $password,
        ]);
    }

    protected static function defaultCredentials(): ?array
    {
        return null;
    }

    protected function cachePoolsToClean(): array
    {
        return [];
    }

    protected static function assertNoExceptionIsThrown(): void
    {
        /* @phpstan-ignore-next-line */
        self::assertTrue(true);
    }
}
