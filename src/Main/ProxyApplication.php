<?php

declare(strict_types=1);

namespace Maximaster\BitrixUnstatic\Main;

use Bitrix\Main\Application as BitrixApplication;
use Bitrix\Main\Context;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Data\ConnectionPool;
use Bitrix\Main\Data\ManagedCache;
use Bitrix\Main\Data\TaggedCache;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\Diag\ExceptionHandler;
use Bitrix\Main\Diag\ExceptionHandlerOutput;
use Bitrix\Main\Diag\FileExceptionHandlerLog;
use Bitrix\Main\Dispatcher;
use Bitrix\Main\NotSupportedException;
use Bitrix\Main\Response;
use Bitrix\Main\SystemException;
use CAdminMessage;
use CMain;
use CUserTypeManager;
use Maximaster\BitrixUnstatic\Contract\Main\Application;
use RuntimeException;

/**
 * Проксирует запросы в BitrixApplication, создавая его экзепляр лениво.
 * Позволяет внедрить собственную реализацию, которая будет наследовать от
 * Bitrix\Main\Application.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods) why:dependency
 */
class ProxyApplication implements Application
{
    private ?BitrixApplication $implementation;

    public function __construct(?BitrixApplication $implementation = null)
    {
        $this->implementation = $implementation;
    }

    /**
     * @throws SystemException
     */
    public function getConnection(string $name = ''): Connection
    {
        return $this->app()::getConnection($name);
    }

    /**
     * @throws SystemException
     */
    public function getUserTypeManager(): CUserTypeManager
    {
        return $this->app()::getUserTypeManager();
    }

    /**
     * @throws SystemException
     */
    public function isUtfMode(): bool
    {
        return $this->app()::isUtfMode();
    }

    /**
     * @throws SystemException
     */
    public function getDocumentRoot(): ?string
    {
        return $this->app()::getDocumentRoot();
    }

    /**
     * @throws SystemException
     */
    public function getPersonalRoot(): ?string
    {
        return $this->app()::getPersonalRoot();
    }

    /**
     * @throws SystemException
     */
    public function resetAccelerator(): void
    {
        $this->app()->resetAccelerator();
    }

    /**
     * @throws SystemException
     */
    public function createExceptionHandlerOutput(): ExceptionHandlerOutput
    {
        return $this->app()->createExceptionHandlerOutput();
    }

    /**
     * @throws SystemException
     */
    public function createExceptionHandlerLog(): FileExceptionHandlerLog
    {
        return $this->app()->createExceptionHandlerLog();
    }

    /**
     * @throws SystemException
     */
    public function getExceptionHandler(): ExceptionHandler
    {
        return $this->app()->getExceptionHandler();
    }

    /**
     * @throws SystemException
     */
    public function start(): void
    {
        $this->app()->start();
    }

    /**
     * @throws SystemException
     */
    public function finish(): void
    {
        $this->app()->finish();
    }

    public function end($status = 0, Response $response = null): void
    {
        $this->app()->end($status, $response);
    }

    /**
     * @throws SystemException
     */
    public function run(): void
    {
        $this->app()->run();
    }

    public function initializeBasicKernel(): void
    {
        $this->app()->initializeBasicKernel();
    }

    public function initializeExtendedKernel(array $params): void
    {
        $this->app()->initializeExtendedKernel($params);
    }

    /**
     * @throws NotSupportedException
     * @throws SystemException
     */
    public function getDispatcher(): Dispatcher
    {
        return $this->app()->getDispatcher();
    }

    /**
     * @throws SystemException
     */
    public function terminate(int $status = 0): void
    {
        $this->app()->terminate($status);
    }

    /**
     * @throws SystemException
     */
    public function getConnectionPool(): ConnectionPool
    {
        return $this->app()->getConnectionPool();
    }

    /**
     * @throws SystemException
     */
    public function getContext(): Context
    {
        return $this->app()->getContext();
    }

    /**
     * @throws SystemException
     */
    public function setContext(Context $context): void
    {
        $this->app()->setContext($context);
    }

    /**
     * @throws SystemException
     */
    public function getCache(): Cache
    {
        return $this->app()->getCache();
    }

    /**
     * @throws SystemException
     */
    public function getManagedCache(): ManagedCache
    {
        return $this->app()->getManagedCache();
    }

    /**
     * @throws SystemException
     */
    public function getTaggedCache(): TaggedCache
    {
        return $this->app()->getTaggedCache();
    }

    /**
     * @throws SystemException
     */
    private function app(): BitrixApplication
    {
        // Сначала надо загрузить ядро, иначе будет рекурсивная загрузка.
        class_exists(CMain::class);

        if ($this->implementation === null) {
            $this->implementation = BitrixApplication::getInstance();
        }

        return $this->implementation;
    }

    /**
     * @SuppressWarnings(PHPMD.CamelCaseVariableName) why:dependency
     */
    private function oldApp(): CMain
    {
        global $APPLICATION;

        if (($APPLICATION instanceof CMain) === false) {
            throw new RuntimeException('APPLICATION не была инициализирована.');
        }

        return $APPLICATION;
    }

    public function setTitle(string $title): void
    {
        $this->oldApp()->SetTitle($title);
    }

    public function getCurPageParam(string $addedQuery, array $removedParams = [], ?bool $useIndexPage = null): string
    {
        return $this->oldApp()->GetCurPageParam($addedQuery, $removedParams, $useIndexPage);
    }

    /**
     * {@inheritDoc}
     *
     * @SuppressWarnings(BooleanArgumentFlag) why:dependency
     */
    public function throwException(string $message, $id = false): void
    {
        $this->oldApp()->ThrowException($message, $id);
    }

    public function renderAdminError(string $error): void
    {
        $error = new CAdminMessage($error);
        echo $error->Show();
    }

    public function renderAdminMessage(string $message): void
    {
        $message = new CAdminMessage(['MESSAGE' => $message, 'TYPE' => 'OK']);
        echo $message->Show();
    }
}
