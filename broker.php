<?php

class Broker{
    private $rezultat;
    private $mysqli;
    private static $broker;
    public function getRezultat(){
        return $this->rezultat;
    }
     public function getMysqli()
    {
        return $this->mysqli;
    }
    private function __construct(){
        $this->mysqli = new mysqli("localhost","root","","katedre");
        $this->mysqli->set_charset("utf8");
    }

    public static function getBroker(){
        if(!isset($broker)){
            $broker=new Broker();
        }
        return $broker;
    }
    
    private function izvrsiUpit($upit){
        $this->rezultat=$this->mysqli->query($upit);
    }

    public function vratiZvanja(){
        $this->izvrsiUpit("select * from zvanje");
    }
    public function vratiKatedreProsto(){
        $this->izvrsiUpit("select id,naziv from katedra");
    }
    public function vratiProfesore(){
        $this->izvrsiUpit("select p.*, z.naziv as 'nazivZvanja', k.naziv as 'nazivKatedre' from profesor p left join zvanje z on (z.id=p.zvanje) left join katedra k on (k.id=p.katedra)");
    }
    public function vratiKatedreDetaljno(){
        $this->izvrsiUpit("select k.*, p.ime as 'sefIme', p.prezime as 'sefPrezime' from katedra k left join profesor p on (k.sef=p.id) ");
    }
    public function vratiProfesoreSaKatedre($id){
        $this->izvrsiUpit("select id, ime,prezime from profesor where katedra=".$id);
    }
    public function dodajKatedru($naziv,$opis,$sef){
        if($sef==0){
            $this->izvrsiUpit("insert into katedra (naziv,opis) values ('".$naziv."','".$opis."')");
        }else{
            $this->izvrsiUpit("insert into katedra (naziv,opis,sef) values ('".$naziv."','".$opis."',".$sef.")");
        }
        if($this->rezultat){
            $last_id = $this->mysqli->insert_id;
            $this->izvrsiUpit("update profesor set katedra=".$last_id." where id=".$sef);
        }
        
        
    }
    public function obrisiKatedru($id){
        $this->izvrsiUpit("delete from katedra where id=".$id);
    }
    public function obrisiProfesora($id){
        $this->izvrsiUpit("delete from profesor where id=".$id);
    }

    public function izmeniKatedru($id,$naziv,$opis,$sef){
        if($sef==0){
            $this->izvrsiUpit("update katedra set naziv='".$naziv."', opis='".$opis."' where id=".$id);
        }else{
            $this->izvrsiUpit("update katedra set naziv='".$naziv."', opis='".$opis."', sef=".$sef." where id=".$id);
        }
    }
    public function dodajProfesora($ime,$prezime,$zvanje,$katedra){
      if($zvanje==0 && $katedra==0){
        $this->izvrsiUpit("insert into profesor(ime,prezime) values ('".$ime."','".$prezime."')");
            return;
      }
      if($zvanje==0){
        $this->izvrsiUpit("insert into profesor(ime,prezime,katedra) values ('".$ime."','".$prezime."',".$katedra.")");
        return;
      }
      if($katedra==0){
        $this->izvrsiUpit("insert into profesor(ime,prezime,zvanje) values ('".$ime."','".$prezime."',".$zvanje.")");
        return;
      }
      $this->izvrsiUpit("insert into profesor(ime,prezime,zvanje,katedra) values ('".$ime."','".$prezime."',".$zvanje.",".$katedra.")");
    }
    public function izmeniProfesora($id,$ime,$prezime,$zvanje,$katedra){
        $this->izvrsiUpit("select id from katedra where sef=".$id);
        $staraKatedra=$this->rezultat->fetch_object();
        if(isset($staraKatedra)){
            $this->izvrsiUpit("update katedra set sef=NULL where id=".$staraKatedra->id);
        }

        if($zvanje==0 && $katedra==0){
            $this->izvrsiUpit("update profesor set ime='".$ime."', prezime='".$prezime."' where id=".$id);
                return;
          }
          if($zvanje==0){
            $this->izvrsiUpit("update profesor set ime='".$ime."', prezime='".$prezime."', katedra=".$katedra." where id=".$id);
            return;
          }
          if($katedra==0){
            $this->izvrsiUpit("update profesor set ime='".$ime."', prezime='".$prezime."', zvanje=".$zvanje." where id=".$id);
            return;
          }
          $this->izvrsiUpit("update profesor set ime='".$ime."', prezime='".$prezime."', katedra=".$katedra.", zvanje=".$zvanje." where id=".$id);
          
    
    
        }



    public function vratiProfesoreBezKatedre(){
        $this->izvrsiUpit("select p.*, z.naziv as 'nazivZvanja' from profesor p left join zvanje z on (z.id=p.zvanje) where p.katedra is null ");
    }
}


?>