<?php

namespace app\api\modules\v1\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "short_url".
 *
 * @property int $id
 * @property string $url
 * @property string $short
 * @property int|null $redirects
 */
class ShortUrl extends ActiveRecord
{
    const LENGTH = 6;

    /**
     * ShortUrl constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->short = $this->generateShort();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'short_url';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'short'], 'required'],
            [['url'], 'string'],
            [['redirects'], 'integer'],
            [['redirects'], 'default', 'value' => 0],
            [['short'], 'string', 'max' => 255],
            [['short'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'short' => 'Short',
            'redirects' => 'Redirects',
        ];
    }

    /**
     * @param $short
     * @return bool
     */
    public function existsShort($short)
    {
        return self::find()->where(['short' => $short])->exists();
    }

    /**
     * @return string
     */
    public function generateShort()
    {
        $short = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"), 0, self::LENGTH);
        if (!$this->existsShort($short)) {
            return $short;
        }

        return $this->generateShort();
    }
}
