<?php

namespace app\controllers;

use \app\models\forms\LoginForm;
use Yii;

class SiteController extends \yii\web\Controller
{


	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest)
			return $this->redirect(['cabinet/index']);

		$lf=new LoginForm();
		if (Yii::$app->request->isPost && $lf->load(Yii::$app->request->post()) && $lf->validate()){
			Yii::$app->session->addFlash('success','Успешный вход');
			$lf->login();
			return $this->redirect(['cabinet/index']);
		}
		return $this->render('login',['lf'=>$lf]);
	}
	



 
}
