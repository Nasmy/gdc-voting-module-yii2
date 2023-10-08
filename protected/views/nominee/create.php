<?php
/* @var $this yii\web\View */
/* @var $model app\models\Nominee */

$this->pageTitle = Yii::t('app', 'Create Nominee');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nominees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="nominee-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
