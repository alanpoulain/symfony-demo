<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver;

trait TransactionalDatabaseTrait
{
    /**
     * @BeforeSuite
     */
    public static function beforeSuite(): void
    {
        StaticDriver::setKeepStaticConnections(true);
    }

    /**
     * @BeforeStep
     */
    public function beforeScenario(): void
    {
        StaticDriver::beginTransaction();
    }

    /**
     * @AfterScenario
     */
    public function afterScenario(): void
    {
        StaticDriver::rollBack();
    }

    /**
     * @AfterSuite
     */
    public static function afterSuite(): void
    {
        StaticDriver::setKeepStaticConnections(false);
    }
}
