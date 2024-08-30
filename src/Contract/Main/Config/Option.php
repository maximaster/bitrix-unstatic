<?php

declare(strict_types=1);

namespace Maximaster\BitrixUnstatic\Contract\Main\Config;

/**
 * Интерфейс для \Bitrix\Main\Config\Option.
 */
interface Option
{
    /**
     * Возвращает значение опции.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public function get(string $moduleId, string $name, string $default = '', bool $siteId = false): string;

    /**
     * Получает значение опции из базы данных.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public function getRealValue($moduleId, $name, $siteId = false): ?string;

    /**
     * Получает значения опций модуля по умолчанию.
     *
     * @psalm-return array<string,mixed>
     */
    public function getDefaults(string $moduleId): array;

    /**
     * Возвращает все установленные значения модуля.
     *
     * @param string|false $siteId
     *
     * @psalm-return array<string,mixed>
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public function getForModule(string $moduleId, $siteId = false): array;

    /**
     * Устанавливает значение опции.
     */
    public function set(string $moduleId, string $name, string $value = '', string $siteId = ''): void;

    /**
     * Удаляет опцию модуля.
     */
    public function delete(string $moduleId, array $filter = []): void;
}
