<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RunController extends Controller
{
    public function runPython(Request $request){
        $name = uniqid(rand(), true) . '.py';
        $file = fopen('storage/'.$name,'w') or die('unable open file');
        fwrite($file,"#!/usr/bin/env python\n");
        fwrite($file,$request->code );
        fclose($file);

        $command = escapeshellcmd('python storage/'.$name);
        $output = shell_exec($command);

        unlink('storage/'.$name);
        return response()->json($output);
    }
}
