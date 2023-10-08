<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Vote".
 *
 * @property integer $id
 * @property integer $voterId
 * @property integer $categoryId
 * @property integer $nomineeId
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $createdById
 * @property string $createdBy
 * @property integer $updatedById
 * @property string $updatedBy
 *
 * @property Category $category
 * @property Nominee $nominee
 * @property Voter $voter
 */
class Vote extends Base
{

    public $totalVotes;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Vote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voterId', 'categoryId', 'nomineeId', 'createdById', 'createdBy'], 'required'],
            [['voterId', 'categoryId', 'nomineeId', 'createdById', 'updatedById'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['createdBy', 'updatedBy'], 'string', 'max' => 128],
            [['voterId', 'categoryId', 'nomineeId'], 'unique', 'targetAttribute' => ['voterId', 'categoryId', 'nomineeId'], 'message' => Yii::t('app', 'The combination of Voter, Category and Nominee has already been chosen.')],
            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['categoryId' => 'id']],
            [['nomineeId'], 'exist', 'skipOnError' => true, 'targetClass' => Nominee::className(), 'targetAttribute' => ['nomineeId' => 'id']],
            [['voterId'], 'exist', 'skipOnError' => true, 'targetClass' => Voter::className(), 'targetAttribute' => ['voterId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'voterId' => Yii::t('app', 'Voter'),
            'categoryId' => Yii::t('app', 'Category'),
            'nomineeId' => Yii::t('app', 'Nominee'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdById' => Yii::t('app', 'Created By ID'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedById' => Yii::t('app', 'Updated By ID'),
            'updatedBy' => Yii::t('app', 'Updated By'),
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
    public function getVoter()
    {
        return $this->hasOne(Voter::className(), ['id' => 'voterId']);
    }

    /**
     * @inheritdoc
     * @return VoteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VoteQuery(get_called_class());
    }
}
