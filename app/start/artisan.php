<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/
Artisan::add(new GenerateCourt());
Artisan::add(new CleanCourt());
Artisan::add(new InstantOrderGenerate());
Artisan::add(new InstantOrderClean());
Artisan::add(new UserHallRelationSupport());
Artisan::add(new AccountBillingStaging());
Artisan::add(new SmsQueueHander());
Artisan::add(new InstantOrderExpire());