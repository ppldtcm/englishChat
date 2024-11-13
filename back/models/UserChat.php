<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "userChat".
 *
 * @property int $id_user
 * @property string $name
 * @property int $numberOfErrors
 *
 * @property Chat[] $chats
 */
class UserChat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userChat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'numberOfErrors'], 'required'],
            [['numberOfErrors'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'name' => 'Name',
            'numberOfErrors' => 'Number Of Errors',
        ];
    }

    /**
     * Gets query for [[Chats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::class, ['id_user' => 'id_user']);
    }
}
