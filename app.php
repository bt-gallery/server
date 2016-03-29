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
        echo $app['view']->render('index');
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
    '/api/v1/competitvework/list/{limit}/{offset}',
    function ($limit, $offset) use ($app, $responder, $logger) {
        $targetWorks = CompetitiveWork::find(array("limit" => $limit, "offset" => $offset));
        $responder($targetWorks->toArray(), ["Content-Type"=>"application/json"]);
    }
);

$app->get(
    '/api/v1/competitivework/{id}',
    function ($id) use ($app, $responder, $logger) {
        $targetWork = CompetitiveWork::findFirst($id);
        $responder($targetWork, ["Content-Type"=>"application/json"]);
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
        $data = $app->request->getPost();
        $cookies = $app->getDI()->getShared("cookies");
        $app->getDI()->set('crypt', function () {
            $crypt = new Crypt();
            $crypt->setKey('CVklkf%$$dx34zx887*&^');
            return $crypt;
        });
        $vote = new Vote;
        $vote->voteIp = $app->request->getClientAddress();
        $vote->voteAgent = $app->request->getUserAgent();
        $vote->votedAt = time();
        if(isset($data["id_competitive_work"])){
            $vote->competitiveWorkIdCompetitiveWork = $data["id_competitive_work"];
        }else{
            $responder(["error"=>$vote->votedAt], ["Content-Type"=>"application/json"]);
            return;
        }
        $vote->voteHash = hash("sha256", $vote->voteIp . $vote->voteAgent);

        if ($cookies->has("userIdentity") and $cookies->has("lastVoteTime")) {
            $userIdentity = $cookies->get("userIdentity");
            $userIdentity = $userIdentity->getValue();
            $lastVoteTime = $cookies->get("lastVoteTime");
            $lastVoteTime = $lastVoteTime->getValue();
            if(date('Ymd') == date('Ymd', (int) $lastVoteTime)){
                $responder(["denied"=>"time", "label"=>"time constraint", "timestamp"=>date('h:i', time()-(int) $lastVoteTime)], ["Content-Type"=>"application/json"]);
            }else{
                $saveResult = $saver($vote);
                if($saveResult) {
                    $result["success"][] = $saveResult;
                    $logger->addInfo("Vote save success", ["votedAt"=>$vote->votedAt, "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                    $cookies->get("userIdentity")->delete();
                    $cookies->get("lastVoteTime")->delete();
                    $cookies->set("userIdentity", $vote->voteIp.$vote->hash, time()+86400);
                    $cookies->set("lastVoteTime", $vote->votedAt, time()+86400);
                    $responder(["success"=>$vote->votedAt], ["Content-Type"=>"application/json"]);
                }
                else {
                    $logger->addError("Vote save failed", ["votedAt"=>$vote->votedAt, "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                    $responder(["error"=>$vote->votedAt], ["Content-Type"=>"application/json"]);
                    return;
                }
            }
        }else{
            if($lastVotes = Vote::find("voteHash='{$vote->voteHash}'")){
                $lastVote = $lastVotes->getLast();
                if(date('Ymd') == date('Ymd', $lastVote->votedAt)){
                    $voteCount = 0;
                    foreach ($lastVotes as $tmpVote) {
                        if(date('Ymd') == date('Ymd', $tmpVote->votedAt)) $voteCount++;
                    }
                    if($voteCount >= 50){
                        $responder(["denied"=>"time", "label"=>"time constraint", "timestamp"=>date('h:i', time()-(int) $lastVoteTime)], ["Content-Type"=>"application/json"]);
                    }else{
                        $saveResult = $saver($vote);
                        if($saveResult) {
                            $result["success"][] = $saveResult;
                            $logger->addInfo("Vote save success", ["votedAt"=>$vote->votedAt, "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                            $cookies->set("userIdentity", $vote->voteIp.$vote->voteHash, time()+86400);
                            $cookies->set("lastVoteTime", $vote->votedAt, time()+86400);
                            $responder(["success"=>$vote->votedAt], ["Content-Type"=>"application/json"]);
                        }
                        else {
                            $logger->addError("Vote save failed", ["votedAt"=>$vote->votedAt, "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                            $responder(["error"=>$vote->votedAt], ["Content-Type"=>"application/json"]);
                            return;
                        }
                    }
                }else{
                    $saveResult = $saver($vote);
                    if($saveResult) {
                        $result["success"][] = $saveResult;
                        $logger->addInfo("Vote save success", ["votedAt"=>$vote->votedAt, "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                        $cookies->set("userIdentity", $vote->voteIp.$vote->voteHash, time()+86400);
                        $cookies->set("lastVoteTime", $vote->votedAt, time()+86400);
                        $responder(["success"=>$vote->votedAt], ["Content-Type"=>"application/json"]);
                    }
                    else {
                        $logger->addError("Vote save failed", ["votedAt"=>$vote->votedAt, "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                        $responder(["error"=>$vote->votedAt], ["Content-Type"=>"application/json"]);
                        return;
                    }
                }
            }else{
                $saveResult = $saver($vote);
                if($saveResult) {
                    $result["success"][] = $saveResult;
                    $logger->addInfo("Vote save success", ["votedAt"=>$vote->votedAt, "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                    $cookies->set("userIdentity", $vote->voteIp.$vote->voteHash, time()+86400);
                    $cookies->set("lastVoteTime", $vote->votedAt, time()+86400);
                    $responder(["success"=>$vote->votedAt], ["Content-Type"=>"application/json"]);
                }
                else {
                    $logger->addError("Vote save failed", ["votedAt"=>$vote->votedAt, "competitiveWorkIdCompetitiveWork"=>$vote->competitiveWorkIdCompetitiveWork]);
                    $responder(["error"=>$vote->votedAt], ["Content-Type"=>"application/json"]);
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
