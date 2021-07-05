<?php
    include "broker.php";
    $broker=Broker::getBroker();
    $broker->vratiProfesoreSaKatedre($_GET["katedra"]);
    $res=array();
        
        if(!$broker->getRezultat()){
            $res["status"]=$broker->getMysqli()->error;
        }else{
            $res["status"]="ok";
            $res["profesori"]=array();
            while($profesor=$broker->getRezultat()->fetch_object()){
                $res["profesori"][]=$profesor;
            }
        }
        echo json_encode($res);



?>