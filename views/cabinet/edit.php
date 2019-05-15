<?php 
$this->title='Редактирование учётки';

$this->params['breadcrumbs']=[
	['label'=>'Кабинет','url'=>['cabinet/index']],
	'Редактировать учётку',
];

$f=\yii\widgets\ActiveForm::begin();
foreach(['f','i','o','tel'] as $el)
	echo $f->field($form,$el);
echo \yii\helpers\Html::submitButton('Сохранить',['class'=>'btn btn-primary']);
\yii\widgets\ActiveForm::end();