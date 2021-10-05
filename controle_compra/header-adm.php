<?php
session_start();
if (!$_SESSION['logado']) {
    header('location:login.html');
}

function pageHeader($title)
{

    echo '<!DOCTYPE html>
        <html lang="pt-br">
        <head>
        
            <meta charset="UTF-8">
            <title> '. $title .'</title>
            <link rel="stylesheet" href="CSS/icons.css">
            <link rel="stylesheet" href="CSS/search.css">
            <link rel="stylesheet" href="CSS/bootstrap.css">
        
        </head>
        <body>';
    require_once('nav-bar-adm.php');

}


