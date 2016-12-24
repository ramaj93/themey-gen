#!/usr/bin/env php
<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require __DIR__ . "/../vendor/autoload.php";

use \themey\Application;

$application = new Application();

// ... register commands

$application->run();
