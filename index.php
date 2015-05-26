<?php
/**
 * Kakalika's entry point
 * 
 * Ntentan Framework
 * Copyright (c) 2013 James Ekow Abaka Ainooson
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
 * 
 * @author James Ainooson <jainooson@gmail.com>
 * @copyright Copyright 2013 James Ekow Abaka Ainooson
 * @license MIT
 */

error_reporting(E_ALL  ^ E_NOTICE);
$ntentan = parse_ini_file('config/ntentan.ini', true);
require "vendor/autoload.php";
use ntentan\Ntentan;

xdebug_start_trace("logs/trace.out");
ini_set('xdebug.collect_params', 4);

// @todo Allow the timezone to be set during installation
date_default_timezone_set('Africa/Accra');

try{
    Ntentan::setup($ntentan);
    include('routes.php');
    Ntentan::route();
}
catch(\ntentan\exceptions\ApiIniFileNotFoundException $e)
{
    header("Location: {$_SERVER['REQUEST_URI']}install");
}
