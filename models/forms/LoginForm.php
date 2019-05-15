<?php 

namespace app\models\forms;

use \app\models\SiteUser;
use Yii;

class LoginForm extends \yii\base\Model
{
	public $mail;

	public $pass;

	private $user;

	public function rules()
	{
		return [
			[['mail','pass'],'required'],
			['mail','email'],
			[['mail'],'checkUser'],
		];
	}

	public function attributeLabels()
	{
		return [
			'mail'=>'Почта',	
			'pass'=>'Пароль',
		];
	}

	// проверка пользователя в базе 
	public function checkUser()
	{
		$this->user=null;
		$u=SiteUser::findIdentityByMaul($this->mail);
		// либо пользователя вообще нет .. либо он залочен .. либо пароль кривой.. 
		if (!$u || $u && (!$u->status || !Yii::$app->security->validatePassword($this->pass,$u->pass) ))
			$this->addErrors(['mail'=>'Логин или пароль не корректны','pass'=>'Логин или пароль не корректны']);
		else
			$this->user=$u;
	}

	/**
	 * логин 
	 */
	public function login()
	{
		if ($this->user)
			Yii::$app->user->login($this->user);
	}
}