<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "geocode_autocomplete".
 *
 * @property integer $id
 * @property string $place_id
 * @property string $description
 * @property integer $parent_id
 * @property integer $region
 *
 * @property GeocodeAutocompleteTranslation[] $geocodeAutocompleteTranslations
 */
class GeocodeAutocomplete extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geocode_autocomplete';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['parent_id', 'region'], 'integer'],
            [['place_id', 'description', 'oblast'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'place_id' => 'Код',
            'description' => 'Название',
            'parent_id' => 'Родитель',
            'region' => 'Регион',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeocodeAutocompleteTranslations()
    {
        return $this->hasMany(GeocodeAutocompleteTranslation::className(), ['content_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(GeocodeAutocomplete::className(), ['id' => 'parent_id']);
    }
}
