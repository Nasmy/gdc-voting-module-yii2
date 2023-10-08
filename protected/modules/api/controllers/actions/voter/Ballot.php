<?php

namespace app\modules\api\controllers\actions\voter;

use Yii;
use yii\base\Action;
use app\models\CategorySearch;
use app\models\NomineeSearch;
use app\models\CategoryNominee;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;

class Ballot extends Action
{
    public function run()
    {
        $params = json_decode(Yii::$app->request->rawBody, true);

        $statusCode = ApiStatusMessage::SUCCESS;
        $statusMsg = null;
        $categories = [];
        $nominees = [];

        // Categories
        $search = new CategorySearch();
        $search->scenario = CategorySearch::SCENARIO_API_SEARCH;

        $result = $search->apiSearch();
        $total = $result['total'];
        $models = $result['models'];

        if (!empty($models)) {
            foreach ($models as $model) {
                $categories[] = Message::category($model);
            }
        }

        // Nominees
        $search = new NomineeSearch();
        $search->scenario = NomineeSearch::SCENARIO_API_SEARCH;

        $result = $search->apiSearch();
        $total = $result['total'];
        $models = $result['models'];

        if (!empty($models)) {
            foreach ($models as $model) {
                $categoryNominees = CategoryNominee::find()->select('categoryId')->where('nomineeId = :nomineeId', [':nomineeId' => $model->id])->all();
                $categoryIds = [];
                foreach ($categoryNominees as $categoryNominee) {
                    $categoryIds[] = $categoryNominee->categoryId;
                }
                $nominees[] = Message::nominee($model, $categoryIds);
            }
        }

        $commonStatus = Message::status($statusCode, $statusMsg);
        $response = Message::detailResponse($commonStatus, ['categories', 'nominees'], [$categories, $nominees]);

        $this->controller->sendResponse($response);
    }
}
?>