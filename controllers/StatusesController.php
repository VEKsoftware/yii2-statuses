<?php

namespace statuses\controllers;

use statuses\models\Statuses;
use statuses\models\StatusesLinks;
use statuses\models\StatusesLinksSearch;
use statuses\models\StatusesSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * StatusesController implements the CRUD actions for Statuses model.
 */
class StatusesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'link-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Statuses models.
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
        $searchModel = new StatusesSearch();

        if (!$searchModel->isAllowed('statuses.statuses.index')) {
            throw new ForbiddenHttpException(Yii::t('statuses', 'Access restricted'));
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Statuses model.
     *
     * @param int $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (!$model->isAllowed('statuses.statuses.view')) {
            throw new ForbiddenHttpException(Yii::t('statuses', 'Access restricted'));
        }

        $searchModel = new StatusesLinksSearch();
        $dataProvider = $searchModel->search($id, Yii::$app->request->queryParams);

        return $this->render(
            'view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Statuses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Statuses the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Statuses::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('statuses', 'The requested page does not exist.'));
        }
    }

    /**
     * Creates a new Statuses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        $model = new Statuses();

        if (!$model->isAllowed('statuses.statuses.create')) {
            throw new ForbiddenHttpException(Yii::t('statuses', 'Access restricted'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Statuses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!$model->isAllowed('statuses.statuses.update')) {
            throw new ForbiddenHttpException(Yii::t('statuses', 'Access restricted'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Create links to other units of Statuses model.
     *
     * @param int $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionLinkCreate($id)
    {
        $model = $this->findModel($id);

        if (!$model->isAllowed('statuses.statuses.link.create')) {
            throw new ForbiddenHttpException(Yii::t('statuses', 'Access restricted'));
        }

        $modelLink = new StatusesLinks();
        $modelLink->status_from = $model->id;

        $post = Yii::$app->request->post();
        if (!empty($post)) {
            if ($modelLink->load($post) && $modelLink->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $searchModel = new StatusesSearch();
        $dataProviderModel = $searchModel->searchUnlink($model, Yii::$app->request->queryParams);

        $rightsSearchClass = \statuses\Statuses::getInstance()->accessRightsSearchClass;
        $rightsSearchModel = new $rightsSearchClass();
        $rightsDataProvider = $rightsSearchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'link-create', [
            'model' => $model,
            'modelLink' => $modelLink,

            'searchModel' => $searchModel,
            'dataProviderModel' => $dataProviderModel,

            'rightsSearchModel' => $rightsSearchModel,
            'rightsDataProvider' => $rightsDataProvider,
        ]);
    }

    /**
     * Delete links to other units of Statuses model.
     *
     * @param $status_from
     * @param $status_to
     * @param $right_id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @internal param int $id
     *
     */
    public function actionLinkDelete($status_from, $status_to, $right_id)
    {
        $model = $this->findModel($status_from);

        if (!$model->isAllowed('statuses.statuses.link.delete')) {
            throw new ForbiddenHttpException(Yii::t('statuses', 'Access restricted'));
        }

        $modelLink = $this->findModelLink($status_from, $status_to, $right_id);

        $modelLink->deleteAll(
            'status_from = ' . $modelLink->status_from . ' AND ' .
            'status_to = ' . $modelLink->status_to . ' AND ' .
            'right_id = ' . $modelLink->right_id
        );

        return $this->redirect(['view', 'id' => $status_from]);
    }

    /**
     * Finds the StatusesLinks model based on keys 'status_from', 'status_to' and 'right_id'.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $status_from
     * @param $status_to
     * @param $right_id
     * @return StatusesLinks the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     * @internal param int $id
     *
     */
    protected function findModelLink($status_from, $status_to, $right_id)
    {
        $model = StatusesLinks::find()
            ->where(['status_from' => $status_from, 'status_to' => $status_to, 'right_id' => $right_id])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('statuses', 'The requested page does not exist.'));
        }
    }

    /**
     * Deletes an existing Statuses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!$model->isAllowed('statuses.statuses.delete')) {
            throw new ForbiddenHttpException(Yii::t('statuses', 'Access restricted'));
        }

        $model->delete();

        return $this->redirect(['index']);
    }
}
