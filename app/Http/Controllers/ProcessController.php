<?php

namespace App\Http\Controllers;

use App\Model\Client;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ProcessController extends Controller
{
    public function Task()
    {
        $client = Client::where('status', 1)->get();
        
        foreach ($client as $value) {
            $process = new Process(['php artisan newchanges:run' , $value->database_name]);
            $process->setTimeout(0);
            $process->disableOutput();
            $process->start();
            $processes[] = $process;
            while (count($processes)) {
                foreach ($processes as $i => $runningProcess) {
                    // specific process is finished, so we remove it
                    if (! $runningProcess->isRunning()) {
                        unset($processes[$i]);
                    }
                    sleep(1);
                }
            }
        }
    }
}
