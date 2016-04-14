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
                $model= Declarant::findFirst($data['id']);
                if (Declarant::findFirst($data['id']) && isset($data['id'])) {
                   $mapper = $servant("mapper");
                $saver = $servant("saver");
                $queue = $app->di->getService("queue")->getDefinition();
                $result = $saver(
                    $mapper($model,$data)
                );
        
                $responder($result, ["Content-Type"=>"application/json"]);
            } else {
            $result = ["error"=>["message"=>"id not found", 
                                 "legend"=>"Запись с таким идентефикатором не найдена"]];
            $responder($result, ["Content-Type"=>"application/json"]);
        } 
    }

);

$app->put(
    '/api/v1/participant/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        $model= Participant::findFirst($data['id']);
        if (Participant::findFirst($data['id']) && isset($data['id'])) {
        $mapper = $servant("mapper");
        $saver = $servant("saver");
        $queue = $app->di->getService("queue")->getDefinition();
        $result = $saver(
            $mapper($model,$data)
        );

        $responder($result, ["Content-Type"=>"application/json"]);
        }else {
            $result = ["error"=>["message"=>"id not found", 
                                 "legend"=>"Запись с таким идентефикатором не найдена"]];
            $responder($result, ["Content-Type"=>"application/json"]);
        } 
    } 
);

$app->put(
    '/api/v1/contribution/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        $model= Contribution::findFirst($data['id']);
        if (Contribution::findFirst($data['id']) && isset($data['id']))
        $mapper = $servant("mapper");
        $saver = $servant("saver");
        $queue = $app->di->getService("queue")->getDefinition();
        $result = $saver(
            $mapper($model,$data)
        );

        $responder($result, ["Content-Type"=>"application/json"]);
        }else {
            $result = ["error"=>["message"=>"id not found", 
                                 "legend"=>"Запись с таким идентефикатором не найдена"]];
            $responder($result, ["Content-Type"=>"application/json"]);
        } 
    }
);

/* Admin */

$app->put(
    '/api/v1/stairway/kick',
    function () use ($app, $responder, $servant) {
        
    }
);

$app->put(
    '/api/v1/specification/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        $model= Specification::findFirst($data['id']);
        if (Specification::findFirst($data['id']) && isset($data['id']))
        $mapper = $servant("mapper");
        $saver = $servant("saver");
        $queue = $app->di->getService("queue")->getDefinition();
        $result = $saver(
            $mapper($model,$data)
        );

        $responder($result, ["Content-Type"=>"application/json"]);
        }else {
            $result = ["error"=>["message"=>"id not found", 
                                 "legend"=>"Запись с таким идентефикатором не найдена"]];
            $responder($result, ["Content-Type"=>"application/json"]);
        } 
    }
);

$app->put(
    '/api/v1/moderation/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        $model= ModerationStatus::findFirst($data['id']);
        if (ModerationStatus::findFirst($data['id']) && isset($data['id']))
        $mapper = $servant("mapper");
        $saver = $servant("saver");
        $queue = $app->di->getService("queue")->getDefinition();
        $result = $saver(
            $mapper($model,$data)
        );

        $responder($result, ["Content-Type"=>"application/json"]);
        }else {
            $result = ["error"=>["message"=>"id not found", 
                                 "legend"=>"Запись с таким идентефикатором не найдена"]];
            $responder($result, ["Content-Type"=>"application/json"]);
        } 
    }
);

$app->put(
    '/api/v1/rejection/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        $model= Rejection::findFirst($data['id']);
        if (Rejection::findFirst($data['id']) && isset($data['id']))
        $mapper = $servant("mapper");
        $saver = $servant("saver");
        $queue = $app->di->getService("queue")->getDefinition();
        $result = $saver(
            $mapper($model,$data)
        );

        $responder($result, ["Content-Type"=>"application/json"]);
        }else {
            $result = ["error"=>["message"=>"id not found", 
                                 "legend"=>"Запись с таким идентефикатором не найдена"]];
            $responder($result, ["Content-Type"=>"application/json"]);
        } 
    }
);

$app->put(
    '/api/v1/category/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        $model= Category::findFirst($data['id']);
        if (Category::findFirst($data['id']) && isset($data['id']))
        $mapper = $servant("mapper");
        $saver = $servant("saver");
        $queue = $app->di->getService("queue")->getDefinition();
        $result = $saver(
            $mapper($model,$data)
        );

        $responder($result, ["Content-Type"=>"application/json"]);
        }else {
            $result = ["error"=>["message"=>"id not found", 
                                 "legend"=>"Запись с таким идентефикатором не найдена"]];
            $responder($result, ["Content-Type"=>"application/json"]);
        } 
    }
);
