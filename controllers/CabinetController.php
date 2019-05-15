<?php 

namespace app\controllers;

use Yii;
use app\models\SiteUser;
use app\models\forms\PassForm;

class CabinetController extends \yii\web\Controller
{

	public function behaviors()
	{
		return [
			'logout'=>[
				'class'=>\yii\filters\AccessControl::className(),
				'rules'=>[
					['allow'=>true,'roles'=>['@'],]
				],
			],
		];
	}
	/**
	 * просмотр кабинета 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		return $this->render('home');
	}

	///выход ..
	public function actionLogout()
	{
		Yii::$app->user->logout();
		return $this->goHome();
	}

	/**
	 * редактировать учётку .. 
	 */
	public function actionEdit()
	{
		//Yii::info(Yii::$app->user->identity->scenarios(),'sc');
		$editform=Yii::$app->user->identity;
		$editform->scenario=SiteUser::SCENARIO_USER_SELF;
		if (Yii::$app->request->isPost && $editform->load(Yii::$app->request->post()) && $editform->save() ){
			Yii::$app->session->addFlash('success','Данные сохранены');
			return $this->redirect(['index']);
		}

		//Yii::info($editform->attributes,'attrs');
		return $this->render('edit',['form'=>$editform]);
	}
	/**
	 * Смена пароля .. 
	 */
	public function actionPass()
	{

		$pass=new \app\models\forms\PassForm([
			'uid'=>Yii::$app->user->identity->uid,
			'mail'=>Yii::$app->user->identity->mail,
		]);
		if (Yii::$app->request->isPost && $pass->load(Yii::$app->request->post()) && $pass->setNewData()){
			Yii::$app->session->addFlash('success','Данные обновлены');
			return $this->redirect(['index']);
		}
		return $this->render('pass',['form'=>$pass]);
	}
}