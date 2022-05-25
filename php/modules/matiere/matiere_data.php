<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Matiere::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		$matiere = new Matiere();
        $matiere->name = htmlspecialchars($_POST['name']);
        $matiere->code = htmlspecialchars($_POST['code']);
        $matiere->coef = htmlspecialchars($_POST['coef']);
        $matiere->classe = $_SESSION['classe']->id;
        $matiere->create($bdd);
		echo 'create';
      break;
    case 'update':
		$matiere = new Matiere();
        $matiere->id = htmlspecialchars($_POST['id']);
        $matiere->name = htmlspecialchars($_POST['name']);
        $matiere->code = htmlspecialchars($_POST['code']);
        $matiere->coef = htmlspecialchars($_POST['coef']);
        $matiere->classe = $_SESSION['classe']->id;
        $matiere->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Matiere::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

    case 'get_all':
        echo '<ul class="collection">';
        foreach(Matiere::get_all($bdd, $_SESSION['classe']->id) as $mat){
          echo '<li data="'.$mat->id.'" class="collection-item mat_item"><i class="material-icons left">label_important</i>'.$mat->name.' ('.$mat->code.')</li>';
        }
        echo '</ul>'; ?>
        <script>
          $('.mat_item').click(function(){
            $('.LOADER').show();
            $.post(
              '../php/modules/note/note_util.php',
              {action: '', matiere: $(this).attr('data')},
              function(data){
                $('.LOADER').hide();
                $('#modal_open_note').modal('close');
                $('.loadpane').html(data);
              }
            );
          });
        </script>
    <?php
      break;

  }
?>