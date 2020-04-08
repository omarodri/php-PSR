<?php
namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator as v;

class AuthController extends BaseController {

    public function getLogin($request){
        return $this->renderHTML('login.twig');    
    }

    public function postLogin($request){
        $postData = $request->getParsedBody();
        $responseMessage = null;
        
        if ($request->getMethod() == 'POST') {

            $userValidator= v::key('email', v::stringType()->notEmpty())
                            ->key('password',  v::stringType()->notEmpty());
            try{
                $user = User::where('email', $postData['email'])->first();

                if ($user){
                    if(\password_verify($postData['password'], $user->password)){
                        echo 'correct';
                    }
                    else {
                            $responseMessage = "Wrong User Name or password. Try again.";                        
                    }
                }
                else {
                    $responseMessage = "Please enter a valid user credentials.";                     
                }
            } catch (\Exception $e){
                $responseMessage =  $e->getMessage();
            }
        
            return $this->renderHTML('login.twig',[
                'responseMessage'=>$responseMessage
            ]);    
        }
    }
}