<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "Nominee".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $imageName
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $createdById
 * @property integer $updatedById
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property CategoryNominee[] $categoryNominees
 * @property Category[] $nomineeCategories
 * @property Vote[] $nomineeVotes
 */
class Nominee extends Base
{
    public $image = null;
    public $imageExt = null;
    public $imageSrcPath = null;
    public $imageWebPath = null;
    public $categoryName = null;
    public $categoryOrder = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Nominee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'imageName'], 'required'],
            ['image', 'required', 'on' => 'create'],
            [['createdById', 'updatedById'], 'integer'],

            [['name', 'description', 'imageName'], 'string', 'max' => 512],

            //[['image'], 'file', 'extensions' => 'jpg, gif, png'],
            //[['image'], 'file', 'maxSize' => '1024000'],
            ['image', 'image', 'extensions' => 'jpg, gif, png', 'minWidth' => 400, 'maxWidth' => 400, 'minHeight' => 400, 'maxHeight' => 400, 'maxSize' => 1024*1024*1024],

            [['createdAt', 'updatedAt', 'image', 'imageExt', 'imageSrcPath', 'imageWebPath'], 'safe'],

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
            'description' => Yii::t('app', 'Description'),
            'image' => Yii::t('app', 'Image'),
            'imageName' => Yii::t('app', 'Image'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdById' => Yii::t('app', 'Created By'),
            'updatedById' => Yii::t('app', 'Updated By'),
            'categoryName' => Yii::t('app', 'Category'),
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
        return $this->hasMany(CategoryNominee::className(), ['nomineeId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNomineeCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'categoryId'])->viaTable('CategoryNominee', ['nomineeId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNomineeVotes()
    {
        return $this->hasMany(Vote::className(), ['nomineeId' => 'id']);
    }

    /**
     * @inheritdoc
     * @return NomineeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NomineeQuery(get_called_class());
    }

    public function afterFind()
    {
        $this->setImageWebPath();
        $this->setImageSrcPath();
        parent::afterFind();
    }

    public function setImageExt() {
        $arr = explode('.', $this->imageName);
        $this->imageExt = end($arr);
    }

    public function setCustomImageName() {
        $search  = array(' ', '_');
        $replace = array('-', '-');

        //$this->imageName = str_replace($search, $replace, $this->name) . '-' . Yii::$app->security->generateRandomString() . ".{$this->imageExt}";
        $this->imageName = str_replace($search, $replace, $this->name) . '-' . time() . ".{$this->imageExt}";
    }

    public function createImageDir() {
        $result = true;

        $imageDir = Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadDir'] . strtolower($this->tableName());

        if (!file_exists($imageDir))
            $result = mkdir($imageDir, Yii::$app->params['uploadDirPerm'], true);

        return $result;
    }

    public function setImageSrcPath() {
        $imageDir = Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadDir'] . strtolower($this->tableName());

        if (!file_exists($imageDir)) {
            if ($this->createImageDir()) {
                $this->imageSrcPath = $imageDir . '/' . $this->imageName;
            }
        } else {
            $this->imageSrcPath = $imageDir . '/' . $this->imageName;
        }
    }

    public function setImageWebPath() {
        $imageDir = Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadDir'] . strtolower($this->tableName());

        if (file_exists($imageDir)) {
            $this->imageWebPath = Url::to(Url::base() . '/' . Yii::$app->params['uploadDir'] . strtolower($this->tableName()) . '/' . $this->imageName, true);
        }
    }

}
