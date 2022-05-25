<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_anne" />'; 
  
	$query  = 'SELECT * FROM annee'.(isset($_POST['search'])? ' WHERE '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  
?>
  
<div class="data_area_anne col s12">
	<div class="col s12">
		<div class="col m6 l6"><font class="flow-text">annee</font></div>
		<div class="col m6 l6"><button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_anne').hide(); $('.add_area_anne').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button></div>
	</div>
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="2">
				<form class="col s12 search_form_anne">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
						            <select name="option" required>
                               <option value="name">name</option>
            </select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_anne">Search . . .</label>
						<input type="search" id="search_text_anne" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
					</div>
					<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
				</form>
			</td>
		</tr>
		<tr>
		        <th class="table_counter">N°</th>
      <th>Name</th>
         <th>Action</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		  foreach ($entity->rawPage($query, 'date_create', $page) as $key => $value) {
			echo '<tr>
			  <td>'.($page*$entity->pageSize+$key+1).'</td>
			  <td>'.$value['name'].'</td>
              
			  <td>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod_anne"><i class="material-icons">edit</i></button>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btDetails_anne"><i class="material-icons">open_in_full</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'ANNEE_SIZE');
		?>
	  </tbody>
	</table>
	<div class="ANNEE_FOOT col s12"></div>
</div>

<div class="col s12 add_area_anne" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_anne"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier annee</font></div>
    </div>
   <form class="row" id="formAdd_anne">
	 <input type="hidden" name="id" id="Id_anne" value="">
	 <input type="hidden" name="action" id="Action_anne" value="create">
	 <div class="col m6 s12 input-field">
     <label for="Name">Name</label>
       <input type="text" name="name" id="Name" required>
    </div>


	 <div class="col m12 s12 input-field">
	   <button type="submit" class="btn white black-text waves-effect" name="button"><i class="material-icons left">done</i>Enregistrer</button>
	 </div>
   </form>
</div>

<div class="col s12 show_area_anne" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_anne').hide(); $('.data_area_anne').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
    </div>
	<div class="col s12">
		<table class="col s12">
			<tr><td>name</td><td class="name_dt label_detail"></td></tr>
      
			<tr>
				<td colspan="2"><button class="btn-small grey darken-2 waves-effect waves-light bt_delete_anne" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button></td>
			</tr>
		</table>
	</div>
</div>
<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_anne(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/annee/annee_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.loadpane').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();
        
		$('.ANNEE_FOOT').pagination({
			items: parseInt($('#ANNEE_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_anne').val()),
			onPageClick: function(pageNumber, event){
				init_anne(pageNumber-1);
			}
		});
          //$('.ANNEE_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.bt_delete_anne').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/annee/annee_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
				  $('.LOADER').hide();
				  init_anne();
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
            }
          });
        });

        $('.btMod_anne').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/annee/annee_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_anne').val('update');
				               $('#Id_anne').val(data.id);
               $('#Name').val(data.name);


				$('.data_area_anne').hide();
				$('.add_area_anne').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_anne').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
		  $.post(
			'../php/modules/annee/annee_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('.bt_delete_anne').attr('data', tid);
				               $('.name_dt').html(data.name);


				$('.data_area_anne').hide();
				$('.show_area_anne').toggle('drop');
				$('.LOADER').hide();
			}
		  );
        });

        $('#formAdd_anne').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		  var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/annee/annee_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_anne')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_anne').val('create');
                  $('.LOADER').hide();
                  init_anne();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });
		
      });
	  
	  $('.bt_back_anne').click(function(){
		  $('.show_area_anne').hide();
          $('.add_area_anne').hide(); $('.data_area_anne').toggle('drop');
          init_anne();
        });
	  
	  $('.search_form_anne').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/annee/annee_util.php',
		$(this).serialize(),
		function(data){
			$('.loadpane').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
