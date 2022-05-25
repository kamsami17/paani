<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  //$_SESSION['classe'] = isset($_POST['classe'])? htmlspecialchars($_POST['classe']) : $_SESSION['classe'];

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  //$_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_elev" />'; 
  
	$query  = 'SELECT * FROM eleve WHERE id IN (SELECT eleve FROM classe_eleve WHERE classe='.$_SESSION['classe']->id.' AND annee='.$_SESSION['annee'].')'.(isset($_POST['search'])? ' AND '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  
?>
  
<div class="data_area_elev col s12">
	<div class="col s12">
		<div class="col m6 l6"><font class="flow-text">Gestion des élèves</font></div>
		<div class="col m6 l6">
      <button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_elev').hide(); $('.add_area_elev').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button>
      <button class="waves-effect waves-light btn btn-small blue darken-4 btn_import"><i class="material-icons left">add</i>Importer (xlsx)</button>
    </div>
	</div>
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="8">
				<form class="col s12 search_form_elev">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
              <select name="option" required>
                <option value="nom">nom</option>
                 <option value="prenom">prenom</option>
                 <option value="date_naissance">date_naissance</option>
                 <option value="matricule">matricule</option>
                 <option value="uname">uname</option>
                 <option value="passwd">passwd</option>
                 <option value="parent">parent</option>
              </select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_elev">Search . . .</label>
						<input type="search" id="search_text_elev" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
					</div>
					<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
				</form>
			</td>
		</tr>
		<tr>
		        <th class="table_counter">N°</th>
        <th>Nom</th>
        <th>Prenom</th>
        <!-- <th>Date de naissance</th> -->
        <th>Matricule</th>
        <!-- <th>Uname</th>
        <th>Passwd</th>
        <th>Parent</th> -->
        <th>Parent</th>
         <th>Action</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		  foreach ($entity->rawPage($query, 'concat(nom,prenom)', $page, 'ASC') as $key => $value) {
        if($value['parents']!=0) $pe = Parents::read($bdd, $value['parents']);
			echo '<tr>
			  <td>'.($page*$entity->pageSize+$key+1).'</td>
			  <td>'.$value['nom'].'</td>
          <td>'.$value['prenom'].'</td>
          <!--td>'.labdate($value['date_naissance']).'</td-->
          <td>'.$value['matricule'].'</td>
          <td>'.($value['parents']!=0? $pe->nom.' '.$pe->prenom.' (<i>'.$pe->contact.'<i>)': '').'</td>
			  <td>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod_elev"><i class="material-icons">edit</i></button>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btDetails_elev"><i class="material-icons">open_in_full</i></button>
        <button data="'.$value['id'].'" type="button" class="btn-flat waves-effect bt_add_phone"><i class="material-icons blue-text">phone_enabled</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'ELEVE_SIZE');
		?>
	  </tbody>
	</table>
	<div class="ELEVE_FOOT col s12"></div>
</div>

<div class="col s12 add_area_elev" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_elev"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier eleve</font></div>
    </div>
   <form class="row" id="formAdd_elev">
	 <input type="hidden" name="id" id="Id_elev" value="">
	 <input type="hidden" name="action" id="Action_elev" value="create">
	 <div class="col m6 s12 input-field">
     <label for="Nom">Nom</label>
       <input type="text" name="nom" id="Nom" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Prenom">Prenom</label>
       <input type="text" name="prenom" id="Prenom" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Date_naissance">Date naissance (jj/mm/aaaa)</label>
       <input type="text" name="date_naissance" id="Date_naissance" required>
    </div>
    <div class="col m6 s12 input-field">
      <label for="Matricule">Matricule</label>
      <input type="text" name="matricule" id="Matricule" required>
    </div>
    <div class="col m6 s12 input-field">
      <div class="col s6">
        <label>
          <input name="sexe" value="M" type="radio" checked />
          <span>Masculin</span>
        </label>
      </div>
      <div class="col s6">
        <label>
          <input name="sexe" value="F" type="radio" />
          <span>Féminin</span>
        </label>
      </div>
    </div>
<!-- <div class="col m6 s12 input-field">
     <label for="Uname">Uname</label>
       <input type="text" name="uname" id="Uname" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Passwd">Passwd</label>
       <input type="text" name="passwd" id="Passwd" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Parent">Parent</label>
       <input type="text" name="parent" id="Parent" required>
    </div> -->


	 <div class="col m12 s12 input-field">
	   <button type="submit" class="btn white black-text waves-effect" name="button"><i class="material-icons left">done</i>Enregistrer</button>
	 </div>
   </form>
</div>

<div class="col s12 show_area_elev" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_elev').hide(); $('.data_area_elev').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
    </div>
	<div class="col s12">
		<table class="col s12">
			<tr><td>nom</td><td class="nom_dt label_detail"></td></tr>
      <tr><td>prenom</td><td class="prenom_dt label_detail"></td></tr>
      <tr><td>date de naissance</td><td class="date_naissance_dt label_detail"></td></tr>
      <tr><td>sexe</td><td class="sexe_dt label_detail"></td></tr>
      <tr><td>matricule</td><td class="matricule_dt label_detail"></td></tr>
      <tr><td>uname</td><td class="uname_dt label_detail"></td></tr>
      <!-- <tr><td>passwd</td><td class="passwd_dt label_detail"></td></tr> -->
      <tr><td>parent</td><td>
        <span class="parent_dt label_detail"></span>
        <button class="btn-small btn-flat waves-effect waves-light bt_add_parent blue-text" data="" type="button"><i class="material-icons blue-text left">person</i>Parent</button>
      </td></tr>
      
			<tr>
				<td colspan="2">
          <button class="btn-small grey darken-2 waves-effect waves-light bt_delete_elev" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button>
        </td>
			</tr>
		</table>
	</div>
</div>

<div id="modal_import" class="modal">
  <div class="modal-content">
    <form class="import_list col m12" enctype="multipart/form-data">
      <input type="hidden" name="action" value="import_alt2">
      <div class="col s12">
        <a href="../FILES/Liste_eleve.xlsx" class="btn-flat blue-text waves-effect">Fiche modèle<i class="material-icons right">cloud_download</i></a>
      </div>
      <div class="col m12">
        <font class="flow-text">Importer une liste (Excel)</font>
      </div>
      <div class="input-field col m8">
        <input type="file" name="classFile" class="classFile" />
      </div>
      <div class="input-field col m4">
        <input type="submit" class="btn waves-effect waves-light blue darken-2" value="Importer" />
      </div>
    </form>
  </div>
</div>

<div id="modal_telephone" class="modal">
  <div class="modal-content">
    <form class="form_telephone col m12">
      <input type="hidden" name="action" value="phone" />
      <input type="hidden" name="eleve" value="" id="EL_ID" />
      <div class="col m12">
        <font class="flow-text">Contact du parent</font>
      </div>
      <div class="input-field col m8">
        <label for="Telephone">Numéro de téléphone</label>
        <input type="text" name="telephone" id="Telephone" />
      </div>
      <div class="input-field col m4">
        <input type="submit" class="btn waves-effect waves-light blue darken-2" value="Valider" />
      </div>
    </form>
  </div>
</div>

<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_elev(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/eleve/eleve_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.loadpane').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();
        $('.modal').modal();
        
		$('.ELEVE_FOOT').pagination({
			items: parseInt($('#ELEVE_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_elev').val()),
			onPageClick: function(pageNumber, event){
				init_elev(pageNumber-1);
			}
		});
          //$('.ELEVE_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.bt_add_phone').click(function(){
          $('#EL_ID').val($(this).attr('data'));
          $('#modal_telephone').modal('open');
        });
        $('.form_telephone').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
          $.post(
            '../php/modules/eleve/eleve_data.php',
            $(this).serialize(),
            function(data){
              $('#modal_telephone').modal('close');
              $('.LOADER').hide();
              if(data=='done'){
                swal('Notification', 'Edition avec succès', 'success');
                init_elev();
              }
              else{
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
            }
          );
        });

        $('.bt_delete_elev').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/eleve/eleve_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
                  $('.LOADER').hide();
                  if(data=='done'){
                    swal('Notification', 'Suppression avec succès!', 'success');
                    init_elev();
                  }
                  else
                    swal('Notification', 'Suppression avec succès!', 'success')
                }
              );
            }
          });
        });

        $('.btMod_elev').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/eleve/eleve_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_elev').val('update');
        $('#Id_elev').val(data.id);
        $('#Nom').val(data.nom);
        $('#Prenom').val(data.prenom);
        $('#Date_naissance').val(data.date_naissance);
        $('#Sexe').val(data.sexe);
        $('#Matricule').val(data.matricule);
        $('#Uname').val(data.uname);
        $('#Passwd').val(data.passwd);
        $('#Parent').val(data.parent);


				$('.data_area_elev').hide();
				$('.add_area_elev').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_elev').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
		  $.post(
			'../php/modules/eleve/eleve_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('.bt_delete_elev').attr('data', tid);
        $('.nom_dt').html(data.nom);
          $('.prenom_dt').html(data.prenom);
          $('.date_naissance_dt').html(data.date_naissance);
          $('.matricule_dt').html(data.matricule);
          $('.uname_dt').html(data.uname);
          $('.sexe_dt').html(data.sexe);
          $('.passwd_dt').html(data.passwd);
          $('.parent_dt').html(data.parent);


				$('.data_area_elev').hide();
				$('.show_area_elev').toggle('drop');
				$('.LOADER').hide();
			}
		  );
        });

      $('.import_list').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		      var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/eleve/eleve_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                swal('Notification', 'Création avec succès', 'success');
                $('#modal_import').modal('close');
                init_elev();
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });

        $('#formAdd_elev').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		  var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/eleve/eleve_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_elev')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_elev').val('create');
                  $('.LOADER').hide();
                  init_elev();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });

        $('.btn_import').click(function(){
          $('#modal_import').modal('open');
        });
		
      });
	  
	  $('.bt_back_elev').click(function(){
		  $('.show_area_elev').hide();
          $('.add_area_elev').hide(); $('.data_area_elev').toggle('drop');
          init_elev();
        });
	  
	  $('.search_form_elev').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/eleve/eleve_util.php',
		$(this).serialize(),
		function(data){
			$('.loadpane').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
