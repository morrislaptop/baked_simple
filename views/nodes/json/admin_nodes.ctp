<?php

$data = array();

foreach ($nodes as $node){
    $data[] = array(
        "text" => $node['Node']['title'], 
        "id" => $node['Node']['id'], 
        "cls" => "folder",
        "leaf" => false #($node['Node']['lft'] + 1 == $node['Node']['rght'])
    );
}

echo $javascript->object($data);

?>