<?php 

class Subs {
        public int $id;
    public int $parent;
    public int $state;
    public int $validated_by;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO subs(parent, state, validated_by) VALUES(:parent, :state, :validated_by)');
  $r->execute(array(
    ':parent' => $this->parent,
    ':state' => $this->state,
    ':validated_by' => $this->validated_by));
		$this->id = $bdd->lastInsertId('subs_id_seq');
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM subs  WHERE id=?');
  $r->execute(array($id));
  $el = new Subs();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE subs SET parent=:parent, state=:state, validated_by=:validated_by WHERE id=:id');
  $r->execute(array(
    ':parent' => $this->parent,
   ':state' => $this->state,
   ':validated_by' => $this->validated_by,
   ':id' => $this->id)); 
}

public static function get_all($bdd){
	$r = $bdd->query('SELECT * FROM subs');
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Subs();
		$d->hydrate($data);
		array_push($ar, $d);
	}
		return $ar;
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM subs WHERE id=?');
  $r->execute(array($id));
}

}

 ?>