<?php

namespace app\models;

use Yii;
use Eventviva\ImageResize;

/**
 * This is the model class for table "file".
 *
 * @property integer $id
 * @property string $filename
 * @property string $avatar
 * @property string $caption
 * @property integer $kml_image
 *
 * @property Skiareas[] $skiareas
 * @property Skiroutes[] $skiroutes
 * @property SkiroutesImage[] $skiroutesImages
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @var file public variable for file upload
     */
    public $file;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename', 'avatar'], 'required'],
            [['kml_image'], 'integer'],
            [['filename', 'avatar', 'caption'], 'string'],
            
            // virtual attributes set above
            ['file', 'safe'],
            ['file', 'file', 
                'skipOnEmpty' => true,
                //'maxSize' => 1024 * 1024 * 2,
                //'tooBig' => 'The file {file} was larger than 2MB!  Please upload a smaller file.',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'avatar' => 'Avatar',
            'caption' => 'Caption',
            'kml_image' => 'Is KML Image'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSkiareas()
    {
        return $this->hasMany(Skiareas::className(), ['image_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSkiroutes()
    {
        return $this->hasMany(Skiroutes::className(), ['kml_image_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSkiroutesImages()
    {
        return $this->hasMany(SkiroutesImage::className(), ['image_id' => 'id']);
    }
    
    public function uploadImage($width = null)
    {
        $bytes = Yii::$app->security->generateRandomKey(32);
        $randomString = str_replace(['+','/'], '', substr(base64_encode($bytes), 0, 32));
        $imageWidth = is_null($width) ? 900 : $width;
        $this->filename = $this->file->baseName . '.' . $this->file->extension;
        $this->avatar = $randomString . '.' . $this->file->extension;
        if ($this->validate()) {
            $this->save(false);
            $filePath = Yii::$app->params['imagePath'] . $this->avatar;
            $image = new ImageResize($this->file->tempName);
            if ($image->getSourceWidth() > $imageWidth) {
                $image->resizeToWidth($imageWidth)
                      ->save($filePath);
            }
            else {
                $this->file->saveAs($filePath);
            }
            return true;
        }
        return false;
    }
    
    public function deleteCurrentImage()
    {
        $filePath = Yii::$app->params['imagePath'] . $this->avatar;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
