<?php
namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['username', 'fullname', 'email', 'phone', 'dob', 'address', 'district', 'pincode', 'state', 'gender', 'password'], 'required', 'on' => 'user'],
            [['username', 'password'], 'required', 'on' => 'admin'],
            [['phone', 'pincode', 'user_type', 'is_verified', 'otp', 'otp_expiry', 'status'], 'integer'],
            [['phone'], 'match', 'pattern' => '/^\d{10}$/', 'message' => 'Phone number must be exactly 10 digits.'],
            [['dob'], 'safe'],
            [['username', 'fullname', 'email', 'password'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 150],
            [['district', 'state', 'rbac'], 'string', 'max' => 30],
            [['gender'], 'string', 'max' => 10],
            [['email'], 'email'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['user'] = ['username', 'fullname', 'email', 'phone', 'dob', 'address', 'district', 'pincode', 'state', 'gender', 'password'];
        $scenarios['admin'] = ['username', 'password'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'fullname' => 'Fullname',
            'email' => 'Email',
            'phone' => 'Phone',
            'dob' => 'DOB',
            'address' => 'Address',
            'district' => 'District',
            'pincode' => 'Pincode',
            'state' => 'State',
            'gender' => 'Gender',
            'user_type' => 'User Type',
            'is_verified' => 'Is Verified',
            'otp' => 'OTP',
            'otp_expiry' => 'OTP Expiry',
            'password' => 'Password',
            'rbac' => 'Permissions (RBAC)',
            'status' => 'Status',
        ];
    }

    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public function generateOtp()
    {
        $this->otp = random_int(1000, 9999);
        $this->otp_expiry = time() + 120;
    }

    // IdentityInterface methods
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return '';
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }

    // User login method
    public function userLogin()
    {
        $user = self::findOne([
            'username' => $this->username,
            'user_type' => 1,
            'is_verified' => 1,
            'status' => 1,
        ]);

        if (!$user || $user->password !== $this->password) {
            return ['success' => false, 'message' => 'Invalid username or password.'];
        }

        if ($user->validatePassword($this->password)) {
            Yii::$app->user->login($user);
            return ['success' => true, 'message' => 'Login successful!'];
        }

        return ['success' => false, 'message' => 'Login failed.'];
    }

    //  Admin login method
    public function adminLogin()
    {
        $user = self::findOne(['username' => $this->username]);

        if (!$user || !in_array($user->user_type, [2, 3]) || $user->password !== $this->password) {
            return ['success' => false, 'message' => 'Invalid admin credentials.'];
        }

        if ($user->validatePassword($this->password)) {
            Yii::$app->user->login($user);
            return ['success' => true, 'message' => 'Login successful!'];
        }

        return ['success' => false, 'message' => 'Login failed.'];
    }

    //  RBAC Permission check
    public function hasPermission(string $permission): bool
    {
        $rbac = is_array($this->rbac) ? $this->rbac : json_decode($this->rbac, true);
        return is_array($rbac) && in_array($permission, $rbac);
    }

    //  List all users/admins with filter
    public static function getUsersByType($type)
    {
        return self::find()->where(['user_type' => $type])->andWhere(['status' => 1])->all();
    }

    public static function getUserDataProvider($type, $searchKeyword = null)
    {
        $query = self::find()->where(['user_type' => $type, 'status' => 1]);

        if ($searchKeyword) {
            $query->andFilterWhere(['like', 'username', $searchKeyword]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);
    }

    //  Soft delete user/admin
    public function softDelete()
    {
        $this->status = 0;
        $this->username .= ' [Deleted]';
        return $this->save(false);
    }
}
