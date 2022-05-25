<?php 

class Trimestre {
        public int $id;
    public int $classe;
    public string $lib;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO trimestre(classe, lib) VALUES(:classe, :lib)');
  $r->execute(array(
    ':classe' => $this->classe,
    ':lib' => $this->lib));
		$this->id = $bdd->lastInsertId('trimestre_id_seq');
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM trimestre  WHERE id=?');
  $r->execute(array($id));
  $el = new Trimestre();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE trimestre SET classe=:classe, lib=:lib WHERE id=:id');
  $r->execute(array(
    ':classe' => $this->classe,
   ':lib' => $this->lib,
   ':id' => $this->id)); 
}

public static function get_all($bdd, $classe){
	$r = $bdd->prepare('SELECT * FROM trimestre WHERE classe=?');
  $r->execute(array($classe));
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Trimestre();
		$d->hydrate($data);
		array_push($ar, $d);
	}
		return $ar;
}

public static function last_trim($bdd, $classe){
	$r = $bdd->prepare('SELECT MAX(id) as tid FROM trimestre WHERE classe=?');
  $r->execute(array($classe));
	return $r->fetch()['tid'];
}

public static function get_lib($bdd, $id){
  $r = $bdd->prepare('SELECT lib FROM trimestre WHERE id=?');
  $r->execute(array($id));
  return $r->fetch()['lib'];
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM trimestre WHERE id=?');
  $r->execute(array($id));
}

}

 ?>