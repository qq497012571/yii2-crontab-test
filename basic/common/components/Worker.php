<?php
namespace app\common\components;

use Yii;
use yii\base\BaseObject;
use app\common\components\Task;
use Symfony\Component\Process\Process;

class Worker extends BaseObject
{
	public $pid = null;
	public $name = null;
	public $task = null;
	public $process = null;
	public $startTime = null;


	public static $root = '@app';

	public static $tasks = [];

	public static $workers = [];

	/**
	 * 安装并初始化任务
	 * @param $tasksConfig
	 */
	public static function install($tasksConfig)
	{
		foreach ($tasksConfig as $task) {

			$taskObj = Yii::createObject([
				'class' => 'app\common\components\Task',
				'name' => $task[0],
				'crontabStr' => $task[1],
				'route' => $task[2],
			]);

			$taskObj->init();

			self::$tasks[] = $taskObj;
		}
	}

	/**
	 * 监听任务状态 
	 */
	public static function listen()
	{
		return self::$tasks;

		$runTasks = [];

		foreach (self::$tasks as $task) {
			if ($task->validate()) {
				$runTasks[] = $task;
			}
		}

		return $runTasks;
	}

	/**
	 * 启动多进程
	 */
	public static function multiFork($taskObjs) {
		foreach ($taskObjs as $taskObj) {

			$process = new Process('php ' . Yii::getAlias(self::$root) . '/yii ' . $taskObj->route);

			$worker = Yii::createObject([
				'class' => 'app\common\components\Worker',
				'process' => $process,
				'task' => $taskObj,
				'name' => $taskObj->name,
				'startTime' => date('Y-m-d H:i:s'),
			]);

			self::$workers[] = &$worker;

			$process->start();
		}

		// linux
		if (function_exists('pcntl_signal')) {
			pcntl_signal(SIGALRM, ['Worker','signalHandler']);
		}
		else { // windows
			while (1) {
				if (empty(self::$workers)) {
					self::$workers = [];
					break;
				}
				self::free();
			}
		}
	}

	public static function signalHandler() {
		self::free();
		pcntl_alarm(1);
	}

	/**
	 * 释放进程
	 * @param  boolean $lock 是否阻塞等待
	 */
	public static function free($lock = false)
	{
		foreach (self::$workers as $worker) {
			if ($worker->process->isRunning()) {
				$index = array_search($worker, self::$workers, true);
				echo "free\n";
				unset(self::$workers[$index]);
			}
		}
	}

}