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

/* GET routes */
$app->get(
    '/', function () use ($app) {
        $app->response->redirect("gallery/list/30/0/ages/4/18")->sendHeaders();
    }
);

$app->get(
    '/declarant', function () use ($app) {
        echo $app['view']->render('index');
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

$app->post(
    '/gallery/search/byname',
    function () use ($app, $responder, $logger, $config) {
        $result = array();
        $filter = new Filter;
        $db = $app->getDI()->getShared("db");

        $query = $app->request->getPost("query");
        $query = $filter->sanitize($query, "string");

        $sql = "SELECT * FROM `moderation_stack_grouped` where concat(name,' ',surname) rlike '{$query}' AND result='одобрено'";
        $resultSet = $db->query($sql);
        $resultSet->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $targetWorks = $resultSet->fetchAll();

        foreach ($targetWorks as $key=>&$work){
            $age = $work["age"];
            $t1 = $age % 10;
            $t2 = $age % 100;
            $age = ($t1 == 1 && $t2 != 11 ? "год" : ($t1 >= 2 && $t1 <= 4 && ($t2 < 10 || $t2 >= 20) ? "года" : "лет"));
            $work['age_string'] = $age;
            $work['participant'] = $work['name'] . " " .$work["surname"];
            $work['webPath'] = $work['web_url'];
            $work['idCompetitiveWork'] = $work['id_competitive_work'];
        }
        $result['targetWorks'] = $targetWorks;
        $result['query'] = $query;
        $result['limit'] = $config->application->galleryLimit;

        echo $app['view']->render('gallery', $result);
    }
);

$app->get(
    '/gallery/drawing/{id}',
    function ($id) use ($app) {
        $filter = new Filter();
        $id = $filter->sanitize($id, "int");
        $targetWork = CompetitiveWork::findFirst($id)->toArray();
        $participant = Participant::findfirst($targetWork['idParticipant']);
        $declarant = Declarant::findfirst($targetWork['idDeclarant']);
        $targetWork['participant_name']=$participant->name;
        $targetWork['participant_surname']=$participant->surname;
        $targetWork['declarant_name']=$declarant->name;
        $targetWork['declarant_surname']=$declarant->surname;
        $targetWork['age']=$participant->age;
        $age = abs($participant->age);
        $requestHash = hash("sha256", $_SERVER['HTTP_X_FORWARDED_FOR'] . $app->request->getUserAgent() . Participant::getGroupS($age));
        $t1 = $age % 10;
        $t2 = $age % 100;
        $age = ($t1 == 1 && $t2 != 11 ? "год" : ($t1 >= 2 && $t1 <= 4 && ($t2 < 10 || $t2 >= 20) ? "года" : "лет"));
        $targetWork['age_string'] = $age;

        $cookies = $app->getDI()->getShared("cookies");
        $app->getDI()->set('crypt', function () {
            $crypt = new Crypt();
            $crypt->setKey('CV##@k87?lkf46_7%$$dx3.4zx8*&^g');
            return $crypt;
        });

        $canVote = Vote::checkVote($cookies, $requestHash, $targetWork['age']);
        if($id>0){
            $prevWork = $id-1;
        }
        if (CompetitiveWork::findFirst($id+1)) { //нужно пересмотреть логику
            $nextWork = $id+1;
        }
        $targetVotes = Vote::count("competitiveWorkIdCompetitiveWork = '$id'");

        $result = array(
            'targetWork' => $targetWork,
            'canVote'=>$canVote,
            'nextWork'=>$nextWork,
            'prevWork'=>$prevWork,
            'votes'=>$targetVotes
        );

        echo $app['view']->render('detail', $result);
    }
);

/* POST routes */
$app->post(
    '/api/v1/{modelName:declarant}',
    function ($modelName) use ($app, $responder, $servant, $logger) {
        $modelName = ucfirst($modelName);
        $model = new $modelName;
        $data = $app->request->getPost();
        $mapper = $servant("mapper");
        $saver = $servant("saver");
        $queue = $app->di->getService("queue")->getDefinition();
        $result = $saver(
            $mapper($model,$data)
        );

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->post(
    '/api/v1/{modelName:participant}',
    function ($modelName) use ($app, $responder, $servant, $logger) {
        $modelName = ucfirst($modelName);
        $model = new $modelName;
        $data = $app->request->getPost();
        $mapper = $servant("mapper");
        $saver = $servant("saver");
        $queue = $app->di->getService("queue")->getDefinition();
        $result = $saver(
            $mapper($model,$data)
        );

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->post(
    '/api/v1/{modelName:address}',
    function ($modelName) use ($app, $responder, $servant, $logger) {
        $modelName = ucfirst($modelName);
        $model = new $modelName;
        $data = $app->request->getPost();
        $mapper = $servant("mapper");
        $saver = $servant("saver");
        $queue = $app->di->getService("queue")->getDefinition();
        $result = $saver(
            $mapper($model,$data)
        );

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->post(
    '/api/v1/competitive/bind',
    function () use ($app, $responder, $logger) {
        $data = $app->request->getPost();
        $model = CompetitiveWork::findFirst($data["idCompetitiveWork"]);
        $mapper = $app->di->getService("mapper")->getDefinition();
        $saver = $app->di->getService("saver")->getDefinition();
        $result = $saver(
            $mapper($model,$data)
        );
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->post(
    '/api/v1/competitive-work/upload',
    function () use ($app, $config, $responder, $logger) {
        if ($app->request->hasFiles()) {
            $saver = $app->di->getService("saver")->getDefinition();
            foreach ($app->request->getUploadedFiles() as $key => $file) {
                $model = new CompetitiveWork;
                $saver($model);

                $fileExtension = pathinfo($file->getName(), PATHINFO_EXTENSION);
                $fileName      = floor(microtime(true)) . "_{$key}.{$fileExtension}";
                $fileTmpPath   = $file->getTempName();
                $fileDirectory = $config->application->uploadDir . "works/{$model->idCompetitiveWork}/";
                $fileFullPath  = $fileDirectory . $fileName;

                mkdir($fileDirectory, 0755, true);
                $moveRes = move_uploaded_file($fileTmpPath, $fileFullPath);

                $pathExists     = file_exists($fileDirectory) ? "yes" : "no";
                $isWritable     = is_writable($fileDirectory) ? "yes" : "no";

                $logger->addDebug("file: " . $fileName . " " . $file->getSize());
                $logger->addDebug("source: " . $fileTmpPath);
                $logger->addDebug("destination: " . $fileFullPath);
                $logger->addDebug("exists: " . $pathExists);
                $logger->addDebug("is writable: " . $isWritable);

                if ($moveRes) {
                    $model->storePath = $fileFullPath;
                    $model->webPath = "/files/works/{$model->idCompetitiveWork}/{$fileName}";
                    $model->fileName = $fileName;
                    $modelResult = $saver($model);
                    $logger->addInfo("file: {$fileFullPath} saved");
                } else {
                    $logger->addError("file: {$fileFullPath} saving failed");
                }
                $content[] = $modelResult;
            }

            $responder($content);
        }
    }
);

$app->post(
    "/api/v1/competitive-bid/register",
    function () use ($app, $logger, $responder, $servant) {
        $saver = $servant("saver");
        $queue = $servant("queue");
        $data = $app->request->getPost();
        $declarant = Declarant::findFirst($data["idDeclarant"]);

        $jobData["declarant"] = $declarant->toArray();
        $jobData["participants"] = Participant::find("idDeclarant={$declarant->idDeclarant}")->toArray();

        foreach($declarant->CompetitiveWork as $competitiveWork){
            $competitiveWork->bet = 1;
            $saveResult = $saver($competitiveWork);

            if($saveResult) {
                $result["success"][] = $saveResult;
                $logger->addInfo("bid succeed", ["idDeclarant"=>$declarant->idDeclarant, "idParticipant"=>$competitiveWork->idParticipant]);
            }
            else {
                $logger->addError("bid failed", ["idDeclarant"=>$declarant->idDeclarant, "idParticipant"=>$competitiveWork->idParticipant]);
                $responder(["error"=>$competitiveWork->idCompetitiveWork], ["Content-Type"=>"application/json"]);
                return;
            }

            $moderationStack = new ModerationStack;
            $moderationStack->idCompetitiveWork = $competitiveWork->idCompetitiveWork;
            $moderationStack->status = 0;

            $saveResult = $saver($moderationStack);

            if($saveResult) {
                $result["success"][] = $saveResult;
                $logger->addInfo(
                    "stack push created",
                    [
                        "idDeclarant"=>$declarant->idDeclarant,
                        "idCompetitiveWork"=>$competitiveWork->idCompetitiveWork,
                        "idModerationStack"=>$moderationStack->idModerationStack
                    ]
                );
            }
            else {
                $logger->addError("stack push failed", ["idDeclarant"=>$declarant->idDeclarant, "idCompetitiveWork"=>$competitiveWork->idCompetitiveWork]);
                $responder(["error"=>$competitiveWork->idCompetitiveWork], ["Content-Type"=>"application/json"]);
                return;
            }

            $logger->addInfo("stack num applied", ["result" => $moderationStack->initQueueNum()]);
            $jobData["queueNum"][$competitiveWork->idParticipant] = $moderationStack->queueNum;
        }

        $queue($jobData, Job::MAIL_DECLARANT_REGISTRATION,Status::NEW_ONE);
        $responder($result, ["Content-Type"=>"application/json"]);

    }
);

$app->post(
    '/api/v1/vote',
    function () use ($app, $responder, $servant, $logger) {
        $saver = $servant("saver");
        $data = $app->request->getPost() ? $app->request->getPost() : (array) json_decode(file_get_contents("php://input"));
        $cookies = $app->getDI()->getShared("cookies");
        $app->getDI()->set('crypt', function () {
            $crypt = new Crypt();
            $crypt->setKey('CV##@k87?lkf46_7%$$dx3.4zx8*&^g');
            return $crypt;
        });
        $vote = new Vote;
        $vote->voteIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $vote->voteAgent = $app->request->getUserAgent();
        //$vote->votedAt = new DateTime("now");
        $tomorrowDateTime = new DateTime("tomorrow");
        if(isset($data["id_competitive_work"])){
            $db = $app->getDI()->getShared("db");
            $filter = new Filter();
            $vote->competitiveWorkIdCompetitiveWork = $filter->sanitize($data["id_competitive_work"], "int");
        }else{
            $responder(["error"=>["reason"=>"id not found", "label"=>"Изображение не найдено", (new DateTime("now"))->format("Y-m-d H:i:s")]], ["Content-Type"=>"application/json"]);
            return;
        }

        $sql = "SELECT age FROM moderation_stack_grouped WHERE id_competitive_work='{$vote->competitiveWorkIdCompetitiveWork}'";
        $resultSet = $db->query($sql);
        $resultSet->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $targetAge = $resultSet->fetchAll();

        $vote->voteGroup = Participant::getGroupS($targetAge[0]['age']);

        $vote->voteHash = hash("sha256", $vote->voteIp . $vote->voteAgent . $vote->voteGroup);

        if ($cookies->has("userIdentity")) {
            $userIdentity = $cookies->get("userIdentity");
            $userIdentity = $userIdentity->getValue();
            if ($cookies->has("lastVoteTimeUndef")) {
                $lastVoteTimeUndef = $cookies->get("lastVoteTimeUndef");
                $lastVoteTimeUndef = $lastVoteTimeUndef->getValue();
            }
            if ($cookies->has("lastVoteTimeChild")) {
                $lastVoteTimeChild = $cookies->get("lastVoteTimeChild");
                $lastVoteTimeChild = $lastVoteTimeChild->getValue();
            }
            if ($cookies->has("lastVoteTimeJunior")) {
                $lastVoteTimeJunior = $cookies->get("lastVoteTimeJunior");
                $lastVoteTimeJunior = $lastVoteTimeJunior->getValue();
            }
            if ($cookies->has("lastVoteTimeTeen")) {
                $lastVoteTimeTeen = $cookies->get("lastVoteTimeTeen");
                $lastVoteTimeTeen = $lastVoteTimeTeen->getValue();
            }

            switch ($vote->voteGroup) {
                case 0:
                    if(isset($lastVoteTimeUndef)){
                        $voteDateTime = new DateTime($lastVoteTimeUndef);
                        $diffDateTimeCookie = $voteDateTime->diff($tomorrowDateTime);
                    }else{
                        $diffDateTimeCookie = (new DateTime("now"))->diff(new DateTime("tomorrow + 1day"));
                    }
                    break;
                case 1:
                    if(isset($lastVoteTimeChild)){
                        $voteDateTime = new DateTime($lastVoteTimeChild);
                        $diffDateTimeCookie = $voteDateTime->diff($tomorrowDateTime);
                    }else{
                        $diffDateTimeCookie = (new DateTime("now"))->diff(new DateTime("tomorrow + 1day"));
                    }
                    break;
                case 2:
                    if(isset($lastVoteTimeJunior)){
                        $voteDateTime = new DateTime($lastVoteTimeJunior);
                        $diffDateTimeCookie = $voteDateTime->diff($tomorrowDateTime);
                    }else{
                        $diffDateTimeCookie = (new DateTime("now"))->diff(new DateTime("tomorrow + 1day"));
                    }
                    break;
                case 3:
                    if(isset($lastVoteTimeTeen)){
                        $voteDateTime = new DateTime($lastVoteTimeTeen);
                        $diffDateTimeCookie = $voteDateTime->diff($tomorrowDateTime);
                    }else{
                        $diffDateTimeCookie = (new DateTime("now"))->diff(new DateTime("tomorrow + 1day"));
                    }
                    break;
            }
            if($diffDateTimeCookie->d == 0){
                $responder(["error"=>["reason"=>"time constraint", "label"=>"Вы уже голосовали сегодня", "timeStamp"=>$diffDateTimeCookie->format("%h:%I")]], ["Content-Type"=>"application/json"]);
            }else{
                $saveResult = $saver($vote);
                if($saveResult) {
                    $result["success"][] = $saveResult;
                    $logger->addInfo("Vote save success", ["votedGroup"=>"", "votedAt"=>(new DateTime("now"))->format("Y-m-d H:i:s"), "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                    $cookies->get("userIdentity")->delete();
                    $cookies->set("userIdentity", $vote->voteIp.$vote->hash, time()+86400);
                    switch ($vote->voteGroup) {
                        case 0:
                            $cookies->get("lastVoteTimeUndef")->delete();
                            $cookies->set("lastVoteTimeUndef", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                            break;
                        case 1:
                            $cookies->get("lastVoteTimeChild")->delete();
                            $cookies->set("lastVoteTimeChild", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                            break;
                        case 2:
                            $cookies->get("lastVoteTimeJunior")->delete();
                            $cookies->set("lastVoteTimeJunior", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                            break;
                        case 3:
                            $cookies->get("lastVoteTimeTeen")->delete();
                            $cookies->set("lastVoteTimeTeen", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                            break;
                    }
                    $responder(["success"=>["reason"=>"vote saved", "label"=>"Ваш голос принят", (new DateTime("now"))->format("Y-m-d H:i:s")]], ["Content-Type"=>"application/json"]);
                }
                else {
                    $logger->addError("Vote save failed", ["votedAt"=>(new DateTime("now"))->format("Y-m-d H:i:s"), "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                    $responder(["error"=>["reason"=>"save error", "label"=>"Не удалось сохранить голос", (new DateTime("now"))->format("Y-m-d H:i:s")]], ["Content-Type"=>"application/json"]);
                    return;
                }
            }
        }else{
            if($lastVotes = Vote::find("voteHash='{$vote->voteHash}'")){
                $lastVote = $lastVotes->getLast();
                $voteDateTime = new DateTime($lastVote->votedAt);
                $diffDateTimeHash = $voteDateTime->diff($tomorrowDateTime);
                if($diffDateTimeHash->d == 0){
                    $voteCount = 0;
                    foreach ($lastVotes as $tmpVote) {
                        $voteDateTime = new DateTime($tmpVote->votedAt);
                        $diffDateTime = $voteDateTime->diff($tomorrowDateTime);
                        if($diffDateTime->d == 0) $voteCount++;
                    }
                    if($voteCount >= 50){
                        $responder(["error"=>["reason"=>"time constraint", "label"=>"Вы уже голосовали сегодня", "timestamp"=>$diffDateTimeHash->format("%h:%I")]], ["Content-Type"=>"application/json"]);
                    }else{
                        $saveResult = $saver($vote);
                        if($saveResult) {
                            $result["success"][] = $saveResult;
                            $logger->addInfo("Vote save success", ["votedAt"=>(new DateTime("now"))->format("Y-m-d H:i:s"), "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                            $cookies->set("userIdentity", $vote->voteIp.$vote->voteHash, time()+86400);
                            switch ($vote->voteGroup) {
                                case 0:
                                    $cookies->set("lastVoteTimeUndef", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                                    break;
                                case 1:
                                    $cookies->set("lastVoteTimeChild", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                                    break;
                                case 2:
                                    $cookies->set("lastVoteTimeJunior", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                                    break;
                                case 3:
                                    $cookies->set("lastVoteTimeTeen", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                                    break;
                            }
                            $responder(["success"=>["reason"=>"vote saved", "label"=>"Ваш голос принят", (new DateTime("now"))->format("Y-m-d H:i:s")]], ["Content-Type"=>"application/json"]);
                        }
                        else {
                            $logger->addError("Vote save failed", ["votedAt"=>(new DateTime("now"))->format("Y-m-d H:i:s"), "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                            $responder(["error"=>["reason"=>"save error", "label"=>"Не удалось сохранить голос", (new DateTime("now"))->format("Y-m-d H:i:s")]], ["Content-Type"=>"application/json"]);
                            return;
                        }
                    }
                }else{
                    $saveResult = $saver($vote);
                    if($saveResult) {
                        $result["success"][] = $saveResult;
                        $logger->addInfo("Vote save success", ["votedAt"=>(new DateTime("now"))->format("Y-m-d H:i:s"), "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                        $cookies->set("userIdentity", $vote->voteIp.$vote->voteHash, time()+86400);
                        switch ($vote->voteGroup) {
                            case 0:
                                $cookies->set("lastVoteTimeUndef", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                                break;
                            case 1:
                                $cookies->set("lastVoteTimeChild", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                                break;
                            case 2:
                                $cookies->set("lastVoteTimeJunior", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                                break;
                            case 3:
                                $cookies->set("lastVoteTimeTeen", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                                break;
                        }
                        $responder(["success"=>["reason"=>"vote saved", "label"=>"Ваш голос принят", (new DateTime("now"))->format("Y-m-d H:i:s")]], ["Content-Type"=>"application/json"]);
                    }
                    else {
                        $logger->addError("Vote save failed", ["votedAt"=>(new DateTime("now"))->format("Y-m-d H:i:s"), "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                        $responder(["error"=>["reason"=>"save error", "label"=>"Не удалось сохранить голос", (new DateTime("now"))->format("Y-m-d H:i:s")]], ["Content-Type"=>"application/json"]);
                        return;
                    }
                }
            }else{
                $saveResult = $saver($vote);
                if($saveResult) {
                    $result["success"][] = $saveResult;
                    $logger->addInfo("Vote save success", ["votedAt"=>(new DateTime("now"))->format("Y-m-d H:i:s"), "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                    $cookies->set("userIdentity", $vote->voteIp.$vote->voteHash, time()+86400);
                    switch ($vote->voteGroup) {
                        case 0:
                            $cookies->set("lastVoteTimeUndef", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                            break;
                        case 1:
                            $cookies->set("lastVoteTimeChild", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                            break;
                        case 2:
                            $cookies->set("lastVoteTimeJunior", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                            break;
                        case 3:
                            $cookies->set("lastVoteTimeTeen", (new DateTime("now"))->format("Y-m-d H:i:s"), time()+86400);
                            break;
                    }
                    $responder(["success"=>["reason"=>"vote saved", "label"=>"Ваш голос принят",(new DateTime("now"))->format("Y-m-d H:i:s")]], ["Content-Type"=>"application/json"]);
                }
                else {
                    $logger->addError("Vote save failed", ["votedAt"=>(new DateTime("now"))->format("Y-m-d H:i:s"), "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                    $responder(["error"=>["reason"=>"save error", "label"=>"Не удалось сохранить голос", (new DateTime("now"))->format("Y-m-d H:i:s")]], ["Content-Type"=>"application/json"]);
                    return;
                }
            }
        }
    }
);

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
