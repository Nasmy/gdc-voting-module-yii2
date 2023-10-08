<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;


class Register extends ActiveRecord
{

    public $producer;
    public $title;
    public $film;

  public static function tableName()
    {
        return 'registerdata';
    }

       public function rules()
    {
        return [
            [['title', 'title'], 'required'],
            [['film', 'film'], 'required'],
            [['producer', 'producer'], 'required'],
            
            
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Title of the Work'),
            'film' => Yii::t('app', 'Film Name'),
            'producer' => Yii::t('app', 'Producer'),
  
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
    public function getProducer()
    {
        return $this->hasOne(Voter::className(), ['id' => 'producerId']);
    }

    /**
     * @inheritdoc
     * @return RegisterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RegisterQuery(get_called_class());
    }
}
