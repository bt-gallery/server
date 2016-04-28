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
        $dataModel = Declarant::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $countModel = Declarant:: count("moderation = '3'");
        $result=["data"=>$dataModel, "meta"=>$countModel];
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
        $dataModel = Participant::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $countModel = Participant:: count("moderation = '3'");
        $result=["data"=>$dataModel, "meta"=>$countModel];
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

$app->get(
    '/api/v1/contribution/getList/{limit:[0-9]+}/{offset:[0-9]+}',
    function ($limit, $offset) use ($app, $responder) {
        $dataModel = Contribution::find(array("moderation = '3'", "order" => "priority DESC", "limit" => $limit, "offset" => $offset))->toArray();
        $countModel = Contribution:: count("moderation = '3'");
        $result=["data"=>$dataModel, "meta"=>$countModel];
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
        $dataModel = StairwayToModeration::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $countModel = StairwayToModeration:: count();
        $result=["data"=>$dataModel, "meta"=>$countModel];
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
        $dataModel = Vote::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $countModel = Vote:: count();
        $result=["data"=>$dataModel, "meta"=>$countModel];
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
        $dataModel = ModerationStatus::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $countModel = ModerationStatus:: count();
        $result=["data"=>$dataModel, "meta"=>$countModel];
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
        $dataModel = Rejection::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $countModel = Rejection:: count();
        $result=["data"=>$dataModel, "meta"=>$countModel];
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
        $dataModel = Category::find(array("limit" => $limit, "offset" => $offset))->toArray();
        $countModel = Category:: count();
        $result=["data"=>$dataModel, "meta"=>$countModel];
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

$app->get(
    '/api/v1/contributionSigned/getList/{limit:[0-9]+}/{offset:[0-9]+}',
    function ($limit, $offset) use ($app, $responder) {
        $dataModel = ContributionSigned::find(array("order" => "priority DESC", "contributionModeration = '3'", "limit" => $limit, "offset" => $offset))->toArray();
        $countModel = ContributionSigned:: count("contributionModeration = '3'");
        $result=["data"=>$dataModel, "meta"=>array('total'=>$countModel)];
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/photo/{id:[0-9]+}', function ($id) use ($app, $responder) {
        if($model = Contribution::findFirst($id)){
            $result = $model->toArray();
        }else{
            $result = ["error"=>["message"=>"id not found", "legend"=>"Запись с таким идентефикатором не найдена"]];
            $responder($result, ["Content-Type"=>"application/json"]);
            return;
        }

        if (stristr($app->request->getUserAgent(), "facebookexternalhit") || stristr($app->request->getUserAgent(), "Facebot") || stristr($app->request->getUserAgent(), "OdklBot")) {
            echo $app['view']->render('bot_detail', $result);
        }else{
            echo file_get_contents("index.html");
        }
    }
);

$app->get(
    '/api/v1/search/bymail', function () use ($app, $responder) {
        $filter = new Filter();
        $db = $app->getDI()->getShared("db");

        $rawQuery = $app->request->getQuery("q");
        $query = $filter->sanitize($rawQuery, "email");

        $sqlQuery = "SELECT * FROM contribution LEFT JOIN declarant on contribution.id_declarant = declarant.id WHERE declarant.email='{$query}'";

        $resultSet = $db->query($sqlQuery);
        $resultSet->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $result = $resultSet->fetchAll();
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/search/bysurname/{name}/{limit:[0-9]+}/{offset:[0-9]+}', function ($name, $limit, $offset) use ($app, $responder){
        $dataModel = Contribution::find(array("limit" => $limit, "offset" => $offset, "conditions" => "persons LIKE '%".$name."%'"))->toArray();
        $countModel =count($dataModel);
        $result=["data"=>$dataModel, "meta"=>$countModel];
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);
