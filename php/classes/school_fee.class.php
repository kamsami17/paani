<?php 

class School_fee {
        public int $id;
    public int $classe;
    public int $eleve;
    public int $montant;
    public int $reste;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO school_fee(classe, eleve, montant, reste) VALUES(:classe, :eleve, :montant, :reste)');
  $r->execute(array(
    ':classe' => $this->classe,
    ':eleve' => $this->eleve,
    ':montant' => $this->montant,
    ':reste' => $this->reste));
		$this->id = $bdd->lastInsertId('school_fee_id_seq');
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM school_fee  WHERE id=?');
  $r->execute(array($id));
  $el = new School_fee();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE school_fee SET classe=:classe, eleve=:eleve, montant=:montant, reste=:reste WHERE id=:id');
  $r->execute(array(
    ':classe' => $this->classe,
   ':eleve' => $this->eleve,
   ':montant' => $this->montant,
   ':reste' => $this->reste,
   ':id' => $this->id)); 
}

public static function get_all($bdd){
	$r = $bdd->query('SELECT * FROM school_fee');
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new School_fee();
		$d->hydrate($data);
		array_push($ar, $d);
	}
		return $ar;
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM school_fee WHERE id=?');
  $r->execute(array($id));
}

}

 ?>