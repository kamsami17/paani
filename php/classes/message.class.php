<?php 

class Message {
        public int $id;
    public int $classe;
    public string $message;
    public int $surveillant;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO message(classe, message, surveillant) VALUES(:classe, :message, :surveillant)');
  $r->execute(array(
    ':classe' => $this->classe,
    ':message' => $this->message,
    ':surveillant' => $this->surveillant));
		$this->id = $bdd->lastInsertId('message_id_seq');
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM message  WHERE id=?');
  $r->execute(array($id));
  $el = new Message();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE message SET classe=:classe, message=:message, surveillant=:surveillant WHERE id=:id');
  $r->execute(array(
    ':classe' => $this->classe,
   ':message' => $this->message,
   ':surveillant' => $this->surveillant,
   ':id' => $this->id)); 
}

public static function get_all($bdd){
	$r = $bdd->query('SELECT * FROM message');
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Message();
		$d->hydrate($data);
		array_push($ar, $d);
	}
		return $ar;
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM message WHERE id=?');
  $r->execute(array($id));
}

public static function history($bdd, $msg, $sender, $ecole, $num, $dest){
  $r = $bdd->prepare('INSERT INTO sms_history(message, sender, ecole, num, dest) VALUES(:message, :sender, :ecole, :num, :dest)');
  $r->execute(array(
    ':message'=> $msg,
    ':sender'=>$sender,
    ':ecole'=>$ecole,
    ':num'=>$num,
    ':dest' => $dest
  ));
}
public static function ctmsg($bdd, $ecole){
  $r = $bdd->prepare('SELECT SUM(num) as st FROM sms_history WHERE ecole=?');
  $r->execute(array($ecole));
  return $r->fetch()['st'];
}

}

 ?>