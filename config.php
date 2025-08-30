<?php
$host = "127.0.0.1";   
$port = "3307";          
$db   = "appointments";   
$user = "root";         
$pass = "";            

// Starts session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
