<?php
include "broker.php";
$broker=Broker::getBroker();
if(isset($_POST["akcija"])){
        $ime=$_POST["ime"];
        $prezime=$_POST["prezime"];
        $katedra=$_POST["katedra"];
        $zvanje=$_POST["zvanje"];
        if(!validanProfesor($ime,$prezime)){
            echo "losi podaci";
            return;
        }else{
            if($_POST["akcija"]=="izmeni"){
                $broker->izmeniProfesora($_POST["id"],$ime,$prezime,$zvanje,$katedra);
            }else{
                $broker->dodajProfesora($ime,$prezime,$zvanje,$katedra);
            }
            
        }
        if(!$broker->getRezultat()){
            echo $broker->getMysqli()->error;
        }
        echo "ok";
    
}
function validanProfesor($ime,$prezime){
    $ime=trim($ime);
    $prezime=trim($prezime);
    return strlen($ime)>2 && strlen($prezime)>3 && preg_match("/^[A-Z][a-z]*$/",$ime) && preg_match("/^[A-Z][a-z]*$/",$prezime) ;
}

?>