<?php 

class Parents {
    public int $id;
    public string $nom;
    public string $prenom;
    public string $contact;
    public string $email;
    public string $passwd;
    public int $ecole;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO parent(nom, prenom, contact, email, passwd, ecole) VALUES(:nom, :prenom, :contact, :email, :passwd, :ecole)');
  $r->execute(array(
    ':nom' => $this->nom,
    ':prenom' => $this->prenom,
    ':contact' => $this->contact,
    ':email' => $this->email,
    ':passwd' => $this->passwd,
    ':ecole' => $this->ecole));
		$this->id = $bdd->lastInsertId('parent_id_seq');
}

public function create2($bdd){
  $r0 = $bdd->prepare('SELECT id FROM parent WHERE contact=?');
  $r0->execute(array($this->contact));
  if($r0->rowCount()==0){
    $r = $bdd->prepare('INSERT INTO parent(nom, prenom, contact, email, passwd, ecole) VALUES(:nom, :prenom, :contact, :email, :passwd, :ecole)');
    $r->execute(array(
      ':nom' => $this->nom,
      ':prenom' => $this->prenom,
      ':contact' => $this->contact,
      ':email' => $this->email,
      ':passwd' => $this->passwd,
      ':ecole' => $this->ecole));
      $this->id = $bdd->lastInsertId('parent_id_seq');
  }
  else{
    $d0 = $r0->fetch();
    $this->id = $d0['id'];
    $r = $bdd->prepare('UPDATE parent SET contact=? WHERE id=?');
    $r->execute(array($this->contact, $d0['id']));
  }

}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT nom, prenom, contact, email FROM parent  WHERE id=?');
  $r->execute(array($id));
  $el = new Parents();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE parent SET nom=:nom, prenom=:prenom, contact=:contact, email=:email WHERE id=:id');
  $r->execute(array(
    ':nom' => $this->nom,
   ':prenom' => $this->prenom,
   ':contact' => $this->contact,
   ':email' => $this->email,
   //':passwd' => $this->passwd,
   ':id' => $this->id)); 
}

public static function get_all($bdd, $ecole){
	$r = $bdd->prepare('SELECT * FROM parent WHERE ecole=?');
  $r->execute(array($ecole));
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Parents();
		$d->hydrate($data);
		array_push($ar, $d);
	}
  return $ar;
}

public static function get_id($bdd, $contact){
  $r = $bdd->prepare('SELECT id FROM parent WHERE contact=?');
  $r->execute(array($contact));
  return $r->fetch()['id'];
}

public static function get_classe($bdd, $classe){
  $r = $bdd->prepare('SELECT * FROM parent WHERE id IN (SELECT TO_NUMBER(e.parents, \'9\') as p FROM eleve e, classe_eleve ce WHERE ce.eleve=e.id AND ce.classe=?)');
  $r->execute(array($classe));
  $ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Parents();
		$d->hydrate($data);
		array_push($ar, $d);
	}
  return $ar;
}

public static function init_pass($bdd, $id, $pass){
  $r = $bdd->prepare('UPDATE parent SET passwd=? WHERE id=?');
  $r->execute(array($pass, $id));
}

public static function get_enfants($bdd, $parent, $annee){
  $q = $bdd->prepare('SELECT e.id, e.nom, e.prenom, e.date_naissance, e.matricule, e.parents, c.id as classe_ref, c.name as classe FROM eleve e, classe c, classe_eleve ce WHERE e.id=ce.eleve AND c.id=ce.classe AND ce.annee=? AND e.parents=?');
  $q->execute(array($annee, $parent));
  return $q->fetchAll(PDO::FETCH_ASSOC);
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM parent WHERE id=?');
  $r->execute(array($id));
}

}

 ?>