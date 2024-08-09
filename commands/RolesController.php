<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use Yii;
use app\models\User;


class RolesController extends Controller 
{

    /**
     * php yii roles/create-role guest Guest
     * php yii roles/create-role user User
     */
    public function actionCreateRole($code, $name)
    {
        $role = Yii::$app->authManager->createRole($code);
        $role->description = $name;
        Yii::$app->authManager->add($role);
    }

    /**
     * php yii roles/add-permission lookBooks "Просмотр книг"
     * php yii roles/add-permission subscribeAuthor "Подписка на новые книги автора"
     * php yii roles/add-permission refactorData "Разрешение добавлять, редактировать, удалять книги авторов"
     */
    public function actionAddPermission($permit, $descr) 
    {
        $permit = Yii::$app->authManager->createPermission($permit);
        $permit->description = $descr;
        Yii::$app->authManager->add($permit);
    }

    /** todo
     * php yii roles/relate-role guest lookBooks
     * php yii roles/relate-role guest subscribeAuthor
     * php yii roles/relate-role user refactorData
     */
    public function actionRelateRole($roleName, $permitName) {
        $role = Yii::$app->authManager->getRole($roleName);
        $permit = Yii::$app->authManager->getPermission($permitName);
        Yii::$app->authManager->addChild($role, $permit);
    }

    /**
     * php yii roles/relate-permition refactorData lookBooks
     */
    public function actionRelatePermition($parentPermit, $childPermit) {
        $parent = Yii::$app->authManager->getPermission($parentPermit);
        $child = Yii::$app->authManager->getPermission($childPermit);
        Yii::$app->authManager->addChild($parent, $child);
    }


    /**
     * php yii roles/set-role user user
     * php yii roles/set-role guest guest
     */
    public function actionSetRole($role, $username) {
        $userRole = Yii::$app->authManager->getRole($role);
        $user = User::findByUsername($username);
        if ($user) {
            Yii::$app->authManager->assign($userRole, $user->getId());
        }
    }

    /**
     * php yii roles/set-permition lookBooks guest
     * php yii roles/set-permition subscribeAuthor guest
     * php yii roles/set-permition refactorData user
     */
    public function actionSetPermition($permit, $username) {
        $permit = Yii::$app->authManager->getPermission($permit);
        $user = User::findByUsername($username);
        if ($user) {
            Yii::$app->authManager->assign($permit, $user->getId());
        }
    }
    
    
}
