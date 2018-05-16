<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "version".
 *
 * @property integer $id
 * @property string $version
 * @property integer $live
 */
class Version extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'version';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['version'], 'number'],
            [['live'], 'integer'],
            [['live'], 'default', 'value' => 0],
            [['live'], 'in', 'range' => [0, 1]],
            [['version', 'live'], 'required'],
            [['version'], 'trim'],
            // Custom validation rule that makes sure the most recently entered version is higher than the last
            /*['version', function ($attribute, $params) {
                $lastRecord = self::find()->orderBy(['id' => SORT_DESC])->one();
                if (!(floatval($this->$attribute) >= floatval($lastRecord->version))) {
                    $this->addError($attribute, 'This version must be higher than the last version!');
                }
            }],*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'live' => 'Live',
            'version' => 'Version Number',
        ];
    }
}
