<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pay_order".
 *
 * @property int $id
 * @property string $order_id
 * @property string $result
 */
class PayOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pay_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'result'], 'required'],
            [['order_id', 'result'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'result' => 'Result',
        ];
    }
}
