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
    public static function installJSDependencies($event) {
        echo "Installing JS Library dependencies for the AltamiraBundle";
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
