<?php
/**
 * Registration REST microservice
 *
 * @category REST-microservice
 * @package  ContestServer
 * @author   barantaran <yourchev@gmail.com>
 * @license  https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link     https://github.com/barantaran/contest-server
*/

/* Utilities */

$responder = function ($content, $headers = [], $status = ["code"=>200,"message"=>"Ok"]) use ($app) {
    foreach ($headers as $key => $value) {
        $app->response->setHeader($key, $value);
    }
    $app->response->setStatusCode($status["code"], $status["message"]);
    $app->response->setContentType('application/json', 'UTF-8');
    $app->response->setJsonContent($content);
    $app->response->send();

    return true;
};

$servant = function ($serviceName) use ($app) {
    return $app->di->getService($serviceName)->getDefinition();
};

/**
 * Not found handler
 */

$app->notFound(
    function () use ($app, $responder, $logger) {
        $logger->addWarning("404 sent", $app->request->getHeaders());
        if($app->request->isAjax()){
            $responder(["error"=>"404"], [], ["code"=>404, "message"=>"Not found"]);
        } else {
            $app->response->setStatusCode(404, "Not Found")->sendHeaders();
            echo $app['view']->render('404');
        }
    }
);

/* GET POST PUT routes */

require_once 'get.php';
require_once 'post.php';
require_once 'put.php';