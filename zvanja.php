<?php
    include "broker.php";
    $broker=Broker::getBroker();
    $broker->vratiZvanja();
    $res=array();
        
        if(!$broker->getRezultat()){
            $res["status"]=$broker->getMysqli()->error;
        }else{
            $res["status"]="ok";
            $res["zvanja"]=array();
            while($zvanje=$broker->getRezultat()->fetch_object()){
                $res["zvanja"][]=$zvanje;
            }
        }
        echo json_encode($res);



?>