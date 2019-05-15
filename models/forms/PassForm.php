<?php 

namespace app\models\forms;

use app\models\SiteUser;
use Yii;

class PassForm extends \yii\base\Model
{

	public $uid;// номер учётки 
	public $passold; // пароль 
	public $passnew; // пароль 
	public $passconfirm; // пароль 
	public $mail;// почта


	public function attributeLabels()
	{
		return [
			'mail'=>'Почта',
			'passold'=>'Старый пароль',
			'passnew'=>'Новый пароль',
			'passconfirm'=>'Новый пароль ещё раз',
		];
	}

	public function rules()
	{
		return [
			['mail','email'],
			['mail','checkExistsMail'],
			[['mail','passold'],'required'],//,'passnew','passconfirm'
			['passold','checkPass'],
			['passconfirm','compare','compareAttribute'=>'passnew','operator'=>'=='],
		];
	}
	/**
	 * проверка пароля
	 * @return [type] [description]
	 */
	public function checkPass($attr)
	{
		$u=SiteUser::findIdentity($this->uid);
		
		if (!$u || !Yii::$app->security->validatePassword($this->passold, $u->pass))
			$this->addError($attr,'Не верный пароль');
	}
	/**
	 * проверка почты на существование 
	 */
	public function checkExistsMail($attr)
	{
		$res=Yii::$app->db->createCommand('select count(*) from {{users}} where uid!=:uid and mail=:email');
		$res->bindValues([
			':uid'=>$this->uid,
			':email'=>$this->mail,
		]);
		$res=$res->queryScalar();
		if ($res>0)
			$this->addError($attr,'Ящик занят');
	}

	/**
	 * засунуть новые данные мыло и пароль
	 */
	public function setNewData():bool
	{
		if ($this->validate()){
			$u=SiteUser::findIdentity($this->uid);
			$u->mail=$this->mail;
			$u->authkey=Yii::$app->security->generateRandomString(rand(70,100));
			if ($this->passnew)// если указали новый пароль надо его захешить 
				$u->pass=Yii::$app->security->generatePasswordHash($this->passnew);
			return $u->save();
		}
		return false;

	}
}