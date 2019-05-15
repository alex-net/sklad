<?php 
$this->title="Вход в кабинет";
	
$f=\yii\widgets\ActiveForm::begin();
echo $f->field($lf,'mail');
echo $f->field($lf,'pass')->passwordInput();
echo \yii\helpers\Html::submitButton('Поехали!',['class'=>'btn btn-success']);
\yii\widgets\ActiveForm::end();