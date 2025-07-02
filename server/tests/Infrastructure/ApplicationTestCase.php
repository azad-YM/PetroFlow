<?php

namespace App\Tests\Infrastructure;

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
}