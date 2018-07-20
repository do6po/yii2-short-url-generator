<?php
/**
 * Created by PhpStorm.
 * User: box
 * Date: 20.07.18
 * Time: 13:44
 */

namespace app\commands;

use app\models\Url;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

class UrlController extends Controller
{
    public function actionDeleteAllExpired()
    {
        $this->stdout(sprintf("=> Start at %s\n", date("d.m.Y H:i:s")), Console::FG_YELLOW);
        $this->stdout('Processing...' . PHP_EOL, Console::BOLD);

        $numberOfDeletedRecord = Url::deleteAllExpired();

        $this->stdout(sprintf('%s rows deleted!', $numberOfDeletedRecord) . PHP_EOL, Console::BOLD);

        $this->stdout(sprintf("=> Finish at %s\n", date("d.m.Y H:i:s")), Console::FG_YELLOW);
        $this->stdout(PHP_EOL, Console::RESET);

        return ExitCode::OK;
    }
}