<?php

$data = array("name" => "Evans", "age" => 24);
$object = (object)$data;

var_dump($object->name);