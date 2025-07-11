<?php

namespace app\modules\admin;
use yii;
/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    public $defaultRoute = 'default/index';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::$app->layoutPath = Yii::getAlias('@app/modules/admin/views/layouts');
        Yii::$app->layout='admin';

        // custom initialization code goes here
    }
}
