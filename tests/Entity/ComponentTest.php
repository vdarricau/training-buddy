<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Component;
use Generator;
use LogicException;
use PHPUnit\Framework\TestCase;

class ComponentTest extends TestCase
{
    /**
     * @test
     */
    public function should_throw_exception_if_status_does_not_exist(): void
    {
        $this->expectException(LogicException::class);

        $workout = new Component();
        $workout->setStatus('not existing status');
    }

    /**
     * @test
     * @dataProvider getComponentStatuses
     */
    public function should_set_status_if_status_allowed(string $status): void
    {
        $workout = new Component();
        $workout->setStatus($status);

        self::assertSame($status, $workout->getStatus());
    }

    public function getComponentStatuses(): Generator
    {
        foreach (Component::STATUSES as $status) {
            yield [$status => $status];
        }
    }
}
