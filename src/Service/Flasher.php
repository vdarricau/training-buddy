<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class Flasher
{
    private const FLASH_TYPE_SUCCESS = 'success';
    private const FLASH_TYPE_ERROR = 'danger';

    public const FLASH_TYPES = [
        self::FLASH_TYPE_SUCCESS,
        self::FLASH_TYPE_ERROR,
    ];

    public function __construct(private FlashBagInterface $flashBag)
    {
    }

    public function addSuccess(string $message): void
    {
        $this->flashBag->add(self::FLASH_TYPE_SUCCESS, $message);
    }

    public function addError(string $message): void
    {
        $this->flashBag->add(self::FLASH_TYPE_ERROR, $message);
    }
}
