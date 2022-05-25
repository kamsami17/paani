<?php 

class Ecole {
        public int $id;
    public string $name;
    public string $code;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO ecole(name, code) VALUES(:name, :code)');
  $r->execute(array(
    ':name' => $this->name,
    ':code' => $this->code));
		$this->id = $bdd->lastInsertId('ecole_id_seq');
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM ecole  WHERE id=?');
  $r->execute(array($id));
  $el = new Ecole();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE ecole SET name=:name, code=:code WHERE id=:id');
  $r->execute(array(
    ':name' => $this->name,
   ':code' => $this->code,
   ':id' => $this->id)); 
}

public static function get_all($bdd){
	$r = $bdd->query('SELECT * FROM ecole');
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Ecole();
		$d->hydrate($data);
		array_push($ar, $d);
	}
		return $ar;
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM ecole WHERE id=?');
  $r->execute(array($id));
}

}

 ?>