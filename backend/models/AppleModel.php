<?php
declare(strict_types=1);

namespace backend\models;

use yii\db\ActiveRecord;

/**
 * Class AppleModel
 *
 * @package backend\models
 *
 * @property integer $id
 * @property string  $color
 * @property string  $state
 * @property integer $created_at
 * @property integer $fall_at
 * @property float   $size
 *
 */
class AppleModel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%apple}}';
    }

}
