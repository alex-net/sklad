<?php 

namespace app\commands;

use Yii;
use yii\helpers\Console;
use app\models\SiteUser;

class RbacController extends \yii\console\Controller
{
	/**
	 * инициализация прав 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$am=Yii::$app->authManager;
		$am->removeAll();

		// добавляем роли 
		$roles=Yii::$app->params['roles'];
		foreach($roles as $x=>$y){
			$role=$am->createRole($x);
			$role->description=$y;
			$am->add($role);
		}
		Console::output(Console::ansiFormat(sprintf('Добавлено %d ролей ',count($am->roles)),[Console::FG_GREEN]));

		$perm=$am->createPermission('users-manage');
		$perm->description='Управление юзерями. Удаление,добавление, редактирование любого юзера';
		$am->add($perm);
		$roleadmin=$am->getRole('admin');
		$am->addChild($roleadmin,$perm);
		Console::output(Console::ansiFormat(sprintf('Добавлено %d разрешений ',count($am->permissions)),[Console::FG_GREEN]));

		$this->actionBinder();
	}

	/**
	 * Связывание прав 
	 * @return [type] [description]
	 */
	public function actionBinder()
	{
		$am=Yii::$app->authManager;
		// зачистить все связки  
		$am->removeAllAssignments();

		// запрашиваем юзерей ... 
		$users=SiteUser::getUserRoles();
		foreach($users as $role=>$uids){
			$role=$am->getRole($role);
			foreach($uids as $uid)
				$am->assign($role,$uid);
			Console::output(Console::ansiFormat(sprintf('Роль %s, обработано пользователей %d',$role->description,count($uids)),[Console::FG_GREEN] ));
		}

		//print_r($users);
		//Yii::info('dsadad');
	}
}