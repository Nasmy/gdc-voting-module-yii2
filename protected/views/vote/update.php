<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Vote */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Vote',
        ]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Votes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="vote-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
