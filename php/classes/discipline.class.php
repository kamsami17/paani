<?php 

class Discipline {
        public int $id;
    public int $eleve;
    public string $note;
    public int $surveillant;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO discipline(eleve, note, surveillant) VALUES(:eleve, :note, :surveillant)');
  $r->execute(array(
    ':eleve' => $this->eleve,
    ':note' => $this->note,
    ':surveillant' => $this->surveillant));
		$this->id = $bdd->lastInsertId('discipline_id_seq');
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM discipline  WHERE id=?');
  $r->execute(array($id));
  $el = new Discipline();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE discipline SET eleve=:eleve, note=:note, surveillant=:surveillant WHERE id=:id');
  $r->execute(array(
    ':eleve' => $this->eleve,
   ':note' => $this->note,
   ':surveillant' => $this->surveillant,
   ':id' => $this->id)); 
}

public static function get_all($bdd){
	$r = $bdd->query('SELECT * FROM discipline');
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Discipline();
		$d->hydrate($data);
		array_push($ar, $d);
	}
  return $ar;
}

public static function get_eleve($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM discipline WHERE eleve=?');
  $r->execute(array($id));
  return $r->fetchAll();
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM discipline WHERE id=?');
  $r->execute(array($id));
}

}

 ?>