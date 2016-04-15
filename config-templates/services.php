<?php
use Phalcon\Mvc\View\Simple as View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$di = new FactoryDefault();
$logger = new Logger('logger');
$handler = new StreamHandler(
    $config->application->logDir . "app.log", Logger::DEBUG
);
$logger->pushHandler($handler);

/**
 * Sets the view component
 */
$di['view'] = function () use ($config) {
    $view = new View();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
};

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di['url'] = function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
};

/**
 * Database connection is created based in the
 * parameters defined in the configuration file
 */
$di['db'] = function () use ($config) {
    return new DbAdapter(
        array(
        "host" => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname" => $config->database->dbname,
        "charset" => $config->database->charset
        )
    );
};

$di['mapper'] = function ($object, $array) {
    foreach ($array as $key => $value) {
        $object->$key = $value;
    }
    return $object;
};

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

$di['toDoList'] = function ($data, $job, $status) use ($logger) {
    $model = new ToDoList;
    $model->data = serialize($data);
    $model->job = $job;
    $model->status = $status;

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
