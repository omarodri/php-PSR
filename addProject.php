<?php
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Project;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'cursophp',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

if (!empty($_POST)) {
    $job = new Project();
    $job->title = $_POST['title'];
    $job->description = $_POST['description'];
    $job->save();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css">
    <title>Add Project</title>
</head>

<body>
    <h1>Add Project</h1>
    <form action="addProject.php" method="post">
        <label for="title">Project Name:</label>
        <input type="text" name="title">
        <label for="description">Description:</label>
        <input type="text" name="description"><br>
        <button type="submit">Save</button>
    </form>
</body>

</html>