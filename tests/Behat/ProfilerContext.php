<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\Profiler\Profiler;

final class ProfilerContext implements Context
{
    private $profiler;

    public function __construct(Profiler $profiler)
    {
        $this->profiler = $profiler;
    }

    /**
     * @BeforeScenario @profiler
     */
    public function enableProfiler(): void
    {
        $this->profiler->enable();
    }
}
