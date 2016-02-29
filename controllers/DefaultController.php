<?php

namespace jaclise\tree\controllers;

use jaclise\tree\models\Tree;
use jaclise\tree\TreeView;
use yii\web\Controller;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class DefaultController extends Controller
{
  public function behaviors()
  {
      return [
          'verbs' => [
              'class' => VerbFilter::className(),
              'actions' => [
                  'delete' => ['post'],
              ],
          ],
          'access' => [
              'class' => AccessControl::className(),
              'rules' => [
                  [
                      'allow' => true,
                      'roles' => ['@']
                  ]
              ]
          ],
      ];
  }

    public function actionIndex()
    {
        $htmlData=TreeView::widget([
            // single query fetch to render the tree
            'query'             => Tree::find()->addOrderBy('root, lft'),
            'headingOptions'    => ['label' => '基础数据管理'],
            'isAdmin'           => true,                       // optional (toggle to enable admin mode)
            'displayValue'      => 1,                           // initial display value
            'softDelete'      => false,                        // normally not needed to change
            //'cacheSettings'   => ['enableCache' => true]      // normally not needed to change
        ]);
        return $this->render('index', [
            'htmlData' => $htmlData,
        ]);
    }
}
