<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "skiroutes_image".
 *
 * @property integer $id
 * @property integer $route_id
 * @property integer $image_id
 *
 * @property File $image
 * @property Skiroutes $route
 */
class SkiroutesImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'skiroutes_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['route_id', 'image_id'], 'required'],
            [['route_id', 'image_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'route_id' => 'Route ID',
            'image_id' => 'Image ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(File::className(), ['id' => 'image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoute()
    {
        return $this->hasOne(Skiroutes::className(), ['id' => 'route_id']);
    }
}
