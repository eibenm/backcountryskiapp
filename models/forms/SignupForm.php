<?php

namespace app\models\forms;

use app\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $nameFirst;
    public $nameLast;
    public $email;
    public $newPassword;
    public $newPasswordConfirm;
    public $usertype;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username'           => 'Username',
            'nameFirst'          => 'First Name',
            'nameLast'           => 'Last Name',
            'newPassword'        => 'Password',
            'newPasswordConfirm' => 'Confirm New Password',
            'usertype'           => 'Usertype'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => '{attribute} can contain only letters, numbers, and "_"'],

            ['nameFirst', 'required'],
            ['nameLast', 'required'],
            
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'This email address has already been taken.'],

            ['newPassword', 'required'],
            ['newPassword', 'string', 'min' => 6],
            ['newPassword', 'filter', 'filter' => 'trim'],
            
            ['newPasswordConfirm', 'required'],
            ['newPasswordConfirm', 'compare', 'compareAttribute' => 'newPassword', 'message' => 'Passwords do not match'],
            
            ['usertype', 'default', 'value' => User::USERTYPE_USER],
            ['usertype', 'in', 'range' => [User::USERTYPE_USER, User::USERTYPE_ADMIN]],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->name_first = $this->nameFirst;
            $user->name_last = $this->nameLast;
            $user->email = $this->email;
            $user->usertype = $this->usertype;
            $user->setPassword($this->newPassword);
            $user->generateAuthKey();
            if ($user->save(false)) {
                return $user;
            }
        }

        return null;
    }
}
