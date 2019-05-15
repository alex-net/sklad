<?php 

namespace app\commands;

use Yii;
use yii\helpers\Console;

class ImportController extends \yii\console\Controller
{
	/**
	 * импорт товаров
	 * @return [type] [description]
	 */
	public function actionTovars()
	{
		$res=Yii::$app->db2->createCommand('select count(*) from {{tovars}}')->queryScalar();
		echo Console::output(Console::ansiFormat(sprintf('Нужно импортировать %d товаров и разделов ',$res),[Console::FG_GREEN]));
	}
}