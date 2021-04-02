<?php
function __autoload($class) {
    require_once("app/Models/$class.php");
}