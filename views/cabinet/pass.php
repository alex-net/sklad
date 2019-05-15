<?php 

$this->title='Смена почты и пароля';

$this->params['breadcrumbs']=[
	['label'=>'Кабинет','url'=>['cabinet/index']],
	'Смена почты и пароля',
];

$f=\yii\widgets\ActiveForm::begin();
echo $f->field($form,'mail');
foreach(['passold','passnew','passconfirm'] as $k)
	echo $f->field($form,$k)->passwordInput();
echo \yii\helpers\Html::submitButton('Поехали!',['class'=>'btn btn-primary']);
\yii\widgets\ActiveForm::end();