<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// require_once 'BaseElement.php';

class Job extends Model
{
    protected $table = 'jobs';
    // public function __construct($title, $description)
    // {
    //     $newTitle = 'Job: ' . $title;
    //     $this->title = $newTitle;
    // }

    public function getDurationAsString()
    {
        $years = floor($this->months / 12);
        $extraMonths = $this->months % 12;

        return "Job duration: $years years $extraMonths months";
    }
}