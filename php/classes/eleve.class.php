<?php 

class Eleve {
    public int $id;
    public string $nom;
    public string $prenom;
    public string $date_naissance;
    public string $matricule;
    public string $uname;
    public string $passwd;
    public string $parents;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO eleve(nom, prenom, date_naissance, sexe, matricule, uname, passwd, parents) VALUES(:nom, :prenom, :date_naissance, :sexe, :matricule, :uname, :passwd, :parent)');
  $r->execute(array(
    ':nom' => $this->nom,
    ':prenom' => $this->prenom,
    ':date_naissance' => $this->date_naissance,
    ':sexe' => $this->sexe,
    ':matricule' => $this->matricule,
    ':uname' => $this->uname,
    ':passwd' => $this->passwd,
    ':parent' => $this->parents));
		$this->id = $bdd->lastInsertId('eleve_id_seq');   
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM eleve  WHERE id=?');
  $r->execute(array($id));
  $el = new Eleve();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE eleve SET nom=:nom, prenom=:prenom, date_naissance=:date_naissance, sexe=:sexe, matricule=:matricule, uname=:uname, passwd=:passwd WHERE id=:id');
  $r->execute(array(
    ':nom' => $this->nom,
   ':prenom' => $this->prenom,
   ':date_naissance' => $this->date_naissance,
   ':sexe' => $this->sexe,
   ':matricule' => $this->matricule,
   ':uname' => $this->uname,
   ':passwd' => $this->passwd,
   //':parent' => $this->parent,
   ':id' => $this->id)); 
}

public static function get_lib($bdd, $id){
  $r = $bdd->prepare('SELECT nom,prenom FROM eleve WHERE id=?');
  $r->execute(array($id));
  $d = $r->fetch();
  return $d['nom'].' '.$d['prenom'];
}

public static function set_parent($bdd, $eleve, $parent){
  $r = $bdd->prepare('UPDATE eleve SET parents=? WHERE id=?');
  $r->execute(array($parent, $eleve));
}

public static function get_all($bdd){
	$r = $bdd->query('SELECT * FROM eleve');
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Eleve();
		$d->hydrate($data);
		array_push($ar, $d);
	}
		return $ar;
}

public static function get_classe($bdd, $classe, $annee){
  $r = $bdd->prepare('SELECT e.* FROM eleve e, classe_eleve ce WHERE e.id=ce.eleve AND ce.annee=? AND ce.classe=?');
  $r->execute(array($annee, $classe));
  return $r->fetchAll();
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM eleve WHERE id=?');
  $r->execute(array($id));
}

public static function genCode($bdd){
  $r=$bdd->query('SELECT count(*) as ct FROM eleve');
  $d=$r->fetch();
  $res='';
  for($i=0; $i<3; $i++){
    $res = $res.chr(rand (65, 90));
  }
  return $res.sprintf('%02d', $d['ct']);
}

public static function minstat($bdd, $ecole){
  $r = $bdd->prepare('SELECT COUNT(*) as ct FROM eleve WHERE id IN (SELECT e.id FROM classe_eleve ce, eleve e, classe c WHERE ce.eleve=e.id AND ce.classe=c.id AND c.ecole=?)');
  $r->execute(array($ecole));
  $r1 = $bdd->prepare('SELECT COUNT(*) as ct FROM parent WHERE ecole=?');
  $r1->execute(array($ecole));
  return [ 'eleves' => $r->fetch()['ct'], 'parents' => $r1->fetch()['ct'] ];
}

public static function getid($bdd, $matricule){
  $r = $bdd->prepare('SELECT id FROM eleve WHERE matricule=?');
  $r->execute(array($matricule));
  return $r->fetch()['id'];
}

}

 ?>