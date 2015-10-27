<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$app->post('slack', 'SlackController@processCommand');
$app->post('/', function() use ($app) {
	// $kernel = new App\Console\Kernel($app);
	// dd('test');
	$input = new Symfony\Component\Console\Input\StringInput('3902 --env slack');
	$kernel = new App\Console\SoapBotKernel($app);
	$kernel->callWithStringArgs('pr-info', '--help');
	return $kernel->output();

	// $p = [
 //            'pr_number' => '3902',
 //            '--env' => 'slack',
 //            '--help' => true
 //        ];

 //       $kernel = new App\Console\Kernel($app);
	// $kernel->call('pr-info', $p);
	// return $kernel->output();

	// $definition = new Symfony\Component\Console\Input\InputDefinition();
	// $command = new App\Console\Commands\SoapBot\PrInfoCommand();
	// $command->setApplication($kernel->getArtisan());
	// $input->bind($command->getDefinition());
	// dd([$input->getArguments(), $input->getOptions()]);

// 	$kernel->call('sb', ['--format' => 'slack']);
// 	$output = $kernel->output();
});
