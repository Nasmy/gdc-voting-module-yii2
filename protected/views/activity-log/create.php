<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ActivityLog */

$this->title = Yii::t('app', 'Create Activity Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Activity Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
