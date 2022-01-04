<?php
echo '<pre>';


echo '<br>--------------------------------<br>';
echo 'Deploying on dev...';
echo '<br>--------------------------------<br>';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes


$cmd = array();
$result1 = '';

$cmd[] = 'cd '.__DIR__.'/../';
$cmd[] = 'git fetch --all';
$cmd[] = 'git reset --hard origin/dev';
$cmd[] = 'sudo php composer.phar install 2>&1';
$cmd[] = 'sudo chown -R ec2-user:ec2-user ./';
$cmd[] = 'chmod -R 0777 ./bootstrap';
$cmd[] = 'chmod -R 0777 ./storage';
$cmd[] = 'php artisan migrate';

$result1 = shell_exec(implode(' && ', $cmd));
echo "<br>";


print_r($result1);
