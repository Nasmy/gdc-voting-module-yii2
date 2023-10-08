<?php

namespace app\modules\api\controllers\actions\category;

use Yii;
use yii\base\Action;
use app\models\CategorySearch;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;

class Search extends Action
{
    public function run()
    {
        $search = new CategorySearch();
        $search->scenario = CategorySearch::SCENARIO_API_SEARCH;
        $search->load(['CategorySearch' => Yii::$app->request->get()]);

        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;
        $data = [];

        if ($search->validateModel()) {

            $result = $search->apiSearch();

            $total = $result['total'];
            $models = $result['models'];

            $list = [];
            if (!empty($models)) {
                foreach ($models as $model) {
                    $list[] = Message::category($model);
                }
            }

            $statusCode = ApiStatusMessage::SUCCESS;
            $data = Message::searchResult($total, $list);

        } else {
            Yii::$app->appLog->writeLog('Validation failed.');

        }

        $statusCode = !empty($search->statusCode) ? $search->statusCode : $statusCode;
        $statusMsg = !empty($search->statusMessage) ? $search->statusMessage : $statusMsg;

        $commonStatus = Message::status($statusCode, $statusMsg);
        $response = Message::detailResponse($commonStatus, 'catergories', $data);

        $this->controller->sendResponse($response);
    }

}