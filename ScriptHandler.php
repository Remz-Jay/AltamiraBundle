<?php
/**
 *
 * This file is part of the AltamiraBundle package.
 *
 * Copyright (c) 2012 Malwarebytes
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Malwarebytes\AltamiraBundle;


/**
 * Handles composer install/update
 */
class ScriptHandler
{
    public static function fetchDependencies($event) {
/*        chdir(__DIR__);

        echo getcwd(), "\n";
        $finder = new \Symfony\Component\Process\ExecutableFinder();
        $gitBin = $finder->find('git');
        $escapedgitBin=escapeshellarg($gitBin);
        $command=$escapedgitBin." submodule init";
        //echo "$escapedgitBin submodule init && $escapedgitBin submodule foreach $escapedgitBin reset --hard && $escapedgitBin submodule foreach $escapedgitBin pull && $escapedgitBin submodule update 2>&1";
        $cmd=new \Symfony\Component\Process\Process($command,__DIR__);
        $cmd->setTimeout(60000);
        $cmd->run(function ($type, $buffer) {
            if ('err' === $type) {
                echo 'ERR > '.$buffer;
            } else {
                echo 'OUT > '.$buffer;
            }
        });
        var_dump($cmd->getOutput());
        echo "Error Code: ",$cmd->getExitCode(),"\n";

        if ($cmd->isSuccessful()) {
            echo "worked";
        }
        $cmd=new \Symfony\Component\Process\Process(sprintf('%s submodule update 2>&1', escapeshellarg($gitBin)),__DIR__);
        $cmd->setTimeout(60000);
        $cmd->run();
        echo $cmd->getOutput();
        echo $cmd->getErrorOutput();

        if ($cmd->isSuccessful()) {
            echo "worked";
        }


        if(system("git submodule init") === false || system("git submodule update") === false ) {
            echo "Error occurred while trying to setup altamira bundle submodules.\n";
        }
        echo "hello world\n";*/



            $status = null;
            $output = array();
            $dir = getcwd();
            chdir(__DIR__);
            exec('git submodule sync', $output, $status);
            if ($status) {
                chdir($dir);
                die("Running git submodule sync failed with $status\n");
            }
            exec('git submodule update --init --recursive', $output, $status);
            chdir($dir);
            if ($status) {
                die("Running git submodule --init --recursive failed with $status\n");
            }

    }
}
