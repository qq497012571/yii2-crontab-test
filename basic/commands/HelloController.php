<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        file_put_contents('tt.php', getmypid() . ':' . date('Y-m-d H:i:s') .  "\n", FILE_APPEND);
        for ($i=0,$c = rand(100000, 1000000); $i < $c; $i++) { 
        }
        echo "index\n";
        sleep(rand(1,5));
    }

    public function actionTest()
    {
        file_put_contents('tt.php', getmypid() . ':' . date('Y-m-d H:i:s') .  "\n", FILE_APPEND);
        for ($i=0,$c = rand(100000, 1000000); $i < $c; $i++) { 
        }
        echo "test\n";
        sleep(rand(1,5));
    }

    public function actionWorld()
    {
        file_put_contents('tt.php', getmypid() . ':' . date('Y-m-d H:i:s') .  "\n", FILE_APPEND);
        for ($i=0,$c = rand(100000, 1000000); $i < $c; $i++) { 
        }
        echo "world\n";
        sleep(rand(1,5));
    }


}
