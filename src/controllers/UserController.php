<?php

namespace app\controllers;

use app\models\RegisterModel;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class UserController extends Controller
{
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            ['username', 'unique'],
            ['email', 'unique'],
            ['email', 'email'], // Ensure email format is valid
            ['password', 'string', 'min' => 6], // Password must be at least 6 characters
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Only logged-in users can access
                    ],
                    [
                        'actions' => ['delete'], // Restrict delete action to admin only
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionList()
    {
        dd(1);
        $model = new RegisterModel();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registering. You can now login.');
            return $this->redirect(['site/login']);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            // Set and hash the password before saving
            $model->setPassword($model->password);
            $model->generateAuthKey();

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // If a new password is provided, hash it
            if (!empty($model->password)) {
                $model->setPassword($model->password);
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


}
