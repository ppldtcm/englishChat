<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\httpclient\Client;

class ApiController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Настраиваем CORS
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Expose-Headers' => ['*'],
                'Access-Control-Allow-Headers' => ['*'],
            ],
        ];

        return $behaviors;
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionCreateMes()
    {
        Yii::$app->request->parsers = [
            'application/json' => 'yii\web\JsonParser',
        ];
        $message = Yii::$app->request->post('message', '');
        
        $folderId = 'b1g85c6dndf00c2agjlu';
        $iamToken = 't1.9euelZqRnMaOlJOVzJ6TjIuUy4yQie3rnpWaxsmKls2azJWYjorMxpuQkp3l8_dfbC1G-e9bNxpq_d3z9x8bK0b571s3Gmr9zef1656VmpmUnpaMjZaTmJKQjs2eypWU7_zF656VmpmUnpaMjZaTmJKQjs2eypWU.Tc51ki1IeliP9jG1zJv8h0pvMLr164d02fpShpVXb7EVHb6hii5vgcMbkerKPgFw63Q3yZJII6hYbT6PtyghBA';  // Укажите ваш IAM_TOKEN
        $url = 'https://llm.api.cloud.yandex.net/foundationModels/v1/completion';

        // if(empty($message)){ $message = "превет"; }
        $data = [
            "modelUri" => "gpt://b1g85c6dndf00c2agjlu/yandexgpt/rc",
            "completionOptions" => ["maxTokens" => 500, "temperature" => 0.3],
            "messages" => [
                ["role" => "system", "text" => "Найди ошибки в сообщении, исправь их и задай вопрос по этому тексту"],
                ["role" => "user", "text" => $message]
            ]
        ];
        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $iamToken,
                'x-folder-id' => $folderId,
            ])
            ->setContent(json_encode($data))
            ->send();

        // Проверяем ответ и обрабатываем возможные ошибки
        if ($response->isOk) {
            $decodedResponse = $response->data;
            $botMessage = $decodedResponse['result']['alternatives'][0]['message']['text'] ?? 'Ошибка обработки ответа';        } else {
            $botMessage = 'Ошибка запроса: ' . $response->statusCode;
            // return ['error' => 'Ошибка запроса: ' . $response->statusCode];
        }

        return ['response' => $botMessage];
        // // Инициализируем cURL запрос к YandexGPT API
        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     'Content-Type: application/json',
        //     'Authorization: Bearer ' . $iamToken,
        //     'x-folder-id: ' . $folderId,
        // ]);

        // // Выполняем запрос
        // $response = curl_exec($ch);

        // // Обработка ошибок cURL
        // if (curl_errno($ch)) {
        //     return ['error' => curl_error($ch)];
        // }

        // curl_close($ch);

        // $decodedResponse = json_decode($response, true);
        // $botMessage = $decodedResponse['result']['alternatives']['message']['text'] ?? 'Ошибка обработки ответа111';
        // echo "<h2>$decodedResponse</h2>";        



        // return ['response' => $botMessage];
    }
}
