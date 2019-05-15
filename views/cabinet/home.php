<?php 

$this->title='Кабинет';
$this->params['breadcrumbs']=['Кабинет'];

echo \yii\widgets\DetailView::widget([
	'model'=>Yii::$app->user->identity,
	'attributes'=>[
		'f','i','o','mail','tel','lastenter','created'
	],
]);