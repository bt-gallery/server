<?php
$responder = function ($content, $headers = []) use ($app) {
    foreach ($headers as $key => $value) {
        $app->response->setHeader($key, $value);
    }
    $app->response->setStatusCode(200, "Ok");
    $app->response->setContentType('application/json', 'UTF-8');
    $app->response->setJsonContent($content);
    $app->response->send();

    return true;
};

$servant = function ($serviceName) use ($app) {
    return $app->di->getService($serviceName)->getDefinition();
};

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

$app->post(
    '/api/v1/{modelName:declarant|address|participant}',
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

        $queue($model->toArray(),Job::MAIL_DECLARANT_REGISTRATION);
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->post(
    '/api/v1/competitive/bind',
    function () use ($app, $responder, $logger) {
        $model = new ParticipantHasCompetitiveWork;
        $data = $app->request->getPost();
        $mapper = $app->di->getService("mapper")->getDefinition();
        $saver = $app->di->getService("saver")->getDefinition();
        $queue = $app->di->getService("queue")->getDefinition();
        $result = $saver(
            $mapper($model,$data)
        );
        $participant = Participant::findFirst($model->idParticipant);
        $work = CompetitiveWork::findFirst($model->idCompetitiveWork);
        $queue(
            array_merge(
                $participant->toArray(),
                $work->toArray()
            ),
            Job::MAIL_DECLARANT_REGISTRATION
        );
        $responder($result, ["Content-Type"=>"application/json"]);
    }
);

$app->post(
    '/api/v1/competitive/upload',
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

/**
 * Not found handler
 */
$app->notFound(
    function () use ($app, $logger) {
        $logger->addWarning("404 sent", $app->request->getHeaders());
        $app->response->setStatusCode(404, "Not Found")->sendHeaders();
        echo $app['view']->render('404');
    }
);
