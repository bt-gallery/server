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
    function () use ($app, $responder, $servant) {
        $model = new Declarant;
        $data = $app->request->getPost();
        if(isset($data['id']))unset($data['id']);
        if(isset($data['time']))unset($data['time']);
        $mapper = $servant("mapper");
        $saver = $servant("saver");
        $result = $saver(
            $mapper($model,$data)
        );

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->post(
    '/api/v1/participant/add',
    function () use ($app, $responder, $servant) {
        $model = new Participant;
        $data = $app->request->getPost();
        if(isset($data['id']))unset($data['id']);
        if(isset($data['time']))unset($data['time']);
        $mapper = $servant("mapper");
        $saver = $servant("saver");
        $result = $saver(
            $mapper($model,$data)
        );

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->post(
    '/api/v1/contribution/add',
    function () use ($app, $responder, $servant, $logger) {
        if ($app->request->hasFiles()) {
            $saver = $app->di->getService("saver")->getDefinition();
            foreach ($app->request->getUploadedFiles() as $key => $file) {
                $model = new Contribution;
                $saver($model);

                $fileExtension = pathinfo($file->getName(), PATHINFO_EXTENSION);
                $fileName      = floor(microtime(true)) . "_{$key}.{$fileExtension}";
                $fileTmpPath   = $file->getTempName();
                $fileDirectory = $config->application->uploadDir . "files/works/{$model->id}/";
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
                    $model->name = $app->request->getPost("name");
                    $model->description = $app->request->getPost("description");
                    $model->storePath = $fileFullPath;
                    $model->webPath = "/files/works/{$model->id}/{$fileName}";
                    $model->fileName = $fileName;
                    $model->moderation = $app->request->getPost("moderation");
                    $model->rejection = $app->request->getPost("rejection");
                    $model->category = $app->request->getPost("category");
                    $model->priority = $app->request->getPost("priority");
                    $model->type = $fileExtension;
                    $model->fileSize = $fileTmpSize;
                    $modelResult = $saver($model);
                    $result[] = $modelResult;
                    $logger->addInfo("file: {$fileFullPath} saved");
                } else {
                    $logger->addError("file: {$fileFullPath} saving failed");
                }
            }
            $responder($result, ["Content-Type"=>"application/json"]);
        }
    }
);

$app->post(
    '/api/v1/vote/add',
    function () use ($app, $responder, $servant) {
        $model = new Vote;
        $data = $app->request->getPost();
        if(isset($data['id']))unset($data['id']);
        if(isset($data['time']))unset($data['time']);
        $mapper = $servant("mapper");
        $saver = $servant("saver");
        $result = $saver(
            $mapper($model,$data)
        );

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->post(
    '/api/v1/stairway/add',
    function () use ($app, $responder, $servant) {
        $model = new StairwayToModeration;
        $data = $app->request->getPost();
        if(isset($data['id']))unset($data['id']);
        $mapper = $servant("mapper");
        $saver = $servant("saver");
        $result = $saver(
            $mapper($model,$data)
        );

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->post(
    '/api/v1/moderation/add',
    function () use ($app, $responder, $servant) {
        $model = new ModerationStatus;
        $data = $app->request->getPost();
        if(Category::findFirst($data['id']) && isset($data['id'])){
            $result = ["error"=>["message"=>"id already exists", "legend"=>"Запись с таким идентефикатором уже существует"]];
        }else{
            if(isset($data['time']))unset($data['time']);
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $result = $saver(
                $mapper($model,$data)
            );
        }

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->post(
    '/api/v1/rejection/add',
    function () use ($app, $responder, $servant) {
        $model = new Rejection;
        $data = $app->request->getPost();
        if(Category::findFirst($data['id']) && isset($data['id'])){
            $result = ["error"=>["message"=>"id already exists", "legend"=>"Запись с таким идентефикатором уже существует"]];
        }else{
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $result = $saver(
                $mapper($model,$data)
            );
        }

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->post(
    '/api/v1/category/add',
    function () use ($app, $responder, $servant) {
        $model = new Category;
        $data = $app->request->getPost();
        if(Category::findFirst($data['id']) && isset($data['id'])){
            $result = ["error"=>["message"=>"id already exists", "legend"=>"Запись с таким идентефикатором уже существует"]];
        }else{
            $mapper = $servant("mapper");
            $saver = $servant("saver");
            $result = $saver(
                $mapper($model,$data)
            );
        }

        $responder($result, ["Content-Type"=>"application/json"]);
    }
);