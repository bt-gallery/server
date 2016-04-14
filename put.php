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
use Phalcon\Filter;
use Phalcon\Crypt;
use Phalcon\Mvc\Model\Query;

/* PUT routes */

$app->put(
    '/api/v1/declarant/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        if ($model = Declarant::findFirst($data['id']) && isset($data['id'])) {
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $queue = $app->di->getService("queue")->getDefinition();
            $result = $saver(
                $mapper($model,$data)
            );
        }else {
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }

);

$app->put(
    '/api/v1/participant/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        if ($model = Participant::findFirst($data['id']) && isset($data['id'])) {
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $queue = $app->di->getService("queue")->getDefinition();
            $result = $saver(
                $mapper($model,$data)
            );
        }else {
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->put(
    '/api/v1/contribution/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        if ($model = Contribution::findFirst($data['id']) && isset($data['id'])) {
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $queue = $app->di->getService("queue")->getDefinition();
            $result = $saver(
                $mapper($model,$data)
            );
        }else {
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->put(
    '/api/v1/stairway/kick',
    function () use ($app, $responder, $servant) {
        
    }
);

$app->put(
    '/api/v1/specification/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        if ($model = Specification::findFirst($data['id']) && isset($data['id'])) {
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $queue = $app->di->getService("queue")->getDefinition();
            $result = $saver(
                $mapper($model,$data)
            );
        }else {
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->put(
    '/api/v1/moderation/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        if ($model = ModerationStatus::findFirst($data['id']) && isset($data['id'])) {
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $queue = $app->di->getService("queue")->getDefinition();
            $result = $saver(
                $mapper($model,$data)
            );
        }else {
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->put(
    '/api/v1/rejection/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        if ($model = Rejection::findFirst($data['id']) && isset($data['id'])) {
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $queue = $app->di->getService("queue")->getDefinition();
            $result = $saver(
                $mapper($model,$data)
            );
        }else {
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->put(
    '/api/v1/category/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        if ($model = Category::findFirst($data['id']) && isset($data['id'])) {
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $queue = $app->di->getService("queue")->getDefinition();
            $result = $saver(
                $mapper($model,$data)
            );
        }else {
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);
