<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "glossary".
 *
 * @property integer $id
 * @property string $term
 * @property string $description
 */
class Glossary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'glossary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['term', 'description'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'term' => 'Term',
            'description' => 'Description',
        ];
    }
}
