<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label'=>'Пользователи','visible'=>Yii::$app->user->can('users-manage'),'items'=>[
                ['label'=>'Список','url'=>['user-man/index']],
                ['label'=>'Новый пользователь','url'=>['user-man/edit']],
            ]],
            ['label' => 'Кабинет' ,'visible'=>!Yii::$app->user->isGuest,'items'=>[
                ['label'=>'Кабинет','url'=>['cabinet/index'],'visible'=>!Yii::$app->user->isGuest],
                ['label'=>'Редактировать учётку','url'=>['cabinet/edit'],'visible'=>!Yii::$app->user->isGuest],
                ['label'=>'Сменить пароль','url'=>['cabinet/pass'],'visible'=>!Yii::$app->user->isGuest],
                ['label'=>'Выйти','url'=>['cabinet/logout'],'visible'=>!Yii::$app->user->isGuest],
            ]],
            ['label' => 'Вход', 'url' => ['site/login'],'visible'=>Yii::$app->user->isGuest],



            
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
