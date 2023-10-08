<?php

namespace app\models;

use Yii;

class Base extends \yii\db\ActiveRecord
{
    public $modelName = null;

    public $statusCode = null;
    public $statusMessage = null;
    public $errorCode = null;

    private $_oldAttributes = array();

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const CRYPT_SALT = '$6$rounds=5000$V%7^CFF73;8^h~E$';

    private $_statuses = [
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_ACTIVE => 'Active',

    ];

    public function getOldAttributes()
    {
        return $this->_oldAttributes;
    }

    public function setOldAttributes($value)
    {
        $this->_oldAttributes = $value;
    }

    /**
     * Returns the statuses.
     * @return array statuses array.
     */
    public function getStatuses()
    {
        return $this->_statuses;
    }

    public function beforeValidate()
    {
        // TODO: Think about setting below attributes when any of other attribute not empty.
        // Otherwise this initialization affect Search > search > !$this->validate() section.
        // updatedAt
        if ($this->hasAttribute('updatedAt'))
            $this->updatedAt = Yii::$app->util->getUtcDateTime();

        // updatedById
        if ($this->hasAttribute('updatedById') && null == $this->updatedById) {
            if (Yii::$app instanceof \yii\web\Application) {
                if (is_object(Yii::$app->user->identity)) {
                    $this->updatedById = Yii::$app->user->identity->id;
                } else {
                    $this->updatedById = -1;
                }
            } elseif (Yii::$app instanceof \yii\console\Application) {
                $this->updatedById = -1;
            } else {
                $this->updatedById = null;
            }
        }

        // New record
        if ($this->isNewRecord) {
            // createdAt
            if ($this->hasAttribute('createdAt'))
                $this->createdAt = Yii::$app->util->getUtcDateTime();

            // createdById
            if ($this->hasAttribute('createdById') && null == $this->createdById) {
                if (Yii::$app instanceof \yii\web\Application) {
                    if (is_object(Yii::$app->user->identity)) {
                        $this->createdById = Yii::$app->user->identity->id;
                    } else {
                        $this->createdById = -1;
                    }
                } elseif (Yii::$app instanceof \yii\console\Application) {
                    $this->createdById = -1;
                } else {
                    $this->createdById = null;
                }
            }

            // updatedAt
            if ($this->hasAttribute('updatedAt'))
                $this->updatedAt = null;

            // updatedById
            if ($this->hasAttribute('updatedById'))
                $this->updatedById = null;
        }

        // Setting empty string attributes to null
        $attributes = $this->getAttributes();
        foreach ($attributes as $name => $value) {
            $this->$name = trim($value);

            if ('' === $this->$name)
                $this->$name = null;
        }

        return parent::beforeValidate();
    }

    public function afterValidate()
    {
        return parent::afterValidate();
    }

    public function afterFind()
    {
        $this->setOldAttributes($this->getAttributes());
        return parent::afterFind();
    }

    /**
     * Trim model attributes
     */
    public function trimAttributes()
    {
        $attrs = $this->getAttributes();

        foreach ($attrs as $name => $value) {
            $this->$name = trim($value);
        }
    }

    public function getLastError()
    {
        $errorData = [];
        if ($this->hasErrors()) {
            $errors = $this->getFirstErrors();
            reset($errors);
            list($attribute, $message) = each($errors);
            $errorData = [
                'attribute' => $attribute,
                'message' => $message
            ];
        }

        return $errorData;
    }

    /**
     * Validate specific model
     * @param array $attributes specific attributes to be validated
     * @return boolean $result true of false.
     */
    public function validateModel($attributes = null)
    {
        $result = false;
        $name = get_class($this);

        if ($this->validate($attributes)) {
            $result = true;
            Yii::$app->appLog->writeLog("{$name} record validation success.");
        } else {
            Yii::$app->appLog->writeLog("{$name} record validation failed.", ['errors' => $this->errors, 'attributes' => $this->attributes]);
        }

        return $result;
    }

    /**
     * Validate multiple models
     * @param mixed $models
     * @return boolean true of false.
     */
    public static function validateMultipleModels($models)
    {
        $result = true;

        foreach ($models as $model) {
            if (!$model->validateModel()) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Generic function to save any model
     * @return boolean $result true of false.
     */
    public function saveModel()
    {
        $result = false;
        //$name = get_class($this);
        //$name = $this->className();
        $this->setModelName();
        $name = $this->getModelName();

        if ($this->validate()) {
            try {
                if ($this->save()) {
                    $result = true;
                    Yii::$app->appLog->writeLog("{$name} record saved.", ['attributes' => $this->attributes]);
                } else {
                    Yii::$app->appLog->writeLog("{$name} record save failed.", ['errors' => $this->errors, 'attributes' => $this->attributes]);
                }
            } catch (\Exception $e) {
                Yii::$app->appLog->writeLog("{$name} record save failed", ['exception' => $e->getMessage(), 'attributes' => $this->attributes]);
            }
        } else {
            Yii::$app->appLog->writeLog("{$name} record save failed. Validation failed.", ['errors' => $this->errors, 'attributes' => $this->attributes]);
            // Use for API validations
            $errors = $this->getLastError();
            if (!empty($errors)) {
                $this->statusCode = $errors['attribute'];
                $this->statusMessage = $errors['message'];
            }
        }

        return $result;
    }

    /**
     * Save multiple models
     * @param mixed $models
     * @return boolean true of false.
     */
    public static function saveMultipleModels($models)
    {
        $result = true;

        foreach ($models as $model) {
            if (!$model->saveModel()) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Generic function to delete any model
     * @return boolean $result true of false.
     */
    public function deleteModel()
    {
        $name = get_class($this);
        $result = false;

        try {
            if ($this->delete()) {
                $result = true;
                Yii::$app->appLog->writeLog("{$name} record deleted.", ['attributes' => $this->attributes]);
            } else {
                Yii::$app->appLog->writeLog("{$name} record delete failed.", ['errors' => $this->errors, 'attributes' => $this->attributes]);
            }
        } catch (\Exception $e) {
            Yii::$app->appLog->writeLog("{$name} record delete failed.", ['exception' => $e->getMessage(), 'attributes' => $this->attributes]);
        }

        return $result;
    }

    /**
     * Delete multiple models
     * @param mixed $models
     * @return boolean true of false.
     */
    public static function deleteMultipleModels($models)
    {
        $result = true;

        foreach ($models as $model) {
            if (!$model->deleteModel()) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * User full name
     * @param integer $userId user id
     * @return string
     */
    public function getUserFullName($userId)
    {
        return User::getFullNameById($userId);
    }

    public function getModelName() {
        return $this->modelName;
    }

    public function setModelName() {
        $arr = explode('\\', $this->className());
        $this->modelName = end($arr);
    }
}
