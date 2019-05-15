<?php 

use yii\helpers\Html;

$this->title='Управление пользователями';

$this->params['breadcrumbs']=['Управление пользователями'];

echo \yii\grid\GridView::widget([
	'dataProvider'=>$dp,
	'columns'=>[
		['attribute'=>'uid','label'=>'№'],
		['attribute'=>'mail','label'=>'Почта','content'=>function($m,$k){
			return Html::a($m['mail'],['user-man/edit','uid'=>$m['uid']]);
		}],
		['attribute'=>'status','label'=>'Активен'],
		['attribute'=>'role','label'=>'Роль','content'=>function($m){return Yii::$app->params['roles'][$m['role']];}],
		['attribute'=>'created','label'=>'Создан'],
		['attribute'=>'lastenter','label'=>'Последний вход'],
	],
]);