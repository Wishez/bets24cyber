<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "referals_detail".
 *
 * @property integer $id_ref
 * @property integer $clicks
 * @property integer $registrations
 * @property integer $deposits
 * @property integer $MGR
 * @property integer $profit
 * @property string $date
 */
class ReferalsDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'referals_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_ref', 'date'], 'required'],
            [['id_ref', 'clicks', 'registrations', 'deposits', 'MGR', 'profit'], 'integer'],
            [['date'], 'date', 'format' => 'php:Y-m-d']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_ref' => 'Id Ref',
            'clicks' => 'Clicks',
            'registrations' => 'Registrations',
            'deposits' => 'Deposits',
            'MGR' => 'Mgr',
            'profit' => 'Profit',
            'date' => 'Date',
        ];
    }

    public static function getDetails($id_ref)
    {
        return self::find()
            ->select(
                [
                    'clicks'=>"sum(`clicks`)",
                    'registrations'=>"sum(`registrations`)",
                    'deposits'=>"sum(`deposits`)",
                    'MGR'=>"sum(`MGR`)",
                    'profit'=>"sum(`profit`)",
                    "date"=>"date_format(date, '%Y-%m')"]
            )
            ->where(['id_ref' => $id_ref])
            ->groupBy(["date_format(date, '%Y-%m')"]);
    }
}
