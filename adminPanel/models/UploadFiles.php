<?php


namespace adminPanel\models;

use yii\base\Model;
use yii\imagine\Image;
use Yii;

class UploadFiles extends Model {

    public $imageFiles;

    public function rules() {

        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg, jpeg', 'maxFiles' => 3]
        ];

    }

    public function upload() {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $img = $file->tempName;
                Image::getImagine()
                    ->open($img)
                    ->save(Yii::getAlias('@adminPanel') . '/files/' . $file->baseName . '.' . $file->extension, ['quality' => 90]);
            }
            return true;
        }
        else {
            return false;
        }
    }

    public function attributeLabels() {
        return [
            'imageFiles' => 'Изображение'
        ];
    }

}