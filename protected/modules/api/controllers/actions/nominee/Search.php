<?php

namespace app\modules\api\controllers\actions\nominee;

use Yii;
use yii\base\Action;
use app\models\NomineeSearch;
use app\models\CategoryNominee;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;

class Search extends Action
{
    public function run()
    {
        $search = new NomineeSearch();
        $search->scenario = NomineeSearch::SCENARIO_API_SEARCH;
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
                    $categories = CategoryNominee::find()->select('categoryId')->where('nomineeId = :nomineeId', [':nomineeId' => $model->id])->all();
                    $categoryIds = [];
                    foreach ($categories as $category) {
                        $categoryIds[] = $category->categoryId;
                    }
                    $list[] = Message::nominee($model, $categoryIds);
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
        $response = Message::detailResponse($commonStatus, 'nominees', $data);

        $this->controller->sendResponse($response);
    }

}