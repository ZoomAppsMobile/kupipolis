<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "countries".
 *
 * @property integer $id
 * @property integer $country_id
 * @property string $name
 */
class Countries extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'countries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'name'], 'required'],
            [['country_id', 'type'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_id' => 'Country ID',
            'name' => 'Name',
        ];
    }
}
