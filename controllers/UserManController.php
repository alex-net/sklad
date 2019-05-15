<?php 

namespace app\controllers;

use app\models\SiteUser;
use Yii;

class UserManController extends \yii\web\Controller
{

	public function behaviors()
	{
		return [
			[
				'class'=>\yii\filters\AccessControl::className(),
				'rules'=>[
					['allow'=>true,'roles'=>['users-manage']],
				],
			]
		];
	}

	/**
	 * список юзерей 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		
		$dp=new \yii\data\SqlDataProvider([
			'sql'=>'select uid,mail,status,role,created,lastenter from {{users}}',
			'totalCount'=>Yii::$app->db->createCommand('select count(*) from {{users}}')->queryScalar(),
			'pagination'=>[
				'pageSize'=>20,
			],
		]);
		return $this->render('index',['dp'=>$dp]);
	}

	/**
	 * форма редактирования и создания . 
	 * @param  integer $uid Номер юзера .. 0 если новый
	 * @return [type]       [description]
	 */
	public function actionEdit(int $uid=0)
	{
		$u=$uid?SiteUser::findIdentity($uid):new SiteUser();
		if (!$u)
			return $this->redirect(['index']);
		$u->scenario=SiteUser::SCENARIO_EDIT_USER;
		$oldmail=$u->mail;
		
		if (Yii::$app->request->isPost  ){
			$post=Yii::$app->request->post();
			
			if (isset($post['kill']) && $u->uid &&  $u->kill()){
				Yii::$app->session->addFlash('success','Пользователь удалён');
				return $this->redirect(['index']);
			}
			if ($u->load($post)){
				//if (Yii:$app->request->post('save'))
				if ($u->uid && ($u->mail!=$oldmail||$u->pass )) // юзер уже был и он указан новый пароль или почту 
					$res=$u->setPassMail();
				else
					$res=$u->save();

				if ($res){
					Yii::$app->session->addFlash('success','Данные сохранены');
					return $this->redirect(['index']);
				}
			}
		}
		$u->pass='';
		return $this->render('edit',['u'=>$u]);
	}
}