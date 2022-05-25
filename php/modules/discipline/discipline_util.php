<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_disc" />'; 
  
	$query  = 'SELECT * FROM discipline'.(isset($_POST['search'])? ' WHERE '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  
?>
  
<div class="data_area_disc col s12">
	<div class="col s12">
		<div class="col m6 l6"><font class="flow-text">discipline</font></div>
		<div class="col m6 l6"><button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_disc').hide(); $('.add_area_disc').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button></div>
	</div>
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="4">
				<form class="col s12 search_form_disc">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
						            <select name="option" required>
                               <option value="eleve">eleve</option>
                 <option value="note">note</option>
                 <option value="surveillant">surveillant</option>
            </select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_disc">Search . . .</label>
						<input type="search" id="search_text_disc" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
					</div>
					<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
				</form>
			</td>
		</tr>
		<tr>
		        <th class="table_counter">N°</th>
      <th>Eleve</th>
      <th>Note</th>
      <th>Surveillant</th>
         <th>Action</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		  foreach ($entity->rawPage($query, 'date_create', $page) as $key => $value) {
			echo '<tr>
			  <td>'.($page*$entity->pageSize+$key+1).'</td>
			  <td>'.$value['eleve'].'</td>
              <td>'.$value['note'].'</td>
              <td>'.$value['surveillant'].'</td>
              
			  <td>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod_disc"><i class="material-icons">edit</i></button>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btDetails_disc"><i class="material-icons">open_in_full</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'DISCIPLINE_SIZE');
		?>
	  </tbody>
	</table>
	<div class="DISCIPLINE_FOOT col s12"></div>
</div>

<div class="col s12 add_area_disc" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_disc"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier discipline</font></div>
    </div>
   <form class="row" id="formAdd_disc">
	 <input type="hidden" name="id" id="Id_disc" value="">
	 <input type="hidden" name="action" id="Action_disc" value="create">
	 <div class="col m6 s12 input-field">
     <label for="Eleve">Eleve</label>
       <input type="text" name="eleve" id="Eleve" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Note">Note</label>
       <input type="text" name="note" id="Note" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Surveillant">Surveillant</label>
       <input type="text" name="surveillant" id="Surveillant" required>
    </div>


	 <div class="col m12 s12 input-field">
	   <button type="submit" class="btn white black-text waves-effect" name="button"><i class="material-icons left">done</i>Enregistrer</button>
	 </div>
   </form>
</div>

<div class="col s12 show_area_disc" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_disc').hide(); $('.data_area_disc').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
    </div>
	<div class="col s12">
		<table class="col s12">
			<tr><td>eleve</td><td class="eleve_dt label_detail"></td></tr>
      <tr><td>note</td><td class="note_dt label_detail"></td></tr>
      <tr><td>surveillant</td><td class="surveillant_dt label_detail"></td></tr>
      
			<tr>
				<td colspan="2"><button class="btn-small grey darken-2 waves-effect waves-light bt_delete_disc" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button></td>
			</tr>
		</table>
	</div>
</div>
<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_disc(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/discipline/discipline_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.loadpane').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();
        
		$('.DISCIPLINE_FOOT').pagination({
			items: parseInt($('#DISCIPLINE_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_disc').val()),
			onPageClick: function(pageNumber, event){
				init_disc(pageNumber-1);
			}
		});
          //$('.DISCIPLINE_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.bt_delete_disc').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/discipline/discipline_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
				  $('.LOADER').hide();
				  init_disc();
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
            }
          });
        });

        $('.btMod_disc').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/discipline/discipline_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_disc').val('update');
				               $('#Id_disc').val(data.id);
               $('#Eleve').val(data.eleve);
               $('#Note').val(data.note);
               $('#Surveillant').val(data.surveillant);


				$('.data_area_disc').hide();
				$('.add_area_disc').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_disc').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
		  $.post(
			'../php/modules/discipline/discipline_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('.bt_delete_disc').attr('data', tid);
				               $('.eleve_dt').html(data.eleve);
               $('.note_dt').html(data.note);
               $('.surveillant_dt').html(data.surveillant);


				$('.data_area_disc').hide();
				$('.show_area_disc').toggle('drop');
				$('.LOADER').hide();
			}
		  );
        });

        $('#formAdd_disc').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		  var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/discipline/discipline_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_disc')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_disc').val('create');
                  $('.LOADER').hide();
                  init_disc();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });
		
      });
	  
	  $('.bt_back_disc').click(function(){
		  $('.show_area_disc').hide();
          $('.add_area_disc').hide(); $('.data_area_disc').toggle('drop');
          init_disc();
        });
	  
	  $('.search_form_disc').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/discipline/discipline_util.php',
		$(this).serialize(),
		function(data){
			$('.loadpane').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
