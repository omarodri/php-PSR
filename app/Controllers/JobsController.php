<?php
namespace App\Controllers;

use App\Models\Job;
use Respect\Validation\Validator as v;

class JobsController extends BaseController {
    public function getAddJobAction($request){

        $responseMessage = '';

        if ($request->getMethod() == 'POST') {

            $jobValidator= v::key('title', v::stringType()->notEmpty())
            ->key('description',  v::stringType()->notEmpty());
            
            // $jobValidator->validate($postData); // true  
            try{
                $postData = $request->getParsedBody();
                $jobValidator->assert($postData);  

                $files = $request->getUploadedFiles();
                $logo = $files['logo'];

                if($logo->getError() == UPLOAD_ERR_OK){
                    $fileName = $logo->getClientFilename();
                    $logo->moveTo("uploads/$fileName");
                }
                else $fileName = '';
                
                $job = new Job();
                $job->title = $postData['title'];
                $job->description = $postData['description'];
                $job->logo = $fileName;
                $job->save();       
                
                $responseMessage = 'Saved';
            } catch (\Exception $e){
                $responseMessage =$e->getMessage();
            }
        }
        
        return $this->renderHTML('addJob.twig',[
                    'responseMessage'=>$responseMessage
        ]);    }
}