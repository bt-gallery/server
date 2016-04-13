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
    '/api/v1/declarant/get', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/declarant/getList', 
    function () use ($app, $responder) {


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
    '/api/v1/participant/get', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/participant/getList', 
    function () use ($app, $responder) {


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
    '/api/v1/contribution/get', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/contribution/getList', 
    function () use ($app, $responder) {


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
    '/api/v1/stairway/get', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/stairway/getList', 
    function () use ($app, $responder) {


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
    '/api/v1/vote/get', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/vote/getList', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/specification/get', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/specification/getList', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/moderation/get', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/moderation/getList', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/rejection/get', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/rejection/getList', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/category/get', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/category/getList', 
    function () use ($app, $responder) {


        $responder($result, ["Content-Type"=>"application/json"]);
    }
);































































$app->get(
    '/declarant', function () use ($app) {
    }
);

$app->get(
    '/api/v1/search/bymail', function () use ($app, $responder) {
        $filter = new Filter();
        $db = $app->getDI()->getShared("db");

        $rawQuery = $app->request->getQuery("q");
        $query = $filter->sanitize($rawQuery, "email");

        $sqlQuery = "SELECT * FROM moderation_stack_grouped WHERE email='{$query}'";

        $resultSet = $db->query($sqlQuery);
        $resultSet->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $result = $resultSet->fetchAll();
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/gallery/list/{limit}/{offset}/ages/{minAge}/{maxAge}',
    function ($limit, $offset, $minAge, $maxAge) use ($app, $responder, $logger) {
        $result = array();
        $db = $app->getDI()->getShared("db");
        $filter = new Filter();
        $limit = $filter->sanitize($limit, "int");
        $offset = $filter->sanitize($offset, "int");
        $minAge = $filter->sanitize($minAge, "int");
        $maxAge = $filter->sanitize($maxAge, "int");
        if( !($limit >= 0 and $offset >= 0 and $minAge >= 0 and $maxAge > $minAge) ) {
            echo $app['view']->render('404');
            return false;
        }
        $sql = "SELECT * FROM moderation_stack_grouped WHERE age BETWEEN '{$minAge}' AND '{$maxAge}' AND result='одобрено' LIMIT {$limit} OFFSET {$offset}";
        try {
            $resultSet = $db->query($sql);
            $resultSet->setFetchMode(Phalcon\Db::FETCH_ASSOC);
            $targetWorks = $resultSet->fetchAll();

            $sql = "SELECT * FROM moderation_stack_grouped WHERE age BETWEEN '{$minAge}' AND '{$maxAge}' AND result='одобрено'";
            $resultSetTmp = $db->query($sql);
            $resultSetTmp->setFetchMode(Phalcon\Db::FETCH_ASSOC);
            $targetWorksTmp = $resultSetTmp->fetchAll();
            $resultSetCount = count($targetWorksTmp);
        } catch (\Exception $e) {
            echo $app['view']->render('404');
            return false;
        }
        foreach ($targetWorks as $key=>&$work){
            $age = $work["age"];
            $t1 = $age % 10;
            $t2 = $age % 100;
            $age = ($t1 == 1 && $t2 != 11 ? "год" : ($t1 >= 2 && $t1 <= 4 && ($t2 < 10 || $t2 >= 20) ? "года" : "лет"));
            $work['age_string'] = $age;
            $work['participant'] = $work['name'] . " " .$work["surname"];
            $work['webPath'] = $work['web_url'];
            $work['idCompetitiveWork'] = $work['id_competitive_work'];
            $tmpId = $work['id_competitive_work'];
            $work['votes'] = Vote::count("competitiveWorkIdCompetitiveWork = '$tmpId'");
        }
        $result['targetWorks'] = $targetWorks;

        if ($offset!=0) {
            if($limit > $offset){
                $result['prev_page_offset'] = 0;
                $result['first_page_offset'] = 0;
            }else{
                if($offset-$limit > $resultSetCount){
                    $result['prev_page_offset'] = $resultSetCount-$limit;
                    $result['first_page_offset'] = 0;
                }else{
                    $result['prev_page_offset'] = $offset-$limit;
                    $result['first_page_offset'] = 0;
                }
            }
        }
        if ($resultSetCount-$offset > $limit) {
            $result['next_page_offset'] = $offset+$limit;
            $result['last_page_offset'] = $resultSetCount-$limit;
        }
        if ($minAge >= 0 and $maxAge > $minAge){
            $result['min_age'] = $minAge;
            $result['max_age'] = $maxAge;
        }
        if($limit>0){
            $result['limit'] = $limit;
        }

        echo $app['view']->render('gallery', $result);
    }
);

$app->get(
    '/gallery/final', function () use ($app) {
        $db = $app->getDI()->getShared("db");

        $sql = "SELECT * FROM final_child LIMIT 3";
        $resultSet = $db->query($sql);
        $resultSet->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $finalChild = $resultSet->fetchAll();
        $result['finalChild'] = $finalChild;

        $sql = "SELECT * FROM final_child WHERE id_competitive_work IN (4117, 4300, 4314)";
        $resultSet = $db->query($sql);
        $resultSet->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $finalBestChild = $resultSet->fetchAll();
        $result['finalBestChild'] = $finalBestChild;

        $sql = "SELECT * FROM final_junior LIMIT 3";
        $resultSet = $db->query($sql);
        $resultSet->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $finalJunior = $resultSet->fetchAll();
        $result['finalJunior'] = $finalJunior;

        $sql = "SELECT * FROM final_junior WHERE id_competitive_work IN (819, 312, 2969)";
        $resultSet = $db->query($sql);
        $resultSet->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $finalBestJunior= $resultSet->fetchAll();
        $result['finalBestJunior'] = $finalBestJunior;

        $sql = "SELECT * FROM final_teen LIMIT 3";
        $resultSet = $db->query($sql);
        $resultSet->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $finalTeen = $resultSet->fetchAll();
        $result['finalTeen'] = $finalTeen;

        $sql = "SELECT * FROM final_teen WHERE id_competitive_work IN (357, 5309, 1548)";
        $resultSet = $db->query($sql);
        $resultSet->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $finalBestTeen= $resultSet->fetchAll();
        $result['finalBestTeen'] = $finalBestTeen;

        echo $app['view']->render('results', $result);
    }
);