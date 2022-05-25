<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_clas" />'; 
  
	$query  = 'SELECT * FROM classe_eleve'.(isset($_POST['search'])? ' WHERE '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  
?>
  
<div class="data_area_clas col s12">
	<div class="col s12">
		<div class="col m6 l6"><font class="flow-text">classe_eleve</font></div>
		<div class="col m6 l6"><button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_clas').hide(); $('.add_area_clas').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button></div>
	</div>
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="4">
				<form class="col s12 search_form_clas">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
						            <select name="option" required>
                               <option value="classe">classe</option>
                 <option value="eleve">eleve</option>
                 <option value="annee">annee</option>
            </select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_clas">Search . . .</label>
						<input type="search" id="search_text_clas" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
					</div>
					<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
				</form>
			</td>
		</tr>
		<tr>
		        <th class="table_counter">N°</th>
      <th>Classe</th>
      <th>Eleve</th>
      <th>Annee</th>
         <th>Action</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		  foreach ($entity->rawPage($query, 'date_create', $page) as $key => $value) {
			echo '<tr>
			  <td>'.($page*$entity->pageSize+$key+1).'</td>
			  <td>'.$value['classe'].'</td>
              <td>'.$value['eleve'].'</td>
              <td>'.$value['annee'].'</td>
              
			  <td>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod_clas"><i class="material-icons">edit</i></button>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btDetails_clas"><i class="material-icons">open_in_full</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'CLASSE_ELEVE_SIZE');
		?>
	  </tbody>
	</table>
	<div class="CLASSE_ELEVE_FOOT col s12"></div>
</div>

<div class="col s12 add_area_clas" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_clas"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier classe_eleve</font></div>
    </div>
   <form class="row" id="formAdd_clas">
	 <input type="hidden" name="id" id="Id_clas" value="">
	 <input type="hidden" name="action" id="Action_clas" value="create">
	 <div class="col m6 s12 input-field">
     <label for="Classe">Classe</label>
       <input type="text" name="classe" id="Classe" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Eleve">Eleve</label>
       <input type="text" name="eleve" id="Eleve" required>
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

<div class="col s12 show_area_clas" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_clas').hide(); $('.data_area_clas').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
    </div>
	<div class="col s12">
		<table class="col s12">
			<tr><td>classe</td><td class="classe_dt label_detail"></td></tr>
      <tr><td>eleve</td><td class="eleve_dt label_detail"></td></tr>
      <tr><td>annee</td><td class="annee_dt label_detail"></td></tr>
      
			<tr>
				<td colspan="2"><button class="btn-small grey darken-2 waves-effect waves-light bt_delete_clas" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button></td>
			</tr>
		</table>
	</div>
</div>
<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_clas(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/classe_eleve/classe_eleve_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.loadpane').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();
        
		$('.CLASSE_ELEVE_FOOT').pagination({
			items: parseInt($('#CLASSE_ELEVE_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_clas').val()),
			onPageClick: function(pageNumber, event){
				init_clas(pageNumber-1);
			}
		});
          //$('.CLASSE_ELEVE_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.bt_delete_clas').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/classe_eleve/classe_eleve_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
				  $('.LOADER').hide();
				  init_clas();
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
            }
          });
        });

        $('.btMod_clas').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/classe_eleve/classe_eleve_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_clas').val('update');
				               $('#Id_clas').val(data.id);
               $('#Classe').val(data.classe);
               $('#Eleve').val(data.eleve);
               $('#Annee').val(data.annee);


				$('.data_area_clas').hide();
				$('.add_area_clas').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_clas').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
		  $.post(
			'../php/modules/classe_eleve/classe_eleve_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('.bt_delete_clas').attr('data', tid);
				               $('.classe_dt').html(data.classe);
               $('.eleve_dt').html(data.eleve);
               $('.annee_dt').html(data.annee);


				$('.data_area_clas').hide();
				$('.show_area_clas').toggle('drop');
				$('.LOADER').hide();
			}
		  );
        });

        $('#formAdd_clas').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		  var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/classe_eleve/classe_eleve_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_clas')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_clas').val('create');
                  $('.LOADER').hide();
                  init_clas();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });
		
      });
	  
	  $('.bt_back_clas').click(function(){
		  $('.show_area_clas').hide();
          $('.add_area_clas').hide(); $('.data_area_clas').toggle('drop');
          init_clas();
        });
	  
	  $('.search_form_clas').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/classe_eleve/classe_eleve_util.php',
		$(this).serialize(),
		function(data){
			$('.loadpane').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
