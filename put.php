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
                $model = new Declarant;
                $data = $app->request->getPut();
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
        $model = new Participant;
        $data = $app->request->getPut();
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
        $model = new Contribution;
        $data = $app->request->getPut();
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
        $model = new Specification;
        $data = $app->request->getPut();
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
        $model = new ModerationStatus;
        $data = $app->request->getPut();
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
        $model = new Rejection;
        $data = $app->request->getPut();
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
        $model = new Category;
        $data = $app->request->getPut();
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