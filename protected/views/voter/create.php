<?php
/* @var $this yii\web\View */
/* @var $model app\models\Voter */

$this->pageTitle = Yii::t('app', 'Create Voter');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Voters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>
<div class="voter-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
