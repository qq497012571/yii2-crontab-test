<?php
namespace app\common\components;

use yii\base\BaseObject;
use app\common\helpers\CronParser;

class Task extends BaseObject
{
	public $name = '';
	public $crontabStr = '';
	public $lastRuntime = '';
	public $nextRuntime = '';
	public $route = '';
	public $count = 0;
	private $dateQueue = [];

	public function validate()
	{
		if (empty($this->dateQueue)) {
			$this->init();
		}

		if ($this->nextRuntime < date('Y-m-d H:i:s')) {
			$this->count++;
			$this->lastRuntime = date('Y-m-d H:i:s');
			$this->deDateQueue();
			return true;
		}

		return false;
	}

	/**
	 * 初始化时间队列
	 */
	public function init()
	{
		$this->dateQueue = CronParser::formatToDate($this->crontabStr, 50);
		$this->deDateQueue();
	}

	public function deDateQueue()
	{
		$this->nextRuntime = array_shift($this->dateQueue);
	}

}