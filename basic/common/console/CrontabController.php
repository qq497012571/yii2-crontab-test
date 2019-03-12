<?php
namespace app\common\console;

use Yii;
use app\common\components\Worker;
use yii\console\Controller;
use Symfony\Component\Process\Process;

class CrontabController extends Controller
{
	public function actionListen()
	{
		Worker::install(Yii::$app->params['tasks']);
		while (1) {

			if (($tasks = Worker::listen()) !== false) {
				Worker::multiFork($tasks);
			}

			sleep(1);
		}
	}

	public function actionList()
	{
		
	}

	public function actionStatus()
	{
		
	}
}