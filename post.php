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

/* POST routes */

$app->post(
    '/api/v1/declarant/add',
    function () use ($app, $responder, $servant, $logger) {

    }
);

$app->post(
    '/api/v1/participant/add',
    function () use ($app, $responder, $servant, $logger) {

    }
);

$app->post(
    '/api/v1/contribution/add',
    function () use ($app, $responder, $servant, $logger) {

    }
);

$app->post(
    '/api/v1/contribution/upload',
    function () use ($app, $responder, $servant, $logger) {

    }
);

$app->post(
    '/api/v1/vote/add',
    function () use ($app, $responder, $servant, $logger) {

    }
);

$app->post(
    '/api/v1/stairway/add',
    function () use ($app, $responder, $servant, $logger) {

    }
);

$app->post(
    '/api/v1/specification/add',
    function () use ($app, $responder, $servant, $logger) {

    }
);

$app->post(
    '/api/v1/moderation/add',
    function () use ($app, $responder, $servant, $logger) {

    }
);

$app->post(
    '/api/v1/rejection/add',
    function () use ($app, $responder, $servant, $logger) {

    }
);

$app->post(
    '/api/v1/category/add',
    function () use ($app, $responder, $servant, $logger) {

    }
);
































































$app->post(
    '/api/v1/declarant/add',
    function () use ($app, $responder, $servant, $logger) {
        $model = new $Declarant;
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
    '/api/v1/participant/add',
    function () use ($app, $responder, $servant, $logger) {
        $model = new $Participant;
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
    '/api/v1/contribution/bind',
    function () use ($app, $responder, $logger) {
        $data = $app->request->getPost();
        $model = Contribution::findFirst($data["id"]);
        $mapper = $app->di->getService("mapper")->getDefinition();
        $saver = $app->di->getService("saver")->getDefinition();
        $result = $saver(
            $mapper($model,$data)
        );
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->post(
    '/api/v1/contribution/upload',
    function () use ($app, $config, $responder, $logger) {
        if ($app->request->hasFiles()) {
            $saver = $app->di->getService("saver")->getDefinition();
            foreach ($app->request->getUploadedFiles() as $key => $file) {
                $model = new Contribution;
                $saver($model);

                $fileExtension = pathinfo($file->getName(), PATHINFO_EXTENSION);
                $fileName      = floor(microtime(true)) . "_{$key}.{$fileExtension}";
                $fileTmpPath   = $file->getTempName();
                $fileDirectory = $config->application->uploadDir . "works/{$model->idCompetitiveWork}/";
                $fileFullPath  = $fileDirectory . $fileName;
                $fileTmpSize   = $file->getSize();

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
                    $model->fileSize = $fileTmpSize;
                    $model->type = $fileExtension;
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
        $declarant = Declarant::findFirst($data["id"]);

        $jobData["declarant"] = $declarant->toArray();
        $jobData["participants"] = Participant::find("idDeclarant={$declarant->id}")->toArray();

        foreach($declarant->Contribution as $contribution){
            $contribution->bet = 1;
            $saveResult = $saver($contribution);

            if($saveResult) {
                $result["success"][] = $saveResult;
                $logger->addInfo("bid succeed", ["idDeclarant"=>$declarant->idDeclarant, "idParticipant"=>$contribution->idParticipant]);
            }
            else {
                $logger->addError("bid failed", ["idDeclarant"=>$declarant->idDeclarant, "idParticipant"=>$contribution->idParticipant]);
                $responder(["error"=>$contribution->id], ["Content-Type"=>"application/json"]);
                return;
            }

            $stairway = new StairwayToModeration;
            $stairway->idContribution = $contribution->id;
            $contribution->moderation = 0;

            $saveResult = $saver($stairway);

            if($saveResult) {
                $result["success"][] = $saveResult;
                $logger->addInfo(
                    "stack push created",
                    [
                        "idDeclarant"=>$declarant->idDeclarant,
                        "idContribution"=>$contribution->idCompetitiveWork,
                        "idStairwayToModeration"=>$stairway->id
                    ]
                );
            }
            else {
                $logger->addError("stack push failed", ["idDeclarant"=>$declarant->idDeclarant, "idCompetitiveWork"=>$competitiveWork->idCompetitiveWork]);
                $responder(["error"=>$competitiveWork->idCompetitiveWork], ["Content-Type"=>"application/json"]);
                return;
            }

            $logger->addInfo("stack num applied", ["result" => $stairway->initQueueNum()]);
            $jobData["queueNum"][$competitiveWork->idParticipant] = $stairway->queueNum;
        }

        $queue($jobData, Job::MAIL_DECLARANT_REGISTRATION,Status::NEW_ONE);
        $responder($result, ["Content-Type"=>"application/json"]);

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

        if (($vote->voteGroup==0 and isset($lastVoteTimeUndef))
        or($vote->voteGroup==1 and isset($lastVoteTimeChild))
        or($vote->voteGroup==2 and isset($lastVoteTimeJunior))
        or($vote->voteGroup==3 and isset($lastVoteTimeTeen))) {
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
                    if($voteCount >= 5){
                        $responder(["error"=>["reason"=>"time constraint", "label"=>"Вы уже голосовали сегодня", "timestamp"=>$diffDateTimeHash->format("%h:%I")]], ["Content-Type"=>"application/json"]);
                    }else{
                        $saveResult = $saver($vote);
                        if($saveResult) {
                            $result["success"][] = $saveResult;
                            $logger->addInfo("Vote save success", ["votedAt"=>(new DateTime("now"))->format("Y-m-d H:i:s"), "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
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