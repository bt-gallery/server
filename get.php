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

/* GET routes */

$app->get(
    '/', function () use ($app) {
        $app->response->redirect("hiddenСave")->sendHeaders();
    }
);

$app->get(
    '/hiddenСave', function () use ($app) {
        echo "<p>It's dangerous to go alone! Take this.</p><p>finest_sword.jpg</p>";
    }
);
 $app->get(
    '/api/v1/declarant/get/{id:[0-9]+}',
    function ($id) use ($app, $responder) {
        if($model = Declarant::findFirst($id)){
            $result = $model->toArray();
        }else{
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/declarant/getList/{limit:[0-9]+}/{offset:[0-9]+}',
    function ($limit, $offset) use ($app, $responder) {
        $result = Declarant::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/declarant/search',
    function () use ($app, $responder) {

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/participant/get/{id:[0-9]+}',
    function ($id) use ($app, $responder) {
        if($model = Participant::findFirst($id)){
            $result = $model->toArray();
        }else{
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/participant/getList/{limit:[0-9]+}/{offset:[0-9]+}', 
    function ($limit, $offset) use ($app, $responder) {
        $result = Participant::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/participant/search',
    function () use ($app, $responder) {

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/contribution/get/{id:[0-9]+}',
    function ($id) use ($app, $responder) {
        if($model = Contribution::findFirst($id)){
            $result = $model->toArray();
        }else{
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);
/*!!!!!*/
$app->get(
    '/api/v1/contribution/get/{id:[0-9]+}/votes',
    function ($id) use ($app, $responder) {
        if($model = Contribution::findFirst($id)){
            $result = $model->getVotes();
        }else{
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);
/*!!!!*/
$app->get(
    '/api/v1/contribution/getList/{limit:[0-9]+}/{offset:[0-9]+}',
    function ($limit, $offset) use ($app, $responder) {
        $result = Contribution::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/contribution/search',
    function () use ($app, $responder) {

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/stairway/get/{id:[0-9]+}',
    function ($id) use ($app, $responder) {
        if($model = StairwayToModeration::findFirst($id)){
            $result = $model->toArray();
        }else{
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/stairway/getList/{limit:[0-9]+}/{offset:[0-9]+}',
    function ($limit, $offset) use ($app, $responder) {
        $result = StairwayToModeration::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);
$app->get(
    '/api/v1/stairway/search',
    function () use ($app, $responder) {

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/vote/get/{id:[0-9]+}',
    function ($id) use ($app, $responder) {
        if($model = Vote::findFirst($id)){
            $result = $model->toArray();
        }else{
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/vote/getList/{limit:[0-9]+}/{offset:[0-9]+}',
    function ($limit, $offset) use ($app, $responder) {
        $result = Vote::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/moderation/get/{id:[0-9]+}',
    function ($id) use ($app, $responder) {
        if($model = ModerationStatus::findFirst($id)){
            $result = $model->toArray();
        }else{
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/moderation/getList/{limit:[0-9]+}/{offset:[0-9]+}',
    function ($limit, $offset) use ($app, $responder) {
        $result = ModerationStatus::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/rejection/get/{id:[0-9]+}',
    function ($id) use ($app, $responder) {
        if($model = Rejection::findFirst($id)){
            $result = $model->toArray();
        }else{
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/rejection/getList/{limit:[0-9]+}/{offset:[0-9]+}',
    function ($limit, $offset) use ($app, $responder) {
        $result = Rejection::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/category/get/{id:[0-9]+}',
    function ($id) use ($app, $responder) {
        if($model = Category::findFirst($id)){
            $result = $model->toArray();
        }else{
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/category/getList/{limit:[0-9]+}/{offset:[0-9]+}',
    function ($limit, $offset) use ($app, $responder) {
        $result = Category::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/contributionSigned/get/{id:[0-9]+}',
    function ($id) use ($app, $responder) {
        if($model = ContributionSigned::findFirst("type = $id")){
            $result = $model->toArray();
        }else{
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);
/*!!!!!!!*/
$app->get(
    '/api/v1/contributionSigned/get/{id:[0-9]+}/votes',
    function ($id) use ($app, $responder) {
        if($model = ContributionSigned::findFirst("type = $id")){
            $result = $model->getVotes();
        }else{
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);
/*!!!!*/
$app->get(
    '/api/v1/contributionSigned/getList/{limit:[0-9]+}/{offset:[0-9]+}',
    function ($limit, $offset) use ($app, $responder) {
        $dataModel = ContributionSigned::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $countModel = ContributionSigned:: count();
        $result=["data"=>$dataModel, "meta"=>$countModel];
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);