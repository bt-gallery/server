<?php

use Phalcon\Di\FactoryDefault\Cli as CliDI,
    Phalcon\Cli\Console as ConsoleApp,
    Phalcon\Mvc\View\Simple as View,
    Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
    Monolog\Logger,
    Monolog\Handler\StreamHandler;

define('VERSION', '1.0.0');

// Используем стандартный для CLI контейнер зависимостей
$di = new CliDI();

// Определяем путь к каталогу приложений
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__)));

require APPLICATION_PATH . '/vendor/autoload.php';
/**
 * Регистрируем автозагрузчик, и скажем ему, чтобы зарегистрировал каталог задач
 */
$loader = new \Phalcon\Loader();
$loader->registerDirs(
    array(
        APPLICATION_PATH . '/tasks',
        APPLICATION_PATH . '/models'
    )
);
$loader->register();

// Загружаем файл конфигурации, если он есть
if (is_readable(APPLICATION_PATH . '/config/config-cli.php')) {
    $config = include APPLICATION_PATH . '/config/config-cli.php';
    $di->set('config', $config);
}

$logger = new Logger('logger');
//$handler = new Monolog\Handler\LogEntriesHandler('62fd4665-e33f-413a-887e-fcea157b583e');
$stream = new StreamHandler($config['logDir'] . "cli.log", Logger::DEBUG);
//$logger->pushHandler($handler);
$logger->pushHandler($stream);

/**
 * Sets the view component
 */
$di['view'] = function () use ($config) {
    $view = new View();
    $view->setViewsDir($config['viewsDir']);
    return $view;
};

/**
 * Database connection is created based in the
 * parameters defined in the configuration file
 */
$di['db'] = function () use ($config) {
    return new DbAdapter(
        array(
        "host" => $config["database"]["host"],
        "username" => $config["database"]["username"],
        "password" => $config["database"]["password"],
        "dbname" => $config["database"]["dbname"]
        )
    );
};

$di->set(
    'logger', function () use ($config) {
        $logger = new Logger('logger');
        //$handler = new Monolog\Handler\LogEntriesHandler('62fd4665-e33f-413a-887e-fcea157b583e');
        $stream = new StreamHandler($config['logDir'] . "cli.log", Logger::DEBUG);
        //$logger->pushHandler($handler);
        $logger->pushHandler($stream);

        return $logger;
    }
);

$di->set(
    'mailLogger', function () use ($config) {
        $logger = new Logger('mailLogger');
        //$handler = new Monolog\Handler\LogEntriesHandler('62fd4665-e33f-413a-887e-fcea157b583e');
        $stream = new StreamHandler($config['logDir'] . "cli.log", Logger::DEBUG);
        //$logger->pushHandler($handler);
        $logger->pushHandler($stream);

        return $logger;
    }
);

$di['saver'] = function (&$model) use ($logger) {
    if (!$model->save()) {
        $result["error"] = array_map(
            function ($message) {
                return $message->getMessage();
            }, $model->getMessages()
        );
        $logger->addCritical("model saving fails", $result);
    } else {
        $result["success"] = $model->toArray();
        $logger->addInfo("model saving ok", $result);
    }

    return $result;
};

// Создаем консольное приложение
$console = new ConsoleApp();
$console->setDI($di);

/**
 * Определяем консольные аргументы
 */
$arguments = array();
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

// определяем глобальные константы для текущей задачи и действия
define('CURRENT_TASK',   (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

try {
    // обрабатываем входящие аргументы
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}
