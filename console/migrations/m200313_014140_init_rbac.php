<?php
/** @noinspection PhpClassNamingConventionInspection */

use yii\db\Migration;

/**
 * Class m200313_014140_init_rbac
 */
class m200313_014140_init_rbac extends Migration
{
    public function up()
    {
        /** @var \yii\rbac\DbManager $auth */
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $applePermisison = $auth->createPermission('applePermisison');
        $applePermisison->description = 'applePermisison';
        $auth->add($applePermisison);

        $appleRole = $auth->createRole('appleRole');
        $auth->add($appleRole);
        $auth->addChild($appleRole, $applePermisison);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }
}
