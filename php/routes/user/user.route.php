<?php

class UserRoute extends ApiLibrary
{

    public function register()
    {
        $this->response($this->userRegister(in()));
    }

    public function login()
    {
        $this->response($this->userLogin(in()));
    }
    public function socialLogin() {
    	$this->response($this->userSocialLogin(in()));
    }
    public function update()
    {
        $this->response($this->userUpdate(in()));
    }
    public function resign()
    {
        $this->response($this->userResign(in()));
    }
    public function sendPhoneVerificationCode()
    {
        $this->response($this->userSendPhoneVerificationCode(in()));
    }
    public function verifyPhoneVerificationCode()
    {
        $this->response($this->userVerifyPhoneVerificationCode(in()));
    }


}

