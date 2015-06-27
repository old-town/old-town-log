<?php
/**
 * @link https://github.com/old-town/old-town-log
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Log;

use OldTown\Log\Exception\DomainException;
use OldTown\Log\Exception\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use ReflectionClass;

/**
 * Class LogFactory
 *
 * @package OldTown\Log
 */
abstract class LogFactory
{
    /**
     * Имя класса логеа по умолчнаию
     *
     * @var string
     */
    protected static $defaultLoggerClass = '\Psr\Log\NullLogger';

    /**
     * @var LoggerInterface
     */
    protected static $log;

    /**
     * Обязательные методы логера
     *
     * @var array|null
     */
    protected static $requiredLoggerMethods;

    /**
     * Возвращает логер
     *
     * @param null $param
     *
     * @return LoggerInterface
     *
     * @throws \OldTown\Log\Exception\InvalidArgumentException
     * @throws \OldTown\Log\Exception\DomainException
     */
    public static function getLog($param = null)
    {
        if (static::$log instanceof LoggerInterface) {
            return static::$log;
        }
        if (null === $param) {
            $defaultLogger = static::$defaultLoggerClass;
            static::$log = new $defaultLogger();
        } elseif ($param instanceof LoggerInterface) {
            static::$log = $param;
        } elseif (class_exists($param)) {
            static::$log = new $param();
        } else {
            $errMsg = 'Не удалось создасть логер';
            throw new InvalidArgumentException($errMsg);
        }

        if (!is_object(static::$log)) {
            $errMsg = 'Ошибка при создание логера';
            throw new InvalidArgumentException($errMsg);
        }

        static::validLogger(static::$log);

        return static::$log;
    }

    /**
     * Проверка логера на валидность
     *
     * @param $logger
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public static function validLogger($logger)
    {
        if (!is_object($logger)) {
            $errMsg = 'Логер должен быть объектом';
            throw new InvalidArgumentException($errMsg);
        }

        $loggerMethods = static::getRequiredLoggerMethods();
        foreach ($loggerMethods as $method) {
            if (!method_exists($logger, $method)) {
                $errMsg = "У логера должен быть метод {$method}";
                throw new DomainException($errMsg);
            }
        }
    }

    /**
     * Возвращает обязательные методы логера
     *
     * @return array
     */
    protected static function getRequiredLoggerMethods()
    {
        if (self::$requiredLoggerMethods) {
            return self::$requiredLoggerMethods;
        }

        $rLoggerInterface = new ReflectionClass(LoggerInterface::class);
        $loggerMethods = $rLoggerInterface->getMethods();

        self::$requiredLoggerMethods = [];

        foreach ($loggerMethods as $method) {
            $methodName = $method->getName();
            self::$requiredLoggerMethods[$methodName] = $methodName;
        }


        return self::$requiredLoggerMethods;
    }


}
