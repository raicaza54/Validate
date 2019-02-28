<?php
defined('BASEPATH') OR exit('No direct script access allowed');
[
    'Type'       => get_class($exception),
    'Message'    => $message,
    'Filename'   => $exception->getFile(),
    'LineNumber' => $exception->getLine()
];