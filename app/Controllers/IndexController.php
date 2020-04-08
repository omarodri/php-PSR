<?php
namespace App\Controllers;

use App\Models\{Job, Project};

class IndexController extends BaseController {
    public function indexAction(){
        $jobs = Job::all();
        $projects = Project::all();
        $name = 'Omar Rodriguez M';
        $limitMonths = 500;

        return $this->renderHTML('index.twig', [
            'name' => $name,
            'jobs' => $jobs
        ]);
        // include '../views/index.twig';

    }
}