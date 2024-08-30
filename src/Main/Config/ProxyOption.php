<?php

declare(strict_types=1);

namespace Maximaster\BitrixUnstatic\Main\Config;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option as BitrixOption;
use Maximaster\BitrixUnstatic\Contract\Main\Config\Option;

/**
 * Вызывает @BitrixOption.
 */
class ProxyOption implements Option
{
    /**
     * {@inheritDoc}.
     *
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public function get(string $moduleId, string $name, string $default = '', bool $siteId = false): string
    {
        return BitrixOption::get($moduleId, $name, $default, $siteId);
    }

    /**
     * {@inheritDoc}.
     *
     * @throws ArgumentNullException
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public function getRealValue($moduleId, $name, $siteId = false): ?string
    {
        return BitrixOption::getRealValue($moduleId, $name, $siteId);
    }

    /**
     * {@inheritDoc}.
     *
     * @throws ArgumentOutOfRangeException
     */
    public function getDefaults(string $moduleId): array
    {
        return BitrixOption::getDefaults($moduleId);
    }

    /**
     * {@inheritDoc}.
     *
     * @throws ArgumentNullException
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public function getForModule(string $moduleId, $siteId = false): array
    {
        return BitrixOption::getForModule($moduleId, $siteId);
    }

    /**
     * {@inheritDoc}.
     *
     * @throws ArgumentOutOfRangeException
     */
    public function set(string $moduleId, string $name, string $value = '', string $siteId = ''): void
    {
        BitrixOption::set($moduleId, $name, $value, $siteId);
    }

    /**
     * {@inheritDoc}.
     *
     * @throws ArgumentNullException
     */
    public function delete(string $moduleId, array $filter = []): void
    {
        BitrixOption::delete($moduleId, $filter);
    }
}
