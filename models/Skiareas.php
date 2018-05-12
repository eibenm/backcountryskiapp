<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "skiareas".
 *
 * @property integer $id
 * @property string $name_area
 * @property string $conditions
 * @property string $color
 * @property string $bounds_southwest
 * @property string $bounds_northeast
 * @property integer $image_id
 * @property integer $permissions
 *
 * @property File $image
 * @property Skiroutes[] $skiroutes
 * @property Skiroutes[] $skiroutesSorted
 */
class Skiareas extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'skiareas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_area', 'conditions', 'color', 'bounds_southwest', 'bounds_northeast'], 'string'],
            [['image_id', 'permissions'], 'integer'],
            [['bounds_southwest', 'bounds_northeast'], 'match',
                'pattern' => '/[0-9]{2}\.[0-9]{3,},\s?\-[0-9]{3}\.[0-9]{3,}/',
                'message' => '{attribute} must match the example format: 40.45123, -120.45124'
            ],
            [['bounds_southwest', 'bounds_northeast'], 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_area' => 'Name Area',
            'conditions' => 'Conditions',
            'color' => 'Color',
            'bounds_southwest' => 'Southwest Bounds',
            'bounds_northeast' => 'Northeast Bounds',
            'image_id' => 'Image ID',
            'permissions' => 'Permissions',
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
    public function getSkiroutes()
    {
        return $this->hasMany(Skiroutes::className(), ['skiarea_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSkiroutesSorted()
    {
        return $this->hasMany(Skiroutes::className(), ['skiarea_id' => 'id'])->orderBy(['name_route' => SORT_ASC]);
    }

    /**
     * Virtual Attribute
     * Truncates area conditions to 300 chars + ellipsis if necessary
     * @return string
     */
    public function getAreaConditions()
    {
        return (strlen($this->conditions) > 300) ? substr($this->conditions, 0, 300) . ' ...' : $this->conditions;
    }
}
