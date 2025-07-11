<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

class User extends \yii\db\ActiveRecord implements IdentityInterface
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
            [['dob'], 'safe'],
            [['username', 'fullname', 'email', 'password'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 150],
            [['district', 'state', 'rbac'], 'string', 'max' => 30],
            [['gender'], 'string', 'max' => 10],
            // [['email'], 'unique', 'message' => 'This email is already registered.']
        ];
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
            'rbac' => 'RBAC',
            'status' => 'Status',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['user'] = ['username', 'fullname', 'email', 'phone', 'dob', 'address', 'district', 'pincode', 'state', 'gender', 'password'];
        $scenarios['admin'] = ['username', 'password']; 
        return $scenarios;
    }

    public function validatePassword($password)
    {
        return $this->password === $password; 
    }


    public function generateOtp()
    {
        $this->otp = random_int(1000, 9999); 
        $this->otp_expiry = time() + 60; 
    }

    // ✅ IdentityInterface methods
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // Not used in this app
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return ''; // Optional
    }

    public function validateAuthKey($authKey)
    {
        return true; // Optional
    }
}
