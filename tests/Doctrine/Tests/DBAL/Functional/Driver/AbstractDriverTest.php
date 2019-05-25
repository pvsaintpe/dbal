<?php

declare(strict_types=1);

namespace Doctrine\Tests\DBAL\Functional\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Doctrine\Tests\DbalFunctionalTestCase;

abstract class AbstractDriverTest extends DbalFunctionalTestCase
{
    /**
     * The driver instance under test.
     *
     * @var Driver
     */
    protected $driver;

    protected function setUp() : void
    {
        parent::setUp();

        $this->driver = $this->createDriver();
    }

    /**
     * @group DBAL-1215
     */
    public function testConnectsWithoutDatabaseNameParameter()
    {
        $params = $this->connection->getParams();
        unset($params['dbname']);

        $user     = $params['user'] ?? '';
        $password = $params['password'] ?? '';

        $connection = $this->driver->connect($params, $user, $password);

        self::assertInstanceOf(DriverConnection::class, $connection);
    }

    /**
     * @group DBAL-1215
     */
    public function testReturnsDatabaseNameWithoutDatabaseNameParameter()
    {
        $params = $this->connection->getParams();
        unset($params['dbname']);

        $connection = new Connection(
            $params,
            $this->connection->getDriver(),
            $this->connection->getConfiguration(),
            $this->connection->getEventManager()
        );

        self::assertSame(
            static::getDatabaseNameForConnectionWithoutDatabaseNameParameter(),
            $this->driver->getDatabase($connection)
        );
    }

    /**
     * @return Driver
     */
    abstract protected function createDriver();

    protected static function getDatabaseNameForConnectionWithoutDatabaseNameParameter() : ?string
    {
        return null;
    }
}
