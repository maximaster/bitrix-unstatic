<?php

declare(strict_types=1);

namespace Maximaster\BitrixUnstatic\Contract\Main;

use Bitrix\Main\Context;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Data\ConnectionPool;
use Bitrix\Main\Data\ManagedCache;
use Bitrix\Main\Data\TaggedCache;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\Diag\ExceptionHandler;
use Bitrix\Main\Diag\ExceptionHandlerLog;
use Bitrix\Main\Diag\ExceptionHandlerOutput;
use Bitrix\Main\Dispatcher;
use Bitrix\Main\Response;
use Bitrix\Main\SystemException;
use CUserTypeManager;

/**
 * Интефрейс к Bitrix\Main\Application.
 */
interface Application
{
    /**
     * Static method returns database connection for the specified name.
     * If name is empty - default connection is returned.
     *
     * @param string $name Name of database connection. If empty - default connection.
     */
    public function getConnection(string $name = ''): Connection;

    /**
     * Returns UF manager.
     */
    public function getUserTypeManager(): CUserTypeManager;

    /**
     * Returns true id server is in utf-8 mode. False - otherwise.
     */
    public function isUtfMode(): bool;

    /**
     * Returns server document root.
     */
    public function getDocumentRoot(): ?string;

    /**
     * Returns personal root directory (relative to document root).
     */
    public function getPersonalRoot(): ?string;

    /**
     * Resets accelerator if any.
     */
    public function resetAccelerator(): void;

    public function createExceptionHandlerOutput(): ExceptionHandlerOutput;

    public function createExceptionHandlerLog(): ExceptionHandlerLog;

    public function getExceptionHandler(): ExceptionHandler;

    public function start(): void;

    public function finish(): void;

    /**
     * Ends work of application.
     * Sends response and then terminates execution.
     * If there is no $response the method will use Context::$response.
     *
     * @throws SystemException
     */
    public function end($status = 0, Response $response = null): void;

    public function run(): void;

    /**
     * Does minimally possible kernel initialization.
     *
     * @throws SystemException
     */
    public function initializeBasicKernel(): void;

    /**
     * Does full kernel initialization. Should be called somewhere after initializeBasicKernel().
     *
     * @param array $params Parameters of the current request (depends on application type)
     *
     * @throws SystemException
     */
    public function initializeExtendedKernel(array $params): void;

    public function getDispatcher(): Dispatcher;

    /**
     * Terminates application by invoking exit().
     * It's the right way to finish application @see \CMain::finalActions().
     */
    public function terminate(int $status = 0): void;

    /**
     * Returns database connections pool object.
     */
    public function getConnectionPool(): ConnectionPool;

    /**
     * Returns context of the current request.
     */
    public function getContext(): Context;

    /**
     * Modifies context of the current request.
     */
    public function setContext(Context $context): void;

    /**
     * Returns new instance of the Cache object.
     */
    public function getCache(): Cache;

    /**
     * Returns manager of the managed cache.
     */
    public function getManagedCache(): ManagedCache;

    /**
     * Returns manager of the managed cache.
     */
    public function getTaggedCache(): TaggedCache;

    /**
     * Sets application title.
     */
    public function setTitle(string $title): void;

    /**
     * Возвращает ссылку на текущую страницу с модифицированными параметрами.
     *
     * @psalm-param list<non-empty-string> $removedParams
     */
    public function getCurPageParam(string $addedQuery, array $removedParams = [], ?bool $useIndexPage = null): string;

    /**
     * Выбросить исключение уровня приложнеия. Это выведет блок с ошибкой.
     *
     * @psalm-param false|string $id
     *
     * @SuppressWarnings(BooleanArgumentFlag) why:dependency
     */
    public function throwException(string $message, $id = false): void;

    /**
     * Отобразить сообщение в административной части.
     */
    public function renderAdminError(string $error): void;

    /**
     * Отобразить сообщение в административной части.
     */
    public function renderAdminMessage(string $message): void;
}
