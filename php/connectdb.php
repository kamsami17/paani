<?php
  try {
    $bdd=new PDO('pgsql:dbname=paani;host=localhost', 'root', 'toor');
  } catch (Exception $e) {
    die('ERROR: '.$e->getMessage());
  }

  spl_autoload_register(function ($class) {
      include 'classes/' . lcfirst($class) . '.class.php';
  });

  if (session_status() == PHP_SESSION_NONE) {  session_start(); }

  function GUID()
  {
      if (function_exists('com_create_guid') === true)
      {
          return trim(com_create_guid(), '{}');
      }
      return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
  }

  function upload(array $FILE, $name){
    $path = pathinfo($FILE['name']);
    $ext = $path['extension'];
    move_uploaded_file($FILE['tmp_name'], '../../upload/'.$name.'_'.date('Ymd_His').'.'.$ext);

    return 'upload/'.$name.'_'.date('Ymd_His').'.'.$ext;
  }

  // function upload2(array $FILE, $name, $path){
  //   $path = pathinfo($FILE['name']);
  //   $ext = $path['extension'];
  //   move_uploaded_file($FILE['tmp_name'], $path.$name.'_'.date('Ymd_His').'.'.$ext);

  //   return $path.$name.'_'.date('Ymd_His').'.'.$ext;
  // }

  function constrain($elem, $type='int'){
    return isset($_POST[$elem]) ? htmlspecialchars($_POST[$elem]) : ($type=='int'? 0 : ''); 
  }
  function postval($elem, $type='string'){
    return isset($_POST[$elem]) ?  ($_POST[$elem]!=''? htmlspecialchars($_POST[$elem]) : ($type=='int'? 0 : '')) : ($type=='int'? 0 : '');
  }
  function dateval($elm){
    return isset($_POST[$elm])? ($_POST[$elm]!=''? htmlspecialchars($_POST[$elm]): NULL): NULL;
  }

  function setdate($elm){
    if(isset($_POST[$elm])){
      $d = htmlspecialchars($_POST[$elm]);
      $de = explode('/', $d);
      return $de[2].'-'.$de[1].'-'.$de[0];
    }
    else
      return '0000-00-00';
  }

  function labdate($d){
    $de = explode('-', $d);
    return count($de)==3? $de[2].'/'.$de[1].'/'.$de[0] : '-INVALID_FORMAT-';
  }

  function pdate($date){
    $date = substr($date, 0, 10);
    $de = explode('/', $date);
    return strpos($date, '-')!==false? $date : $de[2].'-'.$de[1].'-'.$de[0];
  }

  function read_level($level){
    return $level==1? '<b>Lecture seule</b>': '<b>Lecture + Ecriture</b>';
  }

  function current_annee($bdd){
    $r = $bdd->query('SELECT max(id) as an FROM annee');
    return $r->fetch()['an'];
  }

  function send_message_sms($contact, $message, $mot){
    $apiKey="1d3f4155-b46d-4fff-b677-386f3245826a";

        $smsContent=[
            "from"=>$mot, // le mot clé (nom de votre entreprise) validé sur aqilas.com
            "to"=>[$contact],
            "text"=>$message
        ];
        $jsonContent = json_encode($smsContent);

        $ch = curl_init("https://www.aqilas.com/api/v1/sms");
        $header=array('Content-Type: application/json',
            "X-AUTH-TOKEN: $apiKey");

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonContent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $json_response = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = json_decode($json_response, true);
        curl_close($ch);
        
        return $status;
  }

  function sms_send($contact, $message, $mot){
    $apiKey="1d3f4155-b46d-4fff-b677-386f3245826a";

        $smsContent=[
            "from"=>$mot, // le mot clé (nom de votre entreprise) validé sur aqilas.com
            "to"=>$contact,
            "text"=>$message
        ];
        $jsonContent = json_encode($smsContent);

        $ch = curl_init("https://www.aqilas.com/api/v1/sms");
        $header=array('Content-Type: application/json',
            "X-AUTH-TOKEN: $apiKey");

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonContent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $json_response = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = json_decode($json_response, true);
        curl_close($ch);
        
        return $status;
  }

  function send_silent_sms($contact, $message, $mot){
    $apiKey="1d3f4155-b46d-4fff-b677-386f3245826a";

        $smsContent=[
            "from"=>$mot, // le mot clé (nom de votre entreprise) validé sur aqilas.com
            "to"=>$contact,
            "text"=>$message
        ];
        $jsonContent = json_encode($smsContent);

        $ch = curl_init("https://www.aqilas.com/api/v1/sms");
        $header=array('Content-Type: application/json',
            "X-AUTH-TOKEN: $apiKey");

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonContent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $json_response = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = json_decode($json_response, true);
        curl_close($ch);
        
        // if ( $status == 201 or $status == 200 ) {
        //   echo json_encode(['success' => 1]);
        // }
        // else echo json_encode(['success' => 0]);
  }

  function getmot($bdd, $ecole){
    $r = $bdd->prepare('SELECT mot FROM ecole WHERE id=?');
    $r->execute(array($ecole));
    return $r->fetch()['mot'];
  }

?>
