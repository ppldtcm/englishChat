<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property int $id_chat
 * @property int $id_user
 * @property string $lastMessage
 *
 * @property User $user
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'lastMessage'], 'required'],
            [['id_user'], 'integer'],
            [['lastMessage'], 'string'],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id_user']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_chat' => 'Id Chat',
            'id_user' => 'Id User',
            'lastMessage' => 'Last Message',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id_user' => 'id_user']);
    }
}
