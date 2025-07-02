<?php

namespace App\Infrastructure\ForTests;

use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Tools\DsnParser;
use Testcontainers\Modules\PostgresContainer;

class TestConnectionFactory extends ConnectionFactory {
  static $testDsn;

  public function __construct(array $typesConfig, ?DsnParser $dsnParser = null)
  {
    if (!$this::$testDsn) {
      $psql = (new PostgresContainer())
        ->withPostgresUser('user')
        ->withPostgresPassword('azerty')
        ->withPostgresDatabase('app_test')
        ->start();
      $this::$testDsn = sprintf('postgresql://user:azerty@%s:%d/app?serverVersion=16&charset=utf8', $psql->getHost(), $psql->getFirstMappedPort());
    }
    parent::__construct($typesConfig, $dsnParser);
  }


  public function createConnection(array $params, ?Configuration $config = null, ?EventManager $eventManager = null, array $mappingTypes = [])
  {
    $params['url'] = $this::$testDsn;
    return parent::createConnection($params, $config, $eventManager, $mappingTypes);
  }
}