<?php
// show error reporting
error_reporting(E_ALL);
 
// set your default time-zone
date_default_timezone_set('Asia/Jakarta');
 
// variables used for jwt
$key = "251289";
$issued_at = time();
$expiration_time = $issued_at + 60 * 60 * 24 * 60; // valid for 60 day
$issuer = "https://apivisindo.checkdeliver.com";