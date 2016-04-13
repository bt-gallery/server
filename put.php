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
    function () use ($app, $responder, $servant, $logger) {

    }
);

$app->put(
    '/api/v1/participant/update',
    function () use ($app, $responder, $servant, $logger) {
        
    }
);

$app->put(
    '/api/v1/contribution/update',
    function () use ($app, $responder, $servant, $logger) {
        
    }
);

/* Admin */

$app->put(
    '/api/v1/stairway/kick',
    function () use ($app, $responder, $servant, $logger) {
        
    }
);

$app->put(
    '/api/v1/specification/update',
    function () use ($app, $responder, $servant, $logger) {
        
    }
);

$app->put(
    '/api/v1/moderation/update',
    function () use ($app, $responder, $servant, $logger) {
        
    }
);

$app->put(
    '/api/v1/rejection/update',
    function () use ($app, $responder, $servant, $logger) {
        
    }
);

$app->put(
    '/api/v1/category/update',
    function () use ($app, $responder, $servant, $logger) {
        
    }
);