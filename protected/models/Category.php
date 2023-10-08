<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseArrayHelper;

/**
 * This is the model class for table "Category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $order
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $createdById
 * @property integer $updatedById
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property CategoryNominee[] $categoryNominees
 * @property Nominee[] $nominees
 * @property Vote[] $votes
 */
class Category extends Base
{
    public $nomineeListArr = null;
    public $categoryId = null;

    const VOTE_SAVED = 1;
    const VOTE_DELETED = 2;
    const VOTE_ERROR = 0;
    const VOTE_EXIST = 1;
    const VOTE_NOT_EXIST = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'nomineeListArr', 'order'], 'required'],
            //[['nomineeListArr'], 'each'],
            [['order', 'createdById', 'updatedById'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['name', 'order'], 'unique'],
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
            'name' => Yii::t('app', 'Name'),
            'order' => Yii::t('app', 'Order'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdById' => Yii::t('app', 'Created By'),
            'updatedById' => Yii::t('app', 'Updated By'),
            'nomineeListArr' => Yii::t('app', 'Nominees'),
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryNominees()
    {
        return $this->hasMany(CategoryNominee::className(), ['categoryId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNominees()
    {
        return $this->hasMany(Nominee::className(), ['id' => 'nomineeId'])->viaTable('CategoryNominee', ['categoryId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Vote::className(), ['categoryId' => 'id']);
    }

    /**
     * @param null $model
     * @return array
     */
    public function getNomineeList($model = null) {
        if($model === null){
            $model = Nominee::find()->all();
        }
        $listData = $this->customArrayMap($model, 'id', 'name', 'imageName');
        return $listData;
    }

    /**
     * @return string
     */
    public function getNomineesAsHtml(){
        $nominees = $this->getNominees()->all();
        $html = '<ul class="nominee-list">';
        foreach ($nominees as $nominee){
            $html .= '<li><img src="'.$nominee->imageWebPath.'"/> '.$nominee->name.'</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * @return array
     */
    public function getCategoryList(){
        $model = Category::find()->orderBy('order')->all();
        return ArrayHelper::map($model, 'id', 'name');
    }

    /**
     * @param $array
     * @param $from
     * @param null $to
     * @param null $bind
     * @return array
     */
    public function customArrayMap($array, $from, $to = null, $bind = null)
    {
        $result = [];
        foreach ($array as $element) {

            $key = BaseArrayHelper::getValue($element, $from);
            $value = BaseArrayHelper::getValue($element, $to);

            if($bind){
                $bindValue = BaseArrayHelper::getValue($element, $bind);
                $result[$key] = $value.'_'.$bindValue;
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * @inheritdoc
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }
}
