<?php

use yii\db\Migration;
use app\models\SiteUser;
/**
 * Class m190509_123645_users
 */
class m190509_123645_users extends Migration
{

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createtable('users',[
            'uid'=>$this->primaryKey()->comment('Ключик'),
            'mail'=>$this->string(100)->notNull()->comment('Почта'),
            'pass'=>$this->string(60)->notNull()->comment('Пароль'),
            'authkey'=>$this->string(100)->notnull()->comment('Ключ автовхода'),
            'role'=>$this->string(10)->notnull()->comment('Роль'),
            'status'=>$this->boolean()->notnull()->defaultValue(false)->comment('Доступность'),
            'created'=>$this->dateTime()->defaultExpression('now()')->notNull()->comment('Дата создания'),
            'lastenter'=>$this->dateTime()->comment('Дата последнего входа в систему'),
            'f'=>$this->string(100)->comment('Фамилия'),
            'i'=>$this->string(100)->comment('Имя'),
            'o'=>$this->string(100)->comment('Отчество'),
            'tel'=>$this->string(15)->comment('Телефон'),
        ]);
        $this->createIndex('imail','users',['mail'],true);
        $this->createIndex('iauthkey','users',['authkey']);
        $this->createIndex('irole','users',['role']);

        $u=new SiteUser([
            'mail'=>'admin@mail.ru',
            'role'=>'admin',
            'pass'=>'12345',
            'status'=>true,
        ]);
        if ($u->save()){
            $am=yii::$app->authManager;
            $adminrole=$am->getRole('admin');
            if ($adminrole)
                $am->assign($adminrole,$u->id);
        }

        
        //SiteUser::create('admin@mail.ru','admin','12345');
        


    }

    public function down()
    {
        $am=yii::$app->authManager;
        $am->removeAllAssignments();
        $this->dropTable('users');
    }
}
