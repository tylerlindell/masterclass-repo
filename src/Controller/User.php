<?php

namespace Masterclass\Controller;

use PDO;
use Masterclass\Model\User as UserModel;

class User {

    /**
     * @var UserModel
     */
    protected $userModel;
    
    /**
     * @param array $config
     */
    public function __construct(UserModel $user) {
        $this->userModel = $user;
    }
    
    /**
     * Create a new user account
     * @return void
     */
    public function create() {
        $error = null;
        
        // Do the create
        if(isset($_POST['create'])) {
            if(empty($_POST['username']) || empty($_POST['email']) ||
               empty($_POST['password']) || empty($_POST['password_check'])) {
                $error = 'You did not fill in all required fields.';
            }
            
            if(is_null($error)) {
                if(!filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
                    $error = 'Your email address is invalid';
                }
            }
            
            if(is_null($error)) {
                if($_POST['password'] != $_POST['password_check']) {
                    $error = "Your passwords didn't match.";
                }
            }
            
            if(is_null($error)) {
                $check = $this->userModel->check($_POST['username']);

                if($check)
                    $error = 'Your chosen username already exists. Please choose another.';
               
            }
            
            if(is_null($error)) {
                $params = array(
                    $_POST['username'],
                    $_POST['email'],
                    md5($_POST['username'] . $_POST['password']),
                );
            
                if($this->userModel->addNewUser($params)){
                    header("Location: /user/login");
                    exit;
                }
            }
        }
        // Show the create form
        
        $content = '
            <form method="post" action="/user/account/create">
                ' . $error . '<br />
                <label>Username</label> <input type="text" name="username" value="" /><br />
                <label>Email</label> <input type="text" name="email" value="" /><br />
                <label>Password</label> <input type="password" name="password" value="" /><br />
                <label>Password Again</label> <input type="password" name="password_check" value="" /><br />
                <input type="submit" name="create" value="Create User" />
            </form>
        ';
        
        require_once '../layout.phtml';
        
    }
    
    /**
     * Show account details
     * @return void
     */
    public function account() {
        $error = null;
        if(!isset($_SESSION['AUTHENTICATED'])) {
            header("Location: /user/login");
            exit;
        }
        
        if(isset($_POST['updatepw'])) {
            if(!isset($_POST['password']) || !isset($_POST['password_check']) ||
               $_POST['password'] != $_POST['password_check']) {
                $error = 'The password fields were blank or they did not match. Please try again.';       
            }
            else {
                if($this->userModel->updatepw($_SESSION['username'], $_POST['password'], $_SESSION['username']))
                    $error = 'Your password was changed.';
            }
        }
        
        $details = $this->userModel->getUserDetails($_SESSION['username']);
        
        $content = '
        ' . $error . '<br />
        
        <label>Username:</label> ' . $details['username'] . '<br />
        <label>Email:</label>' . $details['email'] . ' <br />
        
         <form method="post" action="/user/account/save">
                <br />
            <label>Password</label> <input type="password" name="password" value="" /><br />
            <label>Password Again</label> <input type="password" name="password_check" value="" /><br />
            <input type="submit" name="updatepw" value="Create User" />
        </form>';
        
        require_once '../layout.phtml';
    }
    
    /**
     * Log into user account
     * @return void
     */
    public function login() {
        $error = null;
        // Do the login
        if(isset($_POST['login'])) {
            if($login = $this->userModel->login($_POST['user'], $_POST['pass'])){ 
                session_regenerate_id();
                $_SESSION['username'] = $login['username'];
                $_SESSION['AUTHENTICATED'] = true;
                header("Location: /");
                exit;
            }
            else {
                $error = 'Your username/password did not match.';
            }
        }
        
        $content = '
            <form method="post" action="/user/login/check">
                ' . $error . '<br />
                <label>Username</label> <input type="text" name="user" value="" />
                <label>Password</label> <input type="password" name="pass" value="" />
                <input type="submit" name="login" value="Log In" />
            </form>
        ';
        
        require_once '../layout.phtml';
        
    }
    
    /**
     * logout of user account
     * @return void
     */
    public function logout() {
        // Log out, redirect
        session_destroy();
        header("Location: /");
    }
}