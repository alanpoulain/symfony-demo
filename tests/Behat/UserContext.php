<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\DataFixtures\AppFixtures;
use Behat\Behat\Context\Context;
use Symfony\Component\BrowserKit\AbstractBrowser;

final class UserContext implements Context
{
    private $browser;
    private $appFixtures;

    public function __construct(AbstractBrowser $browser, AppFixtures $appFixtures)
    {
        $this->browser = $browser;
        $this->appFixtures = $appFixtures;
    }

    /**
     * @Given I am authenticated as :user
     */
    public function iAmAuthenticatedAsUser(string $user): void
    {
        $userData = array_reduce($this->appFixtures->getUserData(), static function(?array $carry, array $userData) use ($user) {
            return $user === $userData[3] ? $userData : null;
        });
        if (null === $userData) {
            throw new \InvalidArgumentException(sprintf('User "%s" not found', $user));
        }

        $this->browser->setServerParameters([
            'PHP_AUTH_USER' => $userData[1],
            'PHP_AUTH_PW'   => $userData[2],
        ]);
    }
}
