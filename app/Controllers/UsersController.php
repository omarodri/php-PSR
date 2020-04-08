<?php
namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator as v;

class UsersController extends BaseController {

    public function getAddUserAction($request){

        $responseMessage = '';

        if ($request->getMethod() == 'POST') {

            $userValidator= v::key('email', v::stringType()->notEmpty())
                            ->key('password',  v::stringType()->notEmpty());
            
            // $jobValidator->validate($postData); // true  
            try{
                $postData = $request->getParsedBody();
                $userValidator->assert($postData);  

                $user = new User();
                $user->email = $postData['email'];
                $user->password = password_hash($postData['password'],PASSWORD_DEFAULT);
                $user->save();       
                
                $responseMessage = 'User Saved successfuly';
            } catch (\Exception $e){
                $responseMessage =$e->getMessage();
            }
        }
        
        return $this->renderHTML('addUser.twig',[
                    'responseMessage'=>$responseMessage,
                    'admin' => 1
        ]);    
    }
}