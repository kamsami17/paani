<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  if(isset($_POST['sel_classe'])) $_SESSION['sel_classe'] = htmlspecialchars($_POST['sel_classe']);
  echo '<input type="hidden" id="SEL_CLASSE_ID" value="'.$_SESSION['sel_classe'].'" />';

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_scho" />'; 
  
	$query  = 'SELECT * FROM school_fee WHERE classe='.$_SESSION['sel_classe'].(isset($_POST['search'])? ' AND '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  $q = 'SELECT * FROM eleve WHERE classe='.$_SESSION['sel_classe'];
  
?>
<fieldset>
	<legend>
		<button class="btn btn_back_fee"><i class="material-icons">arrow_back</i></button>
		Scholarité détails
	</legend>

<div class="data_area_scho col s12">
	<div class="col s12">
		<div class="col m6 l4"><font class="flow-text">school_fee</font></div>
		<div class="col m6 l8">
      <button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_scho').hide(); $('.add_area_scho').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button>
      <button class="waves-effect waves-light btn btn-small blue bt_import_fee"><i class="material-icons left">add</i>Importer</button>
    </div>
	</div>
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="5">
				<form class="col s12 search_form_scho">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
						  <select name="option" required>
                 <option value="classe">classe</option>
                 <option value="eleve">eleve</option>
                 <option value="montant">montant</option>
                 <option value="reste">reste</option>
            </select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_scho">Search . . .</label>
						<input type="search" id="search_text_scho" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
					</div>
					<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
				</form>
			</td>
		</tr>
		<tr>
      <th class="table_counter">N°</th>
      <!-- <th>Classe</th> -->
      <th>Eleve</th>
      <th>Montant</th>
      <th>Reste</th>
      <th>Action</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		  foreach ($entity->rawPage($query, 'date_create', $page) as $key => $value) {
			echo '<tr>
			  <td>'.($page*$entity->pageSize+$key+1).'</td>
        <td>'.Eleve::get_lib($bdd, $value['eleve']).'</td>
        <td>'.$value['montant'].'</td>
        <td>'.$value['reste'].'</td>      
			  <td>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod_scho"><i class="material-icons">edit</i></button>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btDetails_scho"><i class="material-icons">open_in_full</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'SCHOOL_FEE_SIZE');
		?>
	  </tbody>
	</table>
	<div class="SCHOOL_FEE_FOOT col s12"></div>
</div>

<div class="col s12 add_area_scho" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_scho"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier school_fee</font></div>
    </div>
   <form class="row" id="formAdd_scho">
	 <input type="hidden" name="id" id="Id_scho" value="">
	 <input type="hidden" name="action" id="Action_scho" value="create">
	 <!-- <div class="col m6 s12 input-field">
     <label for="Classe">Classe</label>
       <input type="text" name="classe" id="Classe" required>
    </div> -->
<div class="col m6 s12 input-field">
     <label for="Eleve">Eleve</label>
       <input type="text" name="eleve" id="Eleve" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Montant">Montant</label>
       <input type="text" name="montant" id="Montant" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Reste">Reste</label>
       <input type="text" name="reste" id="Reste" required>
    </div>


	 <div class="col m12 s12 input-field">
	   <button type="submit" class="btn white black-text waves-effect" name="button"><i class="material-icons left">done</i>Enregistrer</button>
	 </div>
   </form>
</div>

<div class="col s12 show_area_scho" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_scho').hide(); $('.data_area_scho').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
    </div>
	<div class="col s12">
		<table class="col s12">
			<tr><td>classe</td><td class="classe_dt label_detail"></td></tr>
      <tr><td>eleve</td><td class="eleve_dt label_detail"></td></tr>
      <tr><td>montant</td><td class="montant_dt label_detail"></td></tr>
      <tr><td>reste</td><td class="reste_dt label_detail"></td></tr>
      
			<tr>
				<td colspan="2"><button class="btn-small grey darken-2 waves-effect waves-light bt_delete_scho" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button></td>
			</tr>
		</table>
	</div>
</div>

<div id="modal_import" class="modal">
  <div class="modal-content col s12">
    <form class="import_form col s12">
      <input type="hidden" name="action" value="import" />
      <input type="hidden" name="classe" value="" id="Classe_id" />
      <font class="col s12 center flow-text">Importer une liste</font>
      <div class="col s12 input-field">
        <div class="file-field input-field">
          <div class="btn">
            <span>Fichier</span>
            <input type="file" name="liste" />
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text" placeholder="Upload one or more files">
          </div>
        </div>
      </div>
      <div class="col s12">
        <button type="submit" class="btn-small blue darken-4 waves-effect waves-light">Importer</button>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <a href="#!" class="modal-close waves-effect waves-green btn-flat">fermer</a>
  </div>
</div>
<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_scho(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/school_fee/school_fee_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.load_area').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();
        $('.modal').modal();

        $('.bt_import_fee').click(function(){
          $('#modal_import').modal('open');
          $('#Classe_id').val($('#SEL_CLASSE_ID').val());
        });

        $('.btn_back_fee').click(function(){
          $('.LOADER').show();
          $.post(
            '../php/modules/fee/fee_util.php',
            function(data){
              $('.load_area').html(data);
              $('.LOADER').hide();
            }
          );
        });
        
        $('.SCHOOL_FEE_FOOT').pagination({
          items: parseInt($('#SCHOOL_FEE_SIZE').val()),
          itemsOnPage: PAGE_SIZE,
          cssStyle: 'light-theme',
          currentPage: parseInt($('#CURR_PAGE_scho').val()),
          onPageClick: function(pageNumber, event){
            init_scho(pageNumber-1);
          }
        });
        $('.import_form').submit(function(e){
          	e.preventDefault();
          	$('.LOADER').show();
            var fd = new FormData($(this)[0]);
            $.ajax({
                url : '../php/modules/school_fee/school_fee_data.php',
                data : fd,
                type : 'POST',
                processData: false,
                contentType: false,
                success : function(data){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#modal_import').modal('close');
                  $('.LOADER').hide();
                },
                error: function(resp){
                  $('.LOADER').hide();
                  swal('Erreur', 'Une erreur est survenue', 'error');
                }
              });
            });
          //$('.SCHOOL_FEE_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.bt_delete_scho').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/school_fee/school_fee_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
				  $('.LOADER').hide();
				  init_scho();
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
            }
          });
        });

        $('.btMod_scho').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/school_fee/school_fee_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_scho').val('update');
        $('#Id_scho').val(data.id);
        $('#Eleve').val(data.eleve);
        $('#Montant').val(data.montant);
        $('#Reste').val(data.reste);

				$('.data_area_scho').hide();
				$('.add_area_scho').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_scho').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
		  $.post(
			'../php/modules/school_fee/school_fee_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('.bt_delete_scho').attr('data', tid);
				               $('.classe_dt').html(data.classe);
               $('.eleve_dt').html(data.eleve);
               $('.montant_dt').html(data.montant);
               $('.reste_dt').html(data.reste);


				$('.data_area_scho').hide();
				$('.show_area_scho').toggle('drop');
				$('.LOADER').hide();
			}
		  );
        });

        $('#formAdd_scho').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		  var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/school_fee/school_fee_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_scho')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_scho').val('create');
                  $('.LOADER').hide();
                  init_scho();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });
		
      });
	  
	  $('.bt_back_scho').click(function(){
		  $('.show_area_scho').hide();
          $('.add_area_scho').hide(); $('.data_area_scho').toggle('drop');
          init_scho();
        });
	  
	  $('.search_form_scho').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/school_fee/school_fee_util.php',
		$(this).serialize(),
		function(data){
			$('.load_area').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
