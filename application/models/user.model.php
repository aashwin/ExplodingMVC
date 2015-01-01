<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: user.model.php
 * Date: 17/11/14
 * Time: 12:28
 */

class userModel extends Model {
    private $userData=array();
    public function __construct($db=null){
        parent::__construct($db);
        $this->loginSession();
    }
    public function logout(){
        if(!$this->isLoggedIn()) return false;
        $this->userData=null;
        unset($_SESSION['user_logged_in']);
        unset($_SESSION['user_logged_id']);
        unset($_SESSION['user_logged_name']);
        setcookie('login_token', false, time() - Functions::getTimeAsInt(3), '/', WEB_DOMAIN);
        return true;
    }
    public function loginAfterSignup($userData){
        $this->updateLastLogin($userData['userId']);
        $_SESSION['user_logged_in']=1;
        $_SESSION['user_logged_id']=$userData['userId'];
        $_SESSION['user_logged_name']=$userData['username'];
        $this->setLoginToken($userData['userId']);
    }
    /**
     * Login the user after verification
     * @param string $username Can either be username or email for login
     * @param string $password Raw Password
     * @param bool $rememberMe (optional) Remember the user using cookie or not.
     */
    public function login($username, $password, $rememberMe=true)
    {
        $username = trim($username);
        $rememberMe = intval($rememberMe);
        if (empty($username)) {
            $this->addErrors('Username cannot be empty');
            return false;
        }
        if (empty($password)) {
            $this->addErrors('Password cannot be empty');
            return false;
        }
        $userData = $this->getUserData($username);
        if ($userData === false){
            $this->addErrors('Username/Email does\'nt exist in database');
            return false;
        }
        if ($userData['activation_key'] != NULL){
            $this->addErrors('Please Activate your account');
            return false;
        }
        //Check if user has attempted login 5 times (and failed).
        if($userData['userFailedLogins']>=5){
            //If attempted 5 times, then check if last failed login time is 5 minutes ago

            if(time()-$userData['userFailedTime']<300){
                $this->addErrors('Too many failed attempts, wait 5 minutes');
                return false;
            }else{
                $this->updateLastFailed($userData['userId'], true);
                $userData['userFailedLogins']=0;
                $userData['userFailedTime']=0;
            }
        }
        if(!password_verify($password, $userData['userPassword'])){
            $this->addErrors('Invalid Password... Try again!');
            $this->updateLastFailed($userData['userId']);
            return false;
        }
        if($userData['userFailedLogins']>0)
            $this->updateLastFailed($userData['userId'], true);

        $this->updateLastLogin($userData['userId']);
        $_SESSION['user_logged_in']=1;
        $_SESSION['user_logged_id']=$userData['userId'];
        $_SESSION['user_logged_name']=$userData['username'];
        $this->userData=$userData;
        if($rememberMe){
            $this->setLoginToken($userData['userId']);
        }else{
            $this->updateRememberMe($userData['userId'],NULL);
            setcookie('login_token', false, time() - Functions::getTimeAsInt(3), '/', WEB_DOMAIN);
        }
        if(password_needs_rehash($userData['userPassword'], PASSWORD_DEFAULT, array('cost'=>HASH_COST))){
            $password_hash=password_hash($password, PASSWORD_DEFAULT, array('cost'=>HASH_COST));
            $this->updatePasswordHash($userData['userId'], $password_hash);
        }
        return true;
    }
    public function isLoggedIn(){
        if($this->userData==null) return false;
        if(isset($_SESSION['user_logged_in'])){
            return true;
        }
    }
    public function loggedInUserData($column=NULL){
        if($column===NULL)
            return $this->userData;
        return $this->userData[$column];
    }
    public function isLoggedInAdmin(){
        if($this->userData['userLevel']==1)
            return true;
        return false;
    }
    public function loginSession(){
        if($_SESSION['user_logged_in']==1 && !empty($_SESSION['user_logged_name']) && intval($_SESSION['user_logged_id'])!=0) {
            $this->userData=$this->getUserDataFromID(intval($_SESSION['user_logged_id']));
            return true;
        }else if(isset($_COOKIE['login_token'])){
            list($userId, $token, $hash)=explode('_', $_COOKIE['login_token']);
            $rehash=hash('sha256', COOKIE_SALT.$userId.'_'.$token);
            if($hash==$rehash){
                $user=$this->getUserDataFromID($userId);
                if($user['userLoginToken']!==$hash){
                    setcookie('login_token', false, time() - Functions::getTimeAsInt(3), '/', WEB_DOMAIN);
                    return false;
                }
                $this->setLoginToken($userId);

                $_SESSION['user_logged_in']=1;
                $_SESSION['user_logged_name']=$user['username'];
                $_SESSION['user_logged_id']=$user['userId'];
                $this->userData=$user;
                return true;
            }
        }
        $this->userData=null;
        return false;
    }

    private function setLoginToken($userId){
        $token=md5(mt_rand());
        $token=$userId.'_'.$token;
        $this->updateRememberMe($userId,hash('sha256', COOKIE_SALT.$token));
        $token.='_'.hash('sha256', COOKIE_SALT.$token);
        setcookie('login_token', $token, time() + Functions::getTimeAsInt(3), '/', WEB_DOMAIN);
    }
    /**
     *
     * Creates a New User
     *
     * @param string $username  The new username of the user to be created
     * @param string $password  The password of the user to be created, will be hashed using hash_password before storing in DB.
     * @param string $passwordRepeat The password repeated.
     * @param string $email  The email of the user, needs to be a valid email and non-existent in db
     *
     * @return bool|int Returns FALSE on fail or User ID on Success
     */
    public function create($username, $password, $passwordRepeat, $email){
        $username=trim($username);
        $email=trim($email);


        if(!$this->dataValidation($username, $password, $passwordRepeat, $email))
            return false;

        $password_hash = password_hash($password, PASSWORD_DEFAULT, array('cost' => HASH_COST));
        $userIP=$userIp=Functions::getIP(true);
        $timeNow=time();
        $generateCode=md5(uniqid(rand(), true));
        $queryInsert=$this->getDB()->prepare('INSERT INTO user_login
                                              (username, userEmail, userPassword, userRegIP, userRegTime, activation_key)
                                              VALUES
                                              (:username, :email, :password, :ip, :regTime, :key)');
        $queryInsert->bindValue(':username',$username);
        $queryInsert->bindValue(':email',$email);
        $queryInsert->bindValue(':password',$password_hash);
        $queryInsert->bindValue(':ip',$userIP);
        $queryInsert->bindValue(':regTime',$timeNow);
        $queryInsert->bindValue(':key',$generateCode);
        $queryInsert->execute();
        return $this->getDB()->lastInsertId();


    }

    /**
     * Check if a username or email exists in the database
     * @param $username
     * @param $email
     * @return bool Returns whether the username or email exists in the database
     */
    private function checkUserExists($username, $email){
        $queryValidate=$this->getDB()->prepare('SELECT userId FROM user_login WHERE username=:username OR userEmail=:email');
        $queryValidate->bindValue(':username', $username, PDO::PARAM_STR);
        $queryValidate->bindValue(':email', $email, PDO::PARAM_STR);
        $queryValidate->execute();
        if($queryValidate->rowCount()>0)
            return true;
        return false;
    }


    /**
     * @param string $username Username of user to get details of
     * @return mixed FALSE on failure or ASSOC Array with User Details
     */
    private function getUserData($username){
        $queryUser=$this->getDB()->prepare('SELECT * FROM user_login WHERE username=:username OR userEmail=:email LIMIT 1');
        $queryUser->bindValue(':username', $username);
        $queryUser->bindValue(':email', $username);
        $queryUser->execute();
        if($queryUser->rowCount()>0) {
            $fetched=$queryUser->fetch();
            if($fetched['profileImage']!=NULL) {
                $fetched['profileImageUrl'] = WWW_PROFILE_IMG . $fetched['profileImage'];
            }else{
                $fetched['profileImageUrl'] = WWW_PUBLIC.'/images/noprofile.jpg';
            }
            return $fetched;
        }
        return false;
    }
    /**
     * @param int $userId ID of user to get details of
     * @return mixed FALSE on failure or ASSOC Array with User Details
     */
    public function getUserDataFromID($userId){
        $queryUser=$this->getDB()->prepare('SELECT * FROM user_login WHERE userId=:id LIMIT 1');
        $queryUser->bindValue(':id', $userId);
        $queryUser->execute();
        if($queryUser->rowCount()>0) {
            $fetched=$queryUser->fetch();
            if($fetched['profileImage']!=NULL) {
                $fetched['profileImageUrl'] = WWW_PROFILE_IMG . $fetched['profileImage'];
            }else{
                $fetched['profileImageUrl'] = WWW_PUBLIC.'/images/noprofile.jpg';
            }
            return $fetched;
        }
        return false;
    }
    /**
     * @param int $userId The User ID for the user we are updating
     * @param bool $reset Reset the last failed or increment it
     */
    private function updateLastFailed($userId, $reset=false){
        if($reset){
            $set='userFailedLogins=0,userFailedTime=0';
        }else{
            $set='userFailedLogins=userFailedLogins+1,userFailedTime='.time();
        }
        $queryUser=$this->getDB()->prepare("UPDATE user_login SET $set WHERE userId=:id LIMIT 1");
        $queryUser->bindValue(':id', $userId, PDO::PARAM_INT);
        $queryUser->execute();
    }
    /**
     * @param int $userId The User ID for the user we are updating
     */
    private function updateLastLogin($userId){

        $queryUser=$this->getDB()->prepare("UPDATE user_login SET userLastLogin=:time WHERE userId=:id LIMIT 1");
        $queryUser->execute(array(':id'=>$userId,':time'=>time()));
    }
    /**
     * @param int $userId The User ID for the user we are activating
     */
    public function activateAccount($userId){

        $queryUser=$this->getDB()->prepare("UPDATE user_login SET activation_key=NULL WHERE userId=:id LIMIT 1");
        $queryUser->execute(array(':id'=>$userId));
    }
    /**
     * @param int $userId The User ID for the user we are updating
     * @param string $password_hash
     */
    private function updatePasswordHash($userId, $password_hash){

        $queryUser=$this->getDB()->prepare("UPDATE user_login SET userPassword=:password WHERE userId=:id LIMIT 1");
        $queryUser->bindValue(':id', $userId, PDO::PARAM_INT);
        $queryUser->bindValue(':password', $password_hash);
        $queryUser->execute();
    }

    /**
     * @param int $userId The User ID for the user we are updating
     * @param string $token Token to be updated
     */
    private function updateRememberMe($userId, $token){

        $queryUser=$this->getDB()->prepare("UPDATE user_login SET userLoginToken=:token WHERE userId=:id LIMIT 1");
        $queryUser->execute(array(':id'=>$userId,':token'=>$token));
    }
    public function getUsers($start=NULL, $limitby=NULL,$order='username', $by='ASC', $filterField='', $filterVal=''){
        $limit='';
        if($start!==NULL && $limitby!==NULL){
            $start=intval($start);
            $limitby=intval($limitby);
            $limit="LIMIT $start, $limitby";
        }
        $by=strtoupper($by);
        if($by!='ASC' && $by!='DESC') return false;
        $orderAllowed=array("userId","username","userEmail",'userLevel');
        if(!in_array($order, $orderAllowed)) return false;
        if(!in_array($filterField, $orderAllowed) && $filterField!='') return false;
        $where='';
        if($filterField!='' && $filterVal!=''){
            $where="WHERE $filterField LIKE :value";
        }
        $query=$this->getDB()->prepare("SELECT * FROM user_login $where ORDER BY $order $by $limit");

        if($filterField!='' && $filterVal!=''){
            $query->bindValue(':value', '%'.$filterVal.'%');
        }
        if($query->execute()){
            return $query;
        }
        return false;
    }
    public function getUser($id,$field='*'){
        $query=$this->getDB()->prepare("SELECT $field FROM user_login WHERE userId=:id LIMIT 1");

        if($query->execute(array(':id'=>$id))){

            if($field=='*'){
                return $query->fetch();
            }
            $fetch=$query->fetch();
            return $fetch[$field];
        }
        return false;
    }
    public function dataValidation($username, $password, $passwordRepeat, $email, $update=false){

        if(empty($username))
            $this->addErrors('Username should not be empty');
        if(strlen($username)>35)
            $this->addErrors('Username should be less than 35 characters');
        elseif(strlen($username)<=3)
            $this->addErrors('Username should be more than 3 characters');
        if(!preg_match('/^[a-z0-9_]+$/i', $username))
            $this->addErrors('Invalid Username Format, please use only alphanumeric and underscores');
        if(empty($email))
            $this->addErrors('Email should not be empty');
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            $this->addErrors('Invalid Email, please try again');
        if($this->checkUserExists($username, $email)  && !$update)
            $this->addErrors('Username or Email already exists in the database');
        if(empty($password) && !$update)
            $this->addErrors('Password should not be empty');
        if(strlen($password)<=6 && !$update)
            $this->addErrors('Password is too short, have atleast 6 characters');
        if($password!==$passwordRepeat  && !$update)
            $this->addErrors('Passwords don\'t match');
        if($this->getDB()==NULL){
            $this->addErrors('Unfortunately we are experiencing some issues, try again later');
        }


        if($this->numErrors()>0)
            return false;
        return true;

    }
    public function update($id, $username, $password, $email, $userLevel=0){
        $username=trim($username);
        $email=trim($email);
        $userLevel=intval($userLevel);

        if($this->getUser($id)===false)
        {
            $this->addErrors('Invalid User ID');
            return false;
        }

        if(!$this->dataValidation($username, $password, $password, $email, true))
            return false;
        $updatePassword='';
        $password_hash='';
        if($password!=''){
            $password_hash = password_hash($password, PASSWORD_DEFAULT, array('cost' => HASH_COST));
            $updatePassword=', userPassword=:password';
        }

        $queryInsert=$this->getDB()->prepare("UPDATE user_login SET username=:name, userLevel=:level, userEmail=:email $updatePassword WHERE userId=:id LIMIT 1");
        $queryInsert->bindValue(':name',$username);
        $queryInsert->bindValue(':level',$userLevel);
        $queryInsert->bindValue(':email',$email);
        if($password!=''){
             $queryInsert->bindValue(':password',$password_hash);
        }
        $queryInsert->bindValue(':id',$id, PDO::PARAM_INT);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('No Data Updated!');
        return false;
    }
    public function delete($id){
        $query=$this->getDB()->prepare('DELETE FROM user_login WHERE userId=:id LIMIT 1');
        if($query->execute(array(':id'=>$id))){
            return true;
        }
        return false;

    }

} 