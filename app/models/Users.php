<?php

use Phalcon\Mvc\Model;
use \Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
class Users extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $password;
    public function setPassword($password){
        $this->password = $password;
        return $this;
    }
    /**
     *
     * @var string
     */
    protected $first_name;

    public function setFirstName($name){
        $this->first_name = $name;
        return $this;
    }
    /**
     *
     * @var string
     */
    protected $last_name;
    public function setLastName($name){
        $this->last_name = $name;
        return $this;
    }
    /**
     *
     * @var string
     */
    protected $middle_name;
    public function setMiddleName($name){
        $this->middle_name = $name;
        return $this;
    }
    /**
     *
     * @var string
     */
    protected $date_of_birth;
    public function setDateofBirth($date){
        $this->date_of_birth = $date;
        return $this;
    }
    /**
     *
     * @var string
     */
    protected $address;
    public function setAddress($address){
        $this->address = $address;
        return $this;
    }
    /**
     *
     * @var string
     */
    protected $zip_code;
    public function setZipCode($zip){
        $this->zip_code = $zip;
        return $this;
    }
    /**
     *
     * @var string
     */
    protected $city;
    public function setCity($city){
        $this->city = $city;
        return $this;
    }
    /**
     *
     * @var string
     */
    protected $gender;
    public function setGender($gender){
        $this->gender = $gender;
        return $this;
    }
    /**
     *
     * @var string
     */
    protected $civil_status;
    public function setCivilStatus($civil_status){
        $this->civil_status = $civil_status;
        return $this;
    }
    /**
     *
     * @var string
     */
    protected $user_name;
    public function setUsername($username){
        $this->user_name = $username;
        return $this;
    }
    /**
     *
     * @var string
     */
    public $profile_picture;
    public function setProfilePicture($profile_picture){
        $this->profile_picture = $profile_picture;
        return $this;
    }
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("rebar_challenge");
        $this->setSource("users");
        $this->hasMany('id', 'ContactsUsers', 'user_id', ['alias' => 'ContactsUsers']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

    public function Validation(){
        $message_array = [];
        $validation = new Validation();
        $validation->add(
            'first_name',
            new PresenceOf(
                [
                    'message' => 'First name is required',
                ]
            )
        );

        $validation->add(
            'last_name',
            new PresenceOf([
                'message' => 'Last name is required',
            ])
        );

        $validation->add(
            'date_of_birth',
            new PresenceOf([
                'message' => 'Date of birth is required',
            ])
        );

        $validation->add(
            'address',
            new PresenceOf([
                'message' => 'Address is required',
            ])
        );

        $validation->add(
            'city',
            new PresenceOf([
                'message' => 'City is required',
            ])
        );
        $validation->add(
            'zip_code',
            new PresenceOf([
                'message' => 'Zip code is required',
            ])
        );

        $validation->add(
            'gender',
            new PresenceOf([
                'message' => 'Gender is required',
            ])
        );

        $validation->add(
            'contact_number',
            new PresenceOf([
                'message' => 'Contact number is required',
            ])
        );

        $validation->add(
            'civil_status',
            new PresenceOf([
                'message' => 'Civil Status is required',
            ])
        );

        $validation->add(
            'user_name',
            new PresenceOf([
                'message' => 'Username is required',
            ])
        );

        $validation->add(
            'password',
            new PresenceOf([
                'message' => 'Password is required',
            ])
        );

        $messages = $validation->validate($_POST);

        foreach ($messages as $message){
            array_push($message_array, $message->getMessage());
        }
        return $message_array;
    }

}
