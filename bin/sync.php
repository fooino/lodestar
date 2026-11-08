#!/usr/bin/env php
<?php

use Fooino\Lodestar\Console\SyncStandardsCommand;
use Symfony\Component\Console\Application;

require getcwd() . '/vendor/autoload.php';

$application = new Application(name: 'Fooino Lodestar');

$application->addCommand(command: new SyncStandardsCommand());

$application->setDefaultCommand(commandName: 'sync', isSingleCommand: true);

$application->run();
