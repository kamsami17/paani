<?php 

class Classe_eleve {
        public int $id;
    public int $classe;
    public int $eleve;
    public int $annee;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO classe_eleve(classe, eleve, annee) VALUES(:classe, :eleve, :annee)');
  $r->execute(array(
    ':classe' => $this->classe,
    ':eleve' => $this->eleve,
    ':annee' => $this->annee));
		$this->id = $bdd->lastInsertId('classe_eleve_id_seq');
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM classe_eleve  WHERE id=?');
  $r->execute(array($id));
  $el = new Classe_eleve();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE classe_eleve SET classe=:classe, eleve=:eleve, annee=:annee WHERE id=:id');
  $r->execute(array(
    ':classe' => $this->classe,
   ':eleve' => $this->eleve,
   ':annee' => $this->annee,
   ':id' => $this->id)); 
}

public static function get_all($bdd){
	$r = $bdd->query('SELECT * FROM classe_eleve');
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Classe_eleve();
		$d->hydrate($data);
		array_push($ar, $d);
	}
		return $ar;
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM classe_eleve WHERE id=?');
  $r->execute(array($id));
}

}

 ?>