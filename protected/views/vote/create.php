<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Vote */

$this->title = Yii::t('app', 'Create Vote');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Votes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vote-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
