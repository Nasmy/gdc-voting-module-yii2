<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "CategoryNominee".
 *
 * @property integer $id
 * @property integer $categoryId
 * @property integer $nomineeId
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $createdById
 * @property integer $updatedById
 *
 * @property Category $category
 * @property Nominee $nominee
 * @property User $createdBy
 * @property User $updatedBy
 */
class CategoryNominee extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CategoryNominee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoryId', 'nomineeId'], 'required'],
            [['categoryId', 'nomineeId', 'createdById', 'updatedById'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['categoryId', 'nomineeId'], 'unique', 'targetAttribute' => ['categoryId', 'nomineeId'], 'message' => 'The combination of Category ID and Nominee ID has already been taken.'],
            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['categoryId' => 'id']],
            [['nomineeId'], 'exist', 'skipOnError' => true, 'targetClass' => Nominee::className(), 'targetAttribute' => ['nomineeId' => 'id']],
            [['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['createdById' => 'id']],
            [['updatedById'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updatedById' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'categoryId' => Yii::t('app', 'Category'),
            'nomineeId' => Yii::t('app', 'Nominee'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdById' => Yii::t('app', 'Created By'),
            'updatedById' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'categoryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNominee()
    {
        return $this->hasOne(Nominee::className(), ['id' => 'nomineeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'createdById']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updatedById']);
    }

    /**
     * @inheritdoc
     * @return CategoryNomineeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryNomineeQuery(get_called_class());
    }

}
