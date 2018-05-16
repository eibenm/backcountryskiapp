<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $name_first
 * @property string $name_last
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $usertype
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @var int Usertype Admin
     */
    const USERTYPE_ADMIN = 10;
    
    /**
     * @var int Usertype Admin
     */
    const USERTYPE_USER = 20;
    
    /**
     * @var string Current password
     */
    public $currentPassword;
    
    /**
     * @var string New password
     */
    public $newPassword;
    
    /**
     * @var string New password confirmation
     */
    public $newPasswordConfirm;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => self::className(), 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 50],
            ['username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => '{attribute} can contain only letters, numbers, and "_"'],

            ['name_first', 'required'],
            ['name_last', 'required'],
            [['name_first', 'name_last'], 'string', 'max' => 50],
            
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 50],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => 'This email address has already been taken.'],
            
            ['usertype', 'default', 'value' => User::USERTYPE_USER],
            ['usertype', 'in', 'range' => [User::USERTYPE_USER, User::USERTYPE_ADMIN]],
            
            // Password Rules
            
            ['currentPassword', 'required', 'on' => ['account']],
            ['currentPassword', 'validateCurrentPassword', 'on' => ['account']],
            
            ['newPassword', 'string', 'min' => 6],
            ['newPassword', 'filter', 'filter' => 'trim'],
            
            // Only require password confirm when new password is filled out
            ['newPasswordConfirm', 'required', 'on' => ['account','update'], 'when' => function() { return false; }, 'whenClient' => "function() { return $('#user-newpassword').val() !== ''; }"],
            ['newPasswordConfirm', 'compare', 'compareAttribute' => 'newPassword', 'message' => 'Passwords do not match']
        ];
    }
    
    /**
     * Validate current password
     */
    public function validateCurrentPassword($attribute)
    {
        if (!$this->validatePassword($this->$attribute)) {
            $this->addError($attribute, 'Current password incorrect');
        }
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {     
        return [
            'timestamp' => [
                'class'      => TimestampBehavior::className(),
                'value'      => function () { return time(); },
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'username'    => 'Username',
            'name_first'  => 'First Name',
            'name_last'   => 'Last Name',
            'email'       => 'Email',
            'auth_ley'    => 'Auth Key',
            'created_at'  => 'Created',
            'updated_at'  => 'Updated',
            'usertype'    => 'Usertype',
            
            // virtual attributes set above
            'newPassword'     =>    'New Password',
            'currentPassword' =>    'Current Password',
            'newPasswordConfirm' => 'Confirm New Password'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    //---------------------- Virtual Attributes --------------------------------
    
    /**
     * Virtual attribute - retreives usertype
     */
    public function getUserType()
    {
        switch ($this->usertype) {
            case self::USERTYPE_ADMIN: return 'admin';
            case self::USERTYPE_USER: return 'user';
            default: return '';
        }
    }
    
    //---------------------- General Functions ---------------------------------
    
    public static function userTypesdropdown() {
        return [
            self::USERTYPE_ADMIN => 'admin',
            self::USERTYPE_USER  => 'user'
        ];
    }
}
