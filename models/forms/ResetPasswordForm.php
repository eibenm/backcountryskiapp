<?php
namespace app\models\forms;

use app\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $passwordConfirm;

    /**
     * @var \app\models\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param  string   $token
     * @param  array    $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password'        => 'Password',
            'passwordConfirm' => 'Confirm Password'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'passwordConfirm'], 'required'],
            ['password', 'string', 'min' => 6],
            ['passwordConfirm', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match'],
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}
