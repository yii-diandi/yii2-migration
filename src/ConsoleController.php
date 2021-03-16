<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-06-23 16:45:55
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2021-03-16 11:40:39
 */
 

namespace diandi\migration;

use Yii;
use yii\console\Exception;
use yii\helpers\Console;
use yii\helpers\ArrayHelper;
use yii\console\controllers\MigrateController;

//use jianyan\migration\components\MigrateCreate;

/**
 * This is just an example.
 */
class ConsoleController extends MigrateController
{
    /**
     * Creates a new migration. php yii migrate/backup all
     * 
     * @param string $name 
     * @throws Exception if the name argument is invalid.
     */
    public function actionBackup($name){
        /* 所有数据表 */
        $alltables  = Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll();
        $alltables  = array_map('array_change_key_case', $alltables);
        $alltables  = ArrayHelper::getColumn($alltables, 'name');

        $name = trim($name,',');
        if ($name == 'all') {
            /* 备份所有数据 */
            $tables  = $alltables;
        } else if(strpos($name, ',')){
            /* 备份部分数据表 */
            $tables = explode(',', $name);
        } else {
            /* 备份一个数据表 */
            $tables = [$name];
        }
        /* 检查表是否存在 */
        foreach ($tables as $table) {
            if (!in_array($table,$alltables)) {
                $this->stdout($table." table no find ...\n", Console::FG_RED);
                die();
            }
        }
        /* 创建migration */
        foreach ($tables as $table) {
            //$migrate = new MigrateCreate();
            $migrate = Yii::createObject([
                'class' => 'diandi\migration\components\MigrateCreate',
                'migrationPath' => '@app/migrations'
            ]);
            $migrate->create($table);
            unset($migrate);
        }

        $this->stdout("backup success.\n", Console::FG_GREEN);
    }

    /**
     * Creates a new migration. php yii migrate/backup all
     * 
     * @param string $name 
     * @throws Exception if the name argument is invalid.
     */
    public function actionAddonsBackup($addonsName){
        /* 所有数据表 */
        $alltables  = Yii::$app->db->createCommand("SHOW TABLE STATUS like '%{$addonsName}%'")->queryAll();
        $alltables  = array_map('array_change_key_case', $alltables);
        $alltables  = ArrayHelper::getColumn($alltables, 'name');

          /* 备份所有数据 */
        $tables  = $alltables;
              
        $name = trim($addonsName,',');
        // if ($name == 'all') {
        //     /* 备份所有数据 */
        //     $tables  = $alltables;
        // } else if(strpos($name, ',')){
        //     /* 备份部分数据表 */
        //     $tables = explode(',', $name);
        // } else {
        //     /* 备份一个数据表 */
        //     $tables = [$name];
        // }
        /* 检查表是否存在 */
        foreach ($tables as $table) {
            if (!in_array($table,$alltables)) {
                $this->stdout($table." table no find ...\n", Console::FG_RED);
                die();
            }
        }
		
        /* 创建migration */
        foreach ($tables as $table) {
            //$migrate = new MigrateCreate();
            $migrate = Yii::createObject([
                'class' => 'diandi\migration\components\MigrateCreate',
                'migrationPath' => "@addons/{$addonsName}/migrations/"
            ]);
            $migrate->create($table);
            unset($migrate);
        }

        $this->stdout("backup success.\n", Console::FG_GREEN);
    }
}
