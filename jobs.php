<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\{
    Job,
    Project,
    Printable
};

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'practicaphp',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$jobs = Job::all();
$projects = Project::all();

function printElement($element)
{
    // if ($element->visible == false) {
    //     return;
    // }

    echo '<li class="work-position">';
    echo '<h5>' . $element->title . '</h5>';
    echo '<p>' . $element->description . '</p>';
    echo '<p>' . $element->getDurationAsString() . '</p>';
    echo '<strong>Achievements:</strong>';
    echo '<ul>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing el';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing el';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing el';
    echo '</ul>';
    echo '</li>';
}