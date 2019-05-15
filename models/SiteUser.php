<?php 

namespace app\models;

use Yii;

class SiteUser extends \yii\base\Model implements \yii\web\IdentityInterface
{
	public $uid;// номер учётки 
	public $mail; // логин 
	public $pass; // пароль 
	public $authkey;// ключ входа в систему ..

	public $status; // стутус юзера .. 
	public $created;// дата создания записи 
	public $lastenter;// дата последнего входа в систему...
	public $role;// роль 

	public $f;// фамилия
	public $i;// имя
	public $o;// отчество
	public $tel;// телефон


	// редактирование учётки пользователем , 
	const SCENARIO_USER_SELF = 'user-self';
	const SCENARIO_EDIT_USER = 'user-edit';
 


	public static function findIdentityByMaul(string $mail)
	{
		$res=Yii::$app->db->createCommand('select * from {{users}} where [[mail]]=:mail');
		$res->bindValue(':mail',$mail);
		$res=$res->queryOne();
		if (!$res)
			return null;
		return new static($res);
	}

	// просто поиск юзера по id 
	public static function findIdentity($uid)
	{
		$res=Yii::$app->db->createCommand('select * from {{users}} where [[uid]]=:uid');
		$res->bindValue(':uid',$uid);
		$res=$res->queryOne();
		if (!$res)
			return null;
		return new static($res);
	}
	// поиск пользователя по ключу .  rest api 
	public static function findIdentityByAccessToken($token,$type=null)
	{
		return null;
	}

	public function getAuthKey():string
	{
		return $this->$authkey;
	}

	public function getId()
	{
		return $this->uid;
	}

	public function validateAuthKey($ak)
	{
		return $ak==$this->authkey;
	}

	public function attributeLabels()
	{
		return [
			'mail'=>'Почта',	
			'pass'=>'Пароль',
			'f'=>'Фамилия',
			'i'=>'Имя',
			'o'=>'Отчество',
			'tel'=>'Телефон',
			'lastenter'=>'Дата последнего входа',
			'created'=>'Дата создания',
			'role'=>'Роль',
			'status'=>'Активен',
		];
	}

	public function attributeHints()
	{
		return ['tel'=>'формат 8xxxxxxxxxx'];
	}

	public function scenarios()
	{
		$sc=parent::scenarios();
		$sc[self::SCENARIO_USER_SELF]=['f','i','o','tel'];
		$sc[self::SCENARIO_EDIT_USER]=['mail','pass','role','status','f','i','o','tel'];
		return $sc;
	}

	public function rules()
	{
		return [
			[['mail','role'],'required'],
			['pass','required','when'=>function($m){return empty($m->uid);},'enableClientValidation'=>false],
			['mail','email'],
			['mail','checkInDb'],
			[['mail','o','f','i'],'string','max'=>100],
			['tel','string','max'=>15],
			['tel','match','pattern'=>'#8\d{10}#'],
			['status','boolean'],
			['role','in','range'=>array_keys(Yii::$app->params['roles'])],
		];
	}

	public function checkInDb($attr)
	{
		$q='select count(*) as co from {{users}} where [[mail]]=:mail ';
		$vals=[':mail'=>$this->mail];
		if ($this->uid){
			$q.=' and [[uid]]!=:uid';
			$vals[':uid']=$this->uid;
		}
		$res=Yii::$app->db->createCommand($q);

		$res->bindValues($vals);
		$res=$res->queryScalar();
		if ($res>0)// запись нашлась .. 
			$this->addError($attr,'Почта уже используется. укажите другую');
		//return $res;
	}


	/**
	 * сохранение данных пользователя 
	 * @return [type] [description]
	 */
	public function save():bool
	{
		if (!isset($this->uid)){//новый юзер .. 
			//if (empty($this->pass))
			//	$this->pass=yii::$app->security->generateRandomString(rand(8,16));
			if ($this->pass)// пароль задали .. .
				$this->pass=Yii::$app->security->generatePasswordHash($this->pass);
			// генерим ключ доступа .. 
			$this->authkey=Yii::$app->security->generateRandomString(rand(70,100));
		}
		
		if (!$this->validate())
			return false;

		$fields=$this->attributes;
		
		unset($fields['uid']);
		// зачищаем нули
		foreach($fields as $x=>$y)
			if (!isset($y))
				unset($fields[$x]);
		if (empty($fields['pass']))
			unset($fields['pass']);

		$am=Yii::$app->authManager;
		if (empty($this->uid)){// вставляем новую запись
			Yii::$app->db->createCommand()->insert('users',$fields)->execute();
			$this->uid=Yii::$app->db->lastInsertId;
		}
		else{
			Yii::$app->db->createCommand()->update('users',$fields,['uid'=>$this->uid])->execute();
			$am->revokeAll($this->uid);
		}
		//
		$role=$am->getRole($this->role);
		if ($role)
			$am->assign($role,$this->uid);
		return true;
	}

	/**
	 * удаление пользователя 
	 */

	public function kill()
	{
		$res=Yii::$app->db->createCommand('delete from {{users}} where uid=:uid');
		$res->bindValue(':uid',$this->uid);
		return $res->execute();


	}
	/**
	 * обновление учётки со сменой почты или пароля 
	 */
	public function setPassMail()
	{
		$this->authkey=Yii::$app->security->generateRandomString(rand(70,100));
		if ($this->pass)// если указали новый пароль надо его захешить 
			$this->pass=Yii::$app->security->generatePasswordHash($this->pass);
		return $this->save();
		
	}


	public function getUsername()
	{
		return ($this->i??'none').' '.($this->f??'none');
	}

	/**
	 * формируем массивы юзерей сгруппированные по ролям .
	 * @return [type] [description]
	 */
	public static function getUserRoles():array
	{
		$ret=[];
		$res=Yii::$app->db->createCommand('select [[uid]], [[role]] from {{users}}')->queryAll();
		foreach($res as $u)
			$ret[$u['role']][]=$u['uid'];
		
		return $ret;
	}

	/**
	 * обновить  поле последнего входа
	 */
	public function setLoggindata()
	{
		$res=Yii::$app->db->createCommand('update {{users}} set lastenter=now() where uid=:uid');
		$res->bindValue(':uid',$this->uid);
		$res->execute();
	}
}