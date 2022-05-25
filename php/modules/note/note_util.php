<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_note" />'; 
  $MATIERE = htmlspecialchars($_POST['matiere']);
  $CMat = Matiere::read($bdd, $MATIERE);
  echo '<input type="hidden" value="'.$MATIERE.'" id="MATIERE" />'; 

  $TRIM = isset($_POST['trimestre'])? htmlspecialchars($_POST['trimestre']) : Trimestre::last_trim($bdd, $_SESSION['classe']->id);
	$query  = 'SELECT * FROM note WHERE matiere='.$MATIERE.(isset($_POST['search'])? ' AND '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  
  function pretty_note($list){
    $result = '';
    foreach($list as $note){
      $result .= '<button class="btn-flat waves-effect waves-teal"><b>'.$note['note'].'</b></button>';
    }
    return $result;
  }
?>
  
<div class="data_area_note col s12">
	<div class="col s12">
		<div class="col m6 l6">
      <font class="flow-text">Notes de <i><?php echo $CMat->name.' ('.$CMat->code.')'; ?></i></font>
      <div class="col s12 center"><b><i><?php echo Trimestre::get_lib($bdd, $TRIM); ?></i></b></div>
    </div>
		<div class="col m6 l6">
      <!-- <button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_note').hide(); $('.add_area_note').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button> -->
      <button class="btn white blue-text waves-effect bt_gen_fich">Fiche à remplir</button>
      <a class="btn white blue darken-4 waves-effect modal-trigger" href="#modal_import">Importer notes</a>
    </div>
	</div>

  <div class="col s12">
    <?php 
      foreach(Trimestre::get_all($bdd, $_SESSION['classe']->id) as $trim){
        echo '<button class="btn-small btn-flat blue-text text-darken-4 bt_trim_filter" data="'.$trim->id.'">'.$trim->lib.'</button>';
      }
    ?>
  </div>

  <table class="col s12 striped">
    <thead>
      <tr>
        <th>Matricule</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Date naissance</th>
      </tr>
    </thead>
    <tbody>
      <?php 
        foreach(Eleve::get_classe($bdd, $_SESSION['classe']->id, $_SESSION['annee']) as $enote){
          echo '<tr>
            <td colspan="4">
              <table class="col s12">
                <tr>
                  <td style="padding: 2px !important;">'.$enote['matricule'].'</td>
                  <td style="padding: 2px !important;">'.$enote['nom'].'</td>
                  <td style="padding: 2px !important;">'.$enote['prenom'].'</td>
                  <td style="padding: 2px !important;">'.labdate($enote['date_naissance']).'</td>
                </tr>
                <tr>
                  <td colspan="4" style="padding: 2px !important;">'.pretty_note(Note::list_mat($bdd, $enote['matricule'], $MATIERE, $TRIM)).'</td>
                </tr>
              </table>
            </td>
            
          </tr>';
        }
      ?>
    </tbody>
  </table>

	<div class="NOTE_FOOT col s12"></div>
</div>

<div class="col s12 add_area_note" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_note"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier note</font></div>
    </div>
   <form class="row" id="formAdd_note">
	 <input type="hidden" name="id" id="Id_note" value="">
	 <input type="hidden" name="action" id="Action_note" value="create">
	 <div class="col m6 s12 input-field">
     <label for="Matricule">Matricule</label>
       <input type="text" name="matricule" id="Matricule" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Matiere">Matiere</label>
       <input type="text" name="matiere" id="Matiere" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Note">Note</label>
       <input type="text" name="note" id="Note" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Trimestre">Trimestre</label>
       <input type="text" name="trimestre" id="Trimestre" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Annee">Annee</label>
       <input type="text" name="annee" id="Annee" required>
    </div>


	 <div class="col m12 s12 input-field">
	   <button type="submit" class="btn white black-text waves-effect" name="button"><i class="material-icons left">done</i>Enregistrer</button>
	 </div>
   </form>
</div>

<div class="col s12 show_area_note" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_note').hide(); $('.data_area_note').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
    </div>
	<div class="col s12">
		<table class="col s12">
			<tr><td>matricule</td><td class="matricule_dt label_detail"></td></tr>
      <tr><td>matiere</td><td class="matiere_dt label_detail"></td></tr>
      <tr><td>note</td><td class="note_dt label_detail"></td></tr>
      <tr><td>trimestre</td><td class="trimestre_dt label_detail"></td></tr>
      <tr><td>annee</td><td class="annee_dt label_detail"></td></tr>
			<tr>
				<td colspan="2"><button class="btn-small grey darken-2 waves-effect waves-light bt_delete_note" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button></td>
			</tr>
		</table>
	</div>
</div>

<div id="modal_import" class="modal">
    <div class="modal-content">
      <form class="form_import_note col m12" enctype="multipart/form-data">
        <input type="hidden" name="action" value="import">
        <?php echo '<input type="hidden" value="'.$MATIERE.'" name="matiere" />'; ?>
        <div class="col m12">
          <font class="flow-text">Importer une fiche de notes</font>
        </div>
        <div class="input-field col m6">
          <input type="file" name="fiche_note" class="fiche_note" />
        </div>
        <div class="col m6 input-field">
          <select name="trimestre" class="browser-default" required>
            <option value="" disabled selected>Trimestre</option>
            <?php
              foreach (Trimestre::get_all($bdd, $_SESSION['classe']->id) as $key => $nc) {
                echo '<option value="'.$nc->id.'">'.$nc->lib.'</option>';
              }
              ?>
          </select>
        </div>
        <div class="col m12 input-field">
          <button type="submit" class="btn white black-text waves-effect" name="button"><i class="material-icons left">done</i>Enregistrer</button>
          <button type="button" class="modal-close btn right white red-text waves-effect" name="button"><i class="material-icons left">clear</i>Fermer</button>
        </div>
      </form>
    </div>
  </div>

  <style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_note(pg){
      pg = pg || 0;
      $('.LOADER').show();
      $.post('../php/modules/note/note_util.php',
        {page: pg, matiere: $('#MATIERE').val()},
        function(data){
          $('.LOADER').hide();
          $('.loadpane').html(data);
      });
    }

    $('.bt_trim_filter').click(function(){
      $('.LOADER').show();
      $.post('../php/modules/note/note_util.php',
        { page: 0, matiere: $('#MATIERE').val(), trimestre: $(this).attr('data') },
        function(data){
          $('.LOADER').hide();
          $('.loadpane').html(data);
      });
    });
		
      $(document).ready(function(){
        $('select').formSelect();
        $('.modal').modal();
        
		$('.NOTE_FOOT').pagination({
			items: parseInt($('#NOTE_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_note').val()),
			onPageClick: function(pageNumber, event){
				init_note(pageNumber-1);
			}
		});
          //$('.NOTE_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.bt_delete_note').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/note/note_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
				  $('.LOADER').hide();
				  init_note();
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
            }
          });
        });

        $('.btMod_note').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/note/note_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_note').val('update');
        $('#Id_note').val(data.id);
        $('#Matricule').val(data.matricule);
        $('#Matiere').val(data.matiere);
        $('#Note').val(data.note);
        $('#Trimestre').val(data.trimestre);
        $('#Annee').val(data.annee);


				$('.data_area_note').hide();
				$('.add_area_note').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_note').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
		  $.post(
			'../php/modules/note/note_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('.bt_delete_note').attr('data', tid);
				               $('.matricule_dt').html(data.matricule);
               $('.matiere_dt').html(data.matiere);
               $('.note_dt').html(data.note);
               $('.trimestre_dt').html(data.trimestre);
               $('.annee_dt').html(data.annee);


				$('.data_area_note').hide();
				$('.show_area_note').toggle('drop');
				$('.LOADER').hide();
			}
		  );
        });

        $('.form_import_note').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
          var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/note/note_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data=='done'){
                  swal('Notification', 'Importation avec succès', 'success');
                  $('.LOADER').hide();
                  $('#modal_import').modal('close');
                  init_note();
                }else{
                  swal('Erreur', 'Une erreur est survenue', 'error');
                  $('.LOADER').hide();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });

        $('#formAdd_note').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
          var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/note/note_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_note')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_note').val('create');
                  $('.LOADER').hide();
                  init_note();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });

        $('.bt_gen_fich').click(function(){
          $('.LOADER').show();
          $.post('../php/modules/note/note_data.php',
          {action: 'gen_fich'},
          function(data){
            $('.LOADER').hide();
            document.location.href = data;
            //alert(data);
          });
        }); 
		
      });
	  
	  $('.bt_back_note').click(function(){
		  $('.show_area_note').hide();
          $('.add_area_note').hide(); $('.data_area_note').toggle('drop');
          init_note();
        });
	  
	  $('.search_form_note').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/note/note_util.php',
		$(this).serialize(),
		function(data){
			$('.loadpane').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
