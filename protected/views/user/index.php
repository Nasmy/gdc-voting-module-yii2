<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->pageTitle = Yii::t('app', 'Users');
$this->pageTitleDescription = Yii::t('app', 'Listing all users');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'List');
?>
<div class="user-index">
    <?php if (Yii::$app->user->can('User.Create')): ?>
        <p><?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-info']) ?></p>
    <?php endif ?>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('_grid', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>
</div>
