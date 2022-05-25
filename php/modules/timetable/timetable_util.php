<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_time" />'; 
  
	$query  = 'SELECT * FROM timetable'.(isset($_POST['search'])? ' WHERE '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  
?>
  
<div class="data_area_time col s12">
	<div class="col s12">
		<div class="col m6 l6"><font class="flow-text">timetable</font></div>
		<div class="col m6 l6"><button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_time').hide(); $('.add_area_time').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button></div>
	</div>
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="8">
				<form class="col s12 search_form_time">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
						            <select name="option" required>
                               <option value="mat">mat</option>
                 <option value="heure">heure</option>
                 <option value="jour">jour</option>
                 <option value="prof">prof</option>
                 <option value="act">act</option>
                 <option value="classe">classe</option>
                 <option value="annee">annee</option>
            </select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_time">Search . . .</label>
						<input type="search" id="search_text_time" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
					</div>
					<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
				</form>
			</td>
		</tr>
		<tr>
		        <th class="table_counter">N°</th>
      <th>Mat</th>
      <th>Heure</th>
      <th>Jour</th>
      <th>Prof</th>
      <th>Act</th>
      <th>Classe</th>
      <th>Annee</th>
         <th>Action</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		  foreach ($entity->rawPage($query, 'date_create', $page) as $key => $value) {
			echo '<tr>
			  <td>'.($page*$entity->pageSize+$key+1).'</td>
			  <td>'.$value['mat'].'</td>
              <td>'.$value['heure'].'</td>
              <td>'.$value['jour'].'</td>
              <td>'.$value['prof'].'</td>
              <td>'.$value['act'].'</td>
              <td>'.$value['classe'].'</td>
              <td>'.$value['annee'].'</td>
              
			  <td>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod_time"><i class="material-icons">edit</i></button>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btDetails_time"><i class="material-icons">open_in_full</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'TIMETABLE_SIZE');
		?>
	  </tbody>
	</table>
	<div class="TIMETABLE_FOOT col s12"></div>
</div>

<div class="col s12 add_area_time" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_time"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier timetable</font></div>
    </div>
   <form class="row" id="formAdd_time">
	 <input type="hidden" name="id" id="Id_time" value="">
	 <input type="hidden" name="action" id="Action_time" value="create">
	 <div class="col m6 s12 input-field">
     <label for="Mat">Mat</label>
       <input type="text" name="mat" id="Mat" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Heure">Heure</label>
       <input type="text" name="heure" id="Heure" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Jour">Jour</label>
       <input type="text" name="jour" id="Jour" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Prof">Prof</label>
       <input type="text" name="prof" id="Prof" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Act">Act</label>
       <input type="text" name="act" id="Act" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Classe">Classe</label>
       <input type="text" name="classe" id="Classe" required>
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

<div class="col s12 show_area_time" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_time').hide(); $('.data_area_time').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
    </div>
	<div class="col s12">
		<table class="col s12">
			<tr><td>mat</td><td class="mat_dt label_detail"></td></tr>
      <tr><td>heure</td><td class="heure_dt label_detail"></td></tr>
      <tr><td>jour</td><td class="jour_dt label_detail"></td></tr>
      <tr><td>prof</td><td class="prof_dt label_detail"></td></tr>
      <tr><td>act</td><td class="act_dt label_detail"></td></tr>
      <tr><td>classe</td><td class="classe_dt label_detail"></td></tr>
      <tr><td>annee</td><td class="annee_dt label_detail"></td></tr>
      
			<tr>
				<td colspan="2"><button class="btn-small grey darken-2 waves-effect waves-light bt_delete_time" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button></td>
			</tr>
		</table>
	</div>
</div>
<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_time(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/timetable/timetable_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.loadpane').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();
        
		$('.TIMETABLE_FOOT').pagination({
			items: parseInt($('#TIMETABLE_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_time').val()),
			onPageClick: function(pageNumber, event){
				init_time(pageNumber-1);
			}
		});
          //$('.TIMETABLE_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.bt_delete_time').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/timetable/timetable_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
				  $('.LOADER').hide();
				  init_time();
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
            }
          });
        });

        $('.btMod_time').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/timetable/timetable_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_time').val('update');
				               $('#Id_time').val(data.id);
               $('#Mat').val(data.mat);
               $('#Heure').val(data.heure);
               $('#Jour').val(data.jour);
               $('#Prof').val(data.prof);
               $('#Act').val(data.act);
               $('#Classe').val(data.classe);
               $('#Annee').val(data.annee);


				$('.data_area_time').hide();
				$('.add_area_time').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_time').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
		  $.post(
			'../php/modules/timetable/timetable_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('.bt_delete_time').attr('data', tid);
				               $('.mat_dt').html(data.mat);
               $('.heure_dt').html(data.heure);
               $('.jour_dt').html(data.jour);
               $('.prof_dt').html(data.prof);
               $('.act_dt').html(data.act);
               $('.classe_dt').html(data.classe);
               $('.annee_dt').html(data.annee);


				$('.data_area_time').hide();
				$('.show_area_time').toggle('drop');
				$('.LOADER').hide();
			}
		  );
        });

        $('#formAdd_time').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		  var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/timetable/timetable_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_time')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_time').val('create');
                  $('.LOADER').hide();
                  init_time();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });
		
      });
	  
	  $('.bt_back_time').click(function(){
		  $('.show_area_time').hide();
          $('.add_area_time').hide(); $('.data_area_time').toggle('drop');
          init_time();
        });
	  
	  $('.search_form_time').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/timetable/timetable_util.php',
		$(this).serialize(),
		function(data){
			$('.loadpane').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
