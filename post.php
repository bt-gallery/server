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
use Phalcon\Image\Adapter\Imagick;

/* POST routes */

$app->post(
    '/api/v1/declarant/add',
    function () use ($app, $responder, $servant) {
        $model = new Declarant;
        $data = $app->request->getPost();
        if(isset($data['idDeclarant']))unset($data['idDeclarant']);
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
        if(isset($data['idParticipant']))unset($data['idParticipant']);
        if(isset($data['time']))unset($data['time']);
        if (isset($data['idContribution']) && isset($data['photoInfo'])) {
            if (Contribution::findFirst($data['idContribution'])) {
            $contr = Contribution::findFirst($data['idContribution']);
            $contr->description = $data['photoInfo'];
            $contr->save();
            unset($data['photoInfo']);
            unset($data['idContribution']);
            } else {
                $result = ["error"=>["message"=>"Contribution id not found", "legend"=>"Работа с таким идентефикатором не найдена"]];
                $responder($result, ["Content-Type"=>"application/json"]);
                return;
            }
        }
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

                $fileExtension = strtolower(pathinfo($file->getName(), PATHINFO_EXTENSION));
                if (!($fileExtension=="jpe" or $fileExtension=="jpeg" or $fileExtension=="png" or $fileExtension=="jpg")) {
                    $result = ["error"=>["message"=>"Bad file format", "legend"=>"К сожалению, Ваше изображение не подходит. Пожалуйста, загрузите изображение допустимого формата: PNG или JPEG."]];
                    $responder($result, ["Content-Type"=>"application/json"]);
                    return;
                }
                $fileNameBase  = floor(microtime(true)) . "_{$key}";
                $fileName      = $fileNameBase . ".{$fileExtension}";
                $fileTmpPath   = $file->getTempName();
                $model = new Contribution;
                $model->create();
                $fileDirectory = $config->application->uploadDir . "files/works/{$model->idContribution}/";
                $fileFullPath  = $fileDirectory . $fileName;
                $fileTmpSize   = $file->getSize();

                $imagine = new \Imagine\Imagick\Imagine();
                $image = $imagine->open($fileTmpPath);
                $size = new \Imagine\Image\Box(600, 1000);
                $mode = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
                $thumbName = 'thumb_'. $fileNameBase . ".jpg";
                $thumbDirectory = $fileDirectory;
                $thumbFullPath = $thumbDirectory . $thumbName;
                $thumb = $image->thumbnail($size, $mode);

                mkdir($fileDirectory, 0755, true);
                $moveRes = move_uploaded_file($fileTmpPath, $fileFullPath);
                $thumb->save($thumbFullPath);

                $pathExists     = file_exists($fileDirectory) ? "yes" : "no";
                $isWritable     = is_writable($fileDirectory) ? "yes" : "no";

                $logger->addDebug("file: " . $fileName . " " . $file->getSize());
                $logger->addDebug("source: " . $fileTmpPath);
                $logger->addDebug("destination: " . $fileFullPath);
                $logger->addDebug("exists: " . $pathExists);
                $logger->addDebug("is writable: " . $isWritable);

                if ($moveRes) {
                    $model->idDeclarant = $app->request->getPost("idDeclarant");
                    $model->idParicipant = $app->request->getPost("idParicipant");
                    $model->name = $app->request->getPost("name");
                    $model->description = $app->request->getPost("description");
                    $model->persons = $app->request->getPost("persons");
                    $model->storePath = $fileFullPath;
                    $model->webPath = "/files/works/{$model->idContribution}/{$fileName}";
                    $model->fileName = $fileName;
                    $model->moderation = $app->request->getPost("moderation");
                    $model->rejection = $app->request->getPost("rejection");
                    $model->category = $app->request->getPost("category");
                    $model->priority = $app->request->getPost("priority");
                    $model->type = $fileExtension;
                    $model->fileSize = $fileTmpSize;
                    $model->thumbStorePath = $thumbFullPath;
                    $model->thumbWebPath = "/" . $thumbFullPath;
                    $modelResult = $saver($model);
                    $result[] = $modelResult;
                    $logger->addInfo("file: {$fileFullPath} saved");
                } else {
                    $logger->addError("file: {$fileFullPath} saving failed");
                }
            }
            $responder($result, ["Content-Type"=>"application/json"]);
        }else{
            $result = ["error"=>["message"=>"No images sent", "legend"=>"Нет изображений для обработки"]];
            $responder($result, ["Content-Type"=>"application/json"]);
        }
    }
);

$app->post(
    '/api/v1/vote/add',
    function () use ($app, $responder, $servant) {
        $model = new Vote;
        $data = $app->request->getPost();
        if(isset($data['idVote']))unset($data['idVote']);
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
        if(isset($data['idStairwayToModeration']))unset($data['idStairwayToModeration']);
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
        if(Category::findFirst($data['idModerationStatus']) && isset($data['idModerationStatus'])){
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
        if(Category::findFirst($data['idRejection']) && isset($data['idRejection'])){
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
        if(Category::findFirst($data['idCategory']) && isset($data['idCategory'])){
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