<?php
include "broker.php";
$broker=Broker::getBroker();
    if(isset($_POST["id"]) && intval($_POST["id"])){
        $broker->obrisiProfesora($_POST["id"]);
        if(!$broker->getRezultat()){
            echo $broker->getMysqli()->error;
        }
        echo "ok";   
    }

?>