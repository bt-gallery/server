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
        if (Declarant::findFirst($data['id']) && isset($data['id'])) {
            $model = Declarant::findFirst($data['id']);
            if(isset($data['time']))unset($data['time']);
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $result = $saver(
                $mapper($model,$data)
            );
        }else {
            $result = ["error"=>["message"=>"Declarant id not found", "legend"=>"Заявитель с таким идентефикатором не найден"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }

);

$app->put(
    '/api/v1/participant/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        if ((!Declarant::findFirst($data['idDeclarant'])) && isset($data['idDeclarant'])) {
            $result = ["error"=>["message"=>"Declarant id not found", "legend"=>"Заявитель с таким идентефикатором не найден"]];
            $responder($result, ["Content-Type"=>"application/json"]);
            return;
        }
        if (Participant::findFirst($data['id']) && isset($data['id'])) {
            $model = Participant::findFirst($data['id']);
            if(isset($data['time']))unset($data['time']);
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $result = $saver(
                $mapper($model,$data)
            );
        }else {
            $result = ["error"=>["message"=>"Participant id not found", "legend"=>"Участник с таким идентефикатором не найден"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->put(
    '/api/v1/contribution/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        if ((!Participant::findFirst($data['idParticipant'])) && isset($data['idParticipant'])) {
            $result = ["error"=>["message"=>"Participant id not found", "legend"=>"Участник с таким идентефикатором не найден"]];
            $responder($result, ["Content-Type"=>"application/json"]);
            return;
        }
        if (Contribution::findFirst($data['id']) && isset($data['id'])) {
            $model = Contribution::findFirst($data['id']);
            if(isset($data['time']))unset($data['time']);
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $result = $saver(
                $mapper($model,$data)
            );
        }else {
            $result = ["error"=>["message"=>"Contribution id not found", "legend"=>"Работа с таким идентефикатором не найдена"]];
        }
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->put(
    '/api/v1/contributionSigned/bind',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        $dataId['idDeclarant'] = $data['idDeclarant'];
        $dataId['idParticipant'] = $data['idParticipant'];
        $dataId['idContribution'] = $data['idContribution'];
        if ((!Declarant::findFirst($data['idDeclarant'])) && isset($data['idDeclarant'])) {
            $result = ["error"=>["message"=>"Declarant id not found", "legend"=>"Заявитель с таким идентефикатором не найден"]];
            $responder($result, ["Content-Type"=>"application/json"]);
            return;
        }
        if ((!Participant::findFirst($data['idParticipant'])) && isset($data['idParticipant'])) {
            $result = ["error"=>["message"=>"Participant id not found", "legend"=>"Участник с таким идентефикатором не найден"]];
            $responder($result, ["Content-Type"=>"application/json"]);
            return;
        }
        if (Contribution::findFirst($data['idContribution']) && isset($data['idContribution'])) {
            $model = Contribution::findFirst($data['idContribution']);
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $result[] = $saver(
                $mapper($model,$dataId)
            );
        }else {
            $result = ["error"=>["message"=>"Contribution id not found", "legend"=>"Работа с таким идентефикатором не найдена"]];
            $responder($result, ["Content-Type"=>"application/json"]);
            return;
        }
        if (Participant::findFirst($data['idParticipant']) && isset($data['idParticipant'])) {
            $model = Participant::findFirst($data['idParticipant']);
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $result[] = $saver(
                $mapper($model,$dataId)
            );
        }else {
            $result = ["error"=>["message"=>"Participant id not found", "legend"=>"Участник с таким идентефикатором не найден"]];
            $responder($result, ["Content-Type"=>"application/json"]);
            return;
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
    '/api/v1/moderation/update',
    function () use ($app, $responder, $servant) {
        $data = $app->request->getPut();
        if (ModerationStatus::findFirst($data['id']) && isset($data['id'])) {
            $model = ModerationStatus::findFirst($data['id']);
            $mapper = $servant("mapper");
            $saver = $servant("saver");
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
        if (Rejection::findFirst($data['id']) && isset($data['id'])) {
            $model = Rejection::findFirst($data['id']);
            $mapper = $servant("mapper");
            $saver = $servant("saver");
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
        if (Category::findFirst($data['id']) && isset($data['id'])) {
            $model = Category::findFirst($data['id']);
            $mapper = $servant("mapper");
            $saver = $servant("saver");
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
    "/api/v1/register",
    function () use ($app, $logger, $responder, $servant) {
        $saver = $servant("saver");
        $jobQueue = $servant("jobQueue");
        $stairway = new StairwayToModeration;
        $data = $app->request->getPut();
        $declarant = Declarant::findFirst($data["idDeclarant"]);
        $declarant->moderation = 0;
        $saveResult = $saver($declarant);
        
        if($saveResult) {
            $result["success"][] = $saveResult;
            $logger->addInfo("Declarant status: awaiting moderation", ["idDeclarant"=>$declarant->id]);
        }else {
            $logger->addError("Declarant status change failed", ["idDeclarant"=>$declarant->id]);
            $responder(["error"=>["message"=>"Declarant register failed", "legend"=>"Не удалось зарегистрировать заявителя", "idDeclarant"=>$declarant->id]], ["Content-Type"=>"application/json"]);
            return;
        }

        $jobData["declarant"] = $declarant->toArray();
        $participants = $declarant->getParticipants();
        $jobData["participants"] = $participants->toArray();

        foreach($participants as $participant){
            $participant->moderation = 0;
            $saveResult = $saver($participant);

            if($saveResult) {
                $result["success"][] = $saveResult;
                $logger->addInfo("Participant status: awaiting moderation", ["idParticipant"=>$participant->id]);

                $contributions = $participant->getContributions();
                $jobData["contributions"] = $contributions->toArray();
                foreach($contributions as $contribution){
                    $contribution->moderation = 0;
                    $saveResult = $saver($contribution);

                    if($saveResult) {
                        $result["success"][] = $saveResult;
                        $logger->addInfo("Contribution status: awaiting moderation", ["idContribution"=>$contribution->id]);

                        $stairway->idContribution = $contribution->id;
                        $saveResult = $saver($stairway);
                        if($saveResult) {
                            $result["success"][] = $saveResult;
                            $logger->addInfo(
                                "Contribution stepped on the stairway",
                                [
                                    "idStairwayToModeration"=>$stairway->id,
                                    "idContribution"=>$contribution->id,
                                    "idDeclarant"=>$declarant->id
                                ]
                            );
                            $logger->addInfo("Stairway appended", ["result" => $stairway->id]);
                            //end
                        }else {
                            $logger->addError("Contribution step failed", ["idDeclarant"=>$declarant->id, "idContribution"=>$contribution->id]);
                            $responder(
                                ["error"=>["message"=>"Contribution stairway step failed", "legend"=>"Не удалось отправить работу на модерацию", "idContribution"=>$contribution->id]], ["Content-Type"=>"application/json"]);
                            return;
                        }
                    }else {
                        $logger->addError("Contribution status change failed", ["idContribution"=>$contribution->id]);
                        $responder(["error"=>["message"=>"Contribution register failed", "legend"=>"Не удалось зарегистрировать работу", "idContribution"=>$contribution->id]], ["Content-Type"=>"application/json"]);
                        return;
                    }
                }
            }else {
                $logger->addError("Participant status change failed", ["idParticipant"=>$participant->id]);
                $responder(["error"=>["message"=>"Participant register failed", "legend"=>"Не удалось зарегистрировать участника", "idParticipant"=>$participant->id]], ["Content-Type"=>"application/json"]);
                return;
            }
            $jobData["queueNum"][$participant->id] = $stairway->id;
        }

        $jobQueue($jobData, Job::MAIL_DECLARANT_REGISTRATION,Status::NEW_ONE);
        $responder($result, ["Content-Type"=>"application/json"]);

    }
);