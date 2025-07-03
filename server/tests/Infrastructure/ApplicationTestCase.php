<?php

namespace App\Tests\Infrastructure;

use App\Tests\Fixtures\UserFixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationTestCase extends WebTestCase {
  protected static ?bool $initiated = false;
  protected static KernelBrowser $client;

  public function initialize(): KernelBrowser {
    self::$client = parent::createClient();
    $container = self::getContainer();
    $kernel = $container->get('kernel');

    $application = new Application($kernel);
    $application->setAutoExit(false);

    $entityManager = $container->get('doctrine')->getManager();
    $metadat = $entityManager->getMetadataFactory()->getAllMetadata();
    $schemaTool = new SchemaTool($entityManager);
    $schemaTool->dropSchema($metadat);
    $schemaTool->createSchema($metadat);

    return self::$client;
  }

  protected function request(string $method, string $uri, ?array $body = []) {
    self::$client->request(
      method: $method, 
      uri: $uri, 
      parameters: [], 
      files: [], 
      server: [
        'CONTENT_TYPE' => 'application/json'
      ], 
      content: json_encode($body)
    );

    $this->afterRequest();
  }

  protected function afterRequest() {
    $entityManager = self::getContainer()->get(EntityManagerInterface::class);
    $entityManager->clear();
  }

  protected function load(array $fixtures = []) {
    foreach($fixtures as $fixture) {
      $fixture->load(self::getContainer());
    }

    $entityManager = self::getContainer()->get(EntityManagerInterface::class);
    $entityManager->flush();
  }
}