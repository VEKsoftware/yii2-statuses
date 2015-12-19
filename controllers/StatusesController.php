<?php

namespace statuses\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use statuses\models\Statuses;
use statuses\models\StatusesSearch;

use statuses\models\StatusesLinks;
use statuses\models\StatusesLinksSearch;

use partneruser\models\RefRights;
use partneruser\models\RefRightsSearch;

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
                ],
            ],
        ];
    }

    /**
     * Lists all Statuses models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StatusesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Statuses model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Statuses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Statuses();

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
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * List links to other units of Statuses model
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionLinkView($id)
    {
        $model = $this->findModel($id);
        
        $searchModel = new StatusesLinksSearch();
        $dataProvider = $searchModel->search( $id, Yii::$app->request->queryParams );
        
        return $this->render(
            'link-view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Create links to other units of Statuses model
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionLinkCreate($id)
    {
        $model = $this->findModel($id);
        
        $searchModel = new StatusesSearch();
        $dataProviderModel = $searchModel->searchUnlink( $model, Yii::$app->request->queryParams );
        
        $rightsSearchModel = new RefRightsSearch();
        $rightsDataProvider = $rightsSearchModel->search( Yii::$app->request->queryParams );
        
        return $this->render(
            'link-create', [
            'model' => $model,
            
            'searchModel' => $searchModel,
            'dataProviderModel' => $dataProviderModel,
            
            'rightsSearchModel' => $rightsSearchModel,
            'rightsDataProvider' => $rightsDataProvider,
        ]);
    }

    /**
     * Deletes an existing Statuses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Statuses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Statuses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Statuses::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
