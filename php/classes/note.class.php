<?php 

class Note {
    public int $id;
    public string $matricule;
    public int $matiere;
    public float $note;
    public int $trimestre;
    public int $annee;
    public string $code;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){

  $r = $bdd->prepare('INSERT INTO note(matricule, matiere, note, trimestre, annee, code) VALUES(:matricule, :matiere, :note, :trimestre, :annee, :code)');
  $r->execute(array(
    ':matricule' => $this->matricule,
    ':matiere' => $this->matiere,
    ':note' => $this->note,
    ':trimestre' => $this->trimestre,
    ':annee' => $this->annee,
    ':code' => $this->code));
		$this->id = $bdd->lastInsertId('note_id_seq');
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM note  WHERE id=?');
  $r->execute(array($id));
  $el = new Note();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE note SET matricule=:matricule, matiere=:matiere, note=:note, trimestre=:trimestre, annee=:annee WHERE id=:id');
  $r->execute(array(
    ':matricule' => $this->matricule,
   ':matiere' => $this->matiere,
   ':note' => $this->note,
   ':trimestre' => $this->trimestre,
   ':annee' => $this->annee,
   ':id' => $this->id)); 
}

public static function get_all($bdd){
	$r = $bdd->query('SELECT * FROM note');
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Note();
		$d->hydrate($data);
		array_push($ar, $d);
	}
		return $ar;
}

public static function list_mat($bdd, $matricule, $matiere, $trimestre){
  $r = $bdd->prepare('SELECT note FROM note WHERE matricule=? AND matiere=? AND trimestre=?');
  $r->execute(array($matricule, $matiere, $trimestre));
  return $r->fetchAll(PDO::FETCH_ASSOC);
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM note WHERE id=?');
  $r->execute(array($id));
}

public static function get_ct($bdd){
  $r = $bdd->query('SELECT COUNT(*) as ct FROM note');
  $num = $r->fetch()['ct'];
  return dechex($num);
}

}

 ?>