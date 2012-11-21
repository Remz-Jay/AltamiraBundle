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
        echo "Installing JS Library dependencies for the AltamiraBundle\n";
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

        chdir(__DIR__.DIRECTORY_SEPARATOR."Resources".DIRECTORY_SEPARATOR."libs".DIRECTORY_SEPARATOR."jqplot");

        echo "Compiling jqplot\n";
        exec('ant clean min', $output, $status);
        if ($status) {
            die("Ant failed with $status (is ant installed?)\n");
        }

        if (is_dir(__DIR__.DIRECTORY_SEPARATOR."Resources".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."js")) {
            rrmdir(__DIR__.DIRECTORY_SEPARATOR."Resources".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."js");
        }

        mkdir(__DIR__.DIRECTORY_SEPARATOR."Resources".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."js".DIRECTORY_SEPARATOR."jqplot",0777,true);

        $source = __DIR__.DIRECTORY_SEPARATOR."Resources".DIRECTORY_SEPARATOR."libs".DIRECTORY_SEPARATOR."jqplot".DIRECTORY_SEPARATOR."dist";
        $dest= __DIR__.DIRECTORY_SEPARATOR."Resources".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."js".DIRECTORY_SEPARATOR."jqplot";

        recursiveAssetsOnlyCopy($source,$dest);


        echo "Compiling flot\n";

        chdir(__DIR__.DIRECTORY_SEPARATOR."Resources".DIRECTORY_SEPARATOR."libs".DIRECTORY_SEPARATOR."flot");
        if (($files = scandir(".")) ===false ) {
            die("failed to traverse through flot directory");
        }
        mkdir(__DIR__.DIRECTORY_SEPARATOR."Resources".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."js".DIRECTORY_SEPARATOR."flot",0777,true);
        foreach ($files as $file) {
            if (substr($file,-3) == ".js") {
                exec("java -jar ..".DIRECTORY_SEPARATOR."jqplot".DIRECTORY_SEPARATOR."extras".DIRECTORY_SEPARATOR."yuicompressor-2.4.2.jar $file -o "
                    .__DIR__.DIRECTORY_SEPARATOR."Resources".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."js".DIRECTORY_SEPARATOR."flot".DIRECTORY_SEPARATOR.substr($file,0,-2)."min.js");
            }
        }

        chdir(__DIR__.DIRECTORY_SEPARATOR."Resources".DIRECTORY_SEPARATOR."libs".DIRECTORY_SEPARATOR."flot-bubbles");

        exec("java -jar ..".DIRECTORY_SEPARATOR."jqplot".DIRECTORY_SEPARATOR."extras".DIRECTORY_SEPARATOR."yuicompressor-2.4.2.jar $file -o "
            .__DIR__.DIRECTORY_SEPARATOR."Resources".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."js".DIRECTORY_SEPARATOR."flot".DIRECTORY_SEPARATOR.substr("jquery.flot.bubble.js",0,-2)."min.js");

        chdir($dir);
    }
}


function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

function recursiveAssetsOnlyCopy($source,$dest) {
    foreach (
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST) as $item
    ) {
        if ($item->isDir()) {
            mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
        } else {
            if (substr($item,-3) == ".js" || substr($item,-3) == "css") {
                copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }
    }
}