<?php 


$this->title=$u->uid?'Редактировангие пользователя':'Новый пользователь';

$this->params['breadcrumbs']=[
	['label'=>'Пользователи','url'=>['user-man/index']],
	$u->uid?'Редактировангие пользователя '.$u->mail:'Новый пользователь'
];

$f=\yii\widgets\ActiveForm::begin();

echo $f->field($u,'mail');
echo $f->field($u,'pass')->passwordInput();
echo $f->field($u,'status')->checkBox();
echo $f->field($u,'role')->dropDownList(Yii::$app->params['roles']);
foreach(['i','f','o','tel'] as $k)
	echo $f->field($u,$k);


echo \yii\helpers\Html::submitButton('Поехали',['class'=>'btn btn-primary','name'=>'save']);
if ($u->uid && $u->uid!=Yii::$app->user->id)
	echo \yii\helpers\Html::submitButton('Удалить',['class'=>'btn btn-danger','name'=>'kill']);
\yii\widgets\ActiveForm::end();