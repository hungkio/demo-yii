<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

class User extends ActiveRecord  implements \yii\web\IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    public $password;

    public static function tableName()
    {
        return '{{%users}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'), // This will use MySQL's current timestamp in Y-m-d H:i:s format
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public function rules()
    {
        return [
            [['username'], 'required'],
            ['username', 'unique'],
            ['password', 'string', 'min' => 6 ],
            [['password'], 'required', 'on' => 'create'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['username', 'password']; // Fields required on create
        $scenarios['update'] = ['username', 'password']; // Fields for update
        return $scenarios;
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->generateAuthKey();
        }
        return parent::beforeSave($insert);
    }


}
