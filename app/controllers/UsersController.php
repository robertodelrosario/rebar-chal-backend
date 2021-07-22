<?php
declare(strict_types=1);

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
//use Phalcon\Http\Request;
use Phalcon\Helper\Arr;

class UsersController extends Controller
{
    public function indexAction()
    {
//        $response = new Response();
//        else{
//            return $response->setContent(json_encode(['message' => "unsuccessful", 'status' => true]));
//        }
//        return $response->setContent(json_encode(['message' => "successful", 'status' => true]));
    }

    public function loginAction(){

        $response = new Response();
        $rawData = $this->request->getJsonRawBody(true);
        foreach ($rawData as $key => $value){
            $_POST[$key] = $value;
        }
        $user = Users::findFirst([
           'conditions' => 'user_name = :user_name: and password = :password:',
           'bind' => [
               'user_name' => $this->request->getPost('user_name'),
               'password' => md5($this->request->getPost('password'))
           ]
        ]);
        if($user){
            $this->session->set("AUTH_ID", $user->id);
            if($this->session->has('AUTH_ID')) return $response->setContent(json_encode(['message' => "successful", 'status' => true, 'user_id' => $user->id]));
            else return $response->setContent(json_encode(['message' => "unsuccessful", 'status' => false]));
        }
        else{

        }return $response->setContent(json_encode(['message' => "Unauthorized", 'status' => false, 'user_id' => $user->id]));

    }

    public function logoutAction(){
        $response = new Response();
        $this->session->destroy();
        if(!($this->session->has('AUTH_ID'))) return $response->setContent(json_encode(['message' => "successful", 'status' => true]));
        else return $response->setContent(json_encode(['message' => "unsuccessful", 'status' => false]));
    }

    public function registerAction(){
        $user = new Users();

        $rawData = $this->request->getJsonRawBody(true);
        foreach ($rawData as $key => $value){
            $_POST[$key] = $value;
        }
        $messages = $user->Validation();

        $special_chars = preg_match('/[|!@#$%&*\\/=?,;.:\\-_+~^\\\\]/', $this->request->getPost('user_name'));
        if($special_chars <= 0 ) {
            array_push($messages, 'Username must contain special character');
        }
        $alphanumeric = preg_match('/[A-Za-z0-9-_.]/', $this->request->getPost('user_name'));
        if($alphanumeric <= 0 ) {
            array_push($messages, 'Username must contain alphanumeric');
        }

        $special_chars = preg_match('/[|!@#$%&*\\/=?,;.:\\-_+~^\\\\]/', $this->request->getPost('password'));
        if($special_chars <= 0 ) {
            array_push($messages, 'Password must contain special character');
        }
        $number = preg_match('/[0-9]/', $this->request->getPost('password'));
        if($number <= 0 ) {
            array_push($messages, 'Password must contain number');
        }
        $upper_case = preg_match('/[A-Z-_.]/', $this->request->getPost('password'));
        if($upper_case <= 0 ) {
            array_push($messages, 'Password must contain upper case');
        }
        $lower_case = preg_match('/[a-z]/', $this->request->getPost('password'));
        if($lower_case <= 0 ) {
            array_push($messages, 'Password must contain lower case');
        }

        if(count($messages) > 0){
            $response = new Response();
            return $response->setContent(json_encode(['message' => $messages, 'status' => false]));
        }

        $user->setFirstName($this->request->getPost('first_name'));
        $user->setLastName($this->request->getPost('last_name'));
        $user->setMiddleName($this->request->getPost('middle_name'));
        $user->setCivilStatus($this->request->getPost('civil_status'));
        $user->setCity($this->request->getPost('city'));
        $user->setZipCode($this->request->getPost('zip_code'));
        $user->setAddress($this->request->getPost('address'));
        $user->setGender($this->request->getPost('gender'));
        $date = explode("T", $this->request->getPost('date_of_birth'));
        $user->setDateofBirth($date[0]);
        $user->setUsername($this->request->getPost('user_name'));
//        $user->setPassword($this->security->hash($this->request->getPost('password')));
        $user->setPassword(md5($this->request->getPost('password')));
//        $user->setProfilePicture($this->request->getPost('profile_picture'));
        $user->setProfilePicture("none");
        $success = $user->save();
        $response = new Response();
        if($success){
            $contact = new ContactsUsers();
            $contact->user_id = $user->id;
            $contact->contact = $this->request->getPost('contact_number');
            $contact->save();
            $this->session->set("AUTH_ID", $user->id);
            return $response->setContent(json_encode(['message' => "successful", 'status' => true, 'user_id' => $user->id]));
        }
        else return $response->setContent(json_encode(['message' => "unsuccessful", 'status' => false]));
    }

    public function profileAction(){
        $response = new Response();
        if($this->session->has('AUTH_ID')){
            $user = Users::findFirst([
                'conditions' => 'id = :id:',
                'bind' => [
                    'id' => $this->session->get('AUTH_ID'),
                ]
            ]);
            $contacts = ContactsUsers::find([
                'conditions' => 'user_id = :id:',
                'bind' => [
                    'id' => $this->session->get('AUTH_ID'),
                ]
            ]);
//            $path = "C:\laragon\www\rebar_challenge\public\".$user->profile_picture;
            return $response->setContent(json_encode(['message' => "successful", 'status' => true, 'profile' => $user,'contacts' => $contacts]));
        }
        else return $response->setContent(json_encode(['message' => "logout", 'status' => false]));
    }

    public function editprofileAction($id){
        $response = new Response();
        if($this->session->has('AUTH_ID')){

            $rawData = $this->request->getJsonRawBody(true);
            foreach ($rawData as $key => $value){
                $_POST[$key] = $value;
            }

            $user = Users::findFirst([
                'conditions' => 'id = :id:',
                'bind' => [
                    'id' => $id,
                ]
            ]);
            if(!(is_null($this->request->getPost('first_name'))))$user->setFirstName($this->request->getPost('first_name'));
            if(!(is_null($this->request->getPost('last_name'))))$user->setLastName($this->request->getPost('last_name'));
            $user->setMiddleName($this->request->getPost('middle_name'));
            if(!(is_null($this->request->getPost('civil_status'))))$user->setCivilStatus($this->request->getPost('civil_status'));
            if(!(is_null($this->request->getPost('city'))))$user->setCity($this->request->getPost('city'));
            if(!(is_null($this->request->getPost('zip_code'))))$user->setZipCode($this->request->getPost('zip_code'));
            if(!(is_null($this->request->getPost('address'))))$user->setAddress($this->request->getPost('address'));
            if(!(is_null($this->request->getPost('gender'))))$user->setGender($this->request->getPost('gender'));
            if(!(is_null($this->request->getPost('date_of_birth')))) {
                $date = explode("T", $this->request->getPost('date_of_birth'));
                $user->setDateofBirth($date[0]);
            }
            $success = $user->save();
            $response = new Response();
            if($success) return $response->setContent(json_encode(['message' => "Successfully edited profile", 'status' => true]));
            else return $response->setContent(json_encode(['message' => "Unuccessfully edited profile", 'status' => false]));
        }
        else return $response->setContent(json_encode(['message' => "logout", 'status' => false]));
    }

    public function addcontactAction(){
        $response = new Response();
        if($this->session->has('AUTH_ID')){
            $rawData = $this->request->getJsonRawBody(true);
            foreach ($rawData as $key => $value){
                $_POST[$key] = $value;
            }
            $contact = new ContactsUsers();
            $contact->user_id = $this->session->get('AUTH_ID');
            $contact->contact = $this->request->getPost('contact_number');
            $success = $contact->save();
            if($success) return $response->setContent(json_encode(['message' => "successful", 'status' => true]));
            else return $response->setContent(json_encode(['message' => "Unsuccessful", 'status' => false]));
        }
        else return $response->setContent(json_encode(['message' => "logout", 'status' => false]));
    }

    public function editcontactAction($id){
        $response = new Response();
        if($this->session->has('AUTH_ID')){
            $rawData = $this->request->getJsonRawBody(true);
            foreach ($rawData as $key => $value){
                $_POST[$key] = $value;
            }
            $contact = ContactsUsers::findFirst([
                'conditions' => 'id = :id:',
                'bind' => [
                    'id' => $id,
                ]
            ]);
            $contact->contact = $this->request->getPost('contact_number');
            $contact->save();
            return $response->setContent(json_encode(['message' => "successful", 'status' => true]));
        }
        else return $response->setContent(json_encode(['message' => "logout", 'status' => false]));
    }

    public function deletecontactAction($id){
        $response = new Response();
        if($this->session->has('AUTH_ID')){

            $contacts = ContactsUsers::find([
                'conditions' => 'user_id = :id:',
                'bind' => [
                    'id' => $this->session->get('AUTH_ID'),
                ]
            ]);
            if(count($contacts) <= 1) return $response->setContent(json_encode(['message' => "Unable to delete. User must contain at least one number!", 'status' => false]));
            $contact = ContactsUsers::findFirst([
                'conditions' => 'id = :id:',
                'bind' => [
                    'id' => $id,
                ]
            ]);
            $success = $contact->delete();
            if($success) return $response->setContent(json_encode(['message' => "successful", 'status' => true]));
            else return $response->setContent(json_encode(['message' => "unsuccessful", 'status' => false]));
        }
        else return $response->setContent(json_encode(['message' => "logout", 'status' => false]));
    }

    public function uploaddpAction($id){
        $response = new Response();
        if($this->session->has('AUTH_ID')){
            $user = Users::findFirst([
                'conditions' => 'id = :id:',
                'bind' => [
                    'id' => $id,
                ]
            ]);
            if($this->request->hasFiles() == true){
                $uploads = $this->request->getUploadedFiles();
                $isUploaded = false;
                foreach ($uploads as $upload){
                    $path = 'img/'. uniqid('', true).'-'.strtolower($upload->getname());
                    ($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
                }
                if($isUploaded){
                    $user->setProfilePicture($path);
                    $user->save();
                    return $response->setContent(json_encode(['message' => "successful", 'status' => true]));
                }
                else return $response->setContent(json_encode(['message' => "unsuccessful", 'status' => false]));
            }
            return $response->setContent(json_encode(['message' => "no file", 'status' => false]));
        }
        else return $response->setContent(json_encode(['message' => "logout", 'status' => false]));
    }

    public function removedpAction($id){
        $response = new Response();
        if($this->session->has('AUTH_ID')){
            $user = Users::findFirst([
                'conditions' => 'id = :id:',
                'bind' => [
                    'id' => $id,
                ]
            ]);
            $user->profile_picture='img/profile-picture.png';
            $user->save();
            return $response->setContent(json_encode(['message' => "successful", 'status' => true]));
        }
        else return $response->setContent(json_encode(['message' => "logout", 'status' => false]));
    }
}

