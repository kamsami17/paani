<?php 

class Users {
        public int $id;
    public string $nom;
    public string $prenom;
    public string $contact;
    public string $email;
    public string $passwd;
    public string $role;
    public int $ecole;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO users(nom, prenom, contact, email, passwd, role, ecole) VALUES(:nom, :prenom, :contact, :email, :passwd, :role, :ecole)');
  $r->execute(array(
    ':nom' => $this->nom,
    ':prenom' => $this->prenom,
    ':contact' => $this->contact,
    ':email' => $this->email,
    ':passwd' => $this->passwd,
    ':role' => $this->role,
    ':ecole' => $this->ecole));
		$this->id = $bdd->lastInsertId('users_id_seq');
}

public function auth($bdd){
  $r = $bdd->prepare('SELECT * FROM users WHERE email=:email AND passwd=:pwd AND role=\'ADMIN\'');
  $r->execute(array(
    ':email'=>$this->email,
    ':pwd'=>$this->passwd
  ));
  if($r->rowCount()==1){
    $d = $r->fetch(PDO::FETCH_ASSOC);
    $this->hydrate($d);
    return 1;
  }else{
    return 0;
  }
}

public static function getlib($bdd, $id){
  $r = $bdd->prepare('SELECT CONCAT(nom,\' \',prenom) as name FROM users WHERE id=?');
  $r->execute(array($id));
  return $r->fetch()['name'];
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM users  WHERE id=?');
  $r->execute(array($id));
  $el = new Users();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE users SET nom=:nom, prenom=:prenom, contact=:contact, email=:email, role=:role, ecole=:ecole WHERE id=:id');
  $r->execute(array(
    ':nom' => $this->nom,
   ':prenom' => $this->prenom,
   ':contact' => $this->contact,
   ':email' => $this->email,
   ':role' => $this->role,
   ':ecole' => $this->ecole,
   ':id' => $this->id)); 
}

public static function get_all($bdd){
	$r = $bdd->query('SELECT * FROM users');
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Users();
		$d->hydrate($data);
		array_push($ar, $d);
	}
		return $ar;
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM users WHERE id=?');
  $r->execute(array($id));
}

public static function get_ecole($bdd, $id){
  $r = $bdd->prepare('SELECT ecole FROM users WHERE id=?');
  $r->execute(array($id));
  return $r->fetch()['ecole'];
}

}

 ?>