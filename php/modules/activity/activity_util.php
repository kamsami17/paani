<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_acti" />'; 
  
	$query  = 'SELECT * FROM activity'.(isset($_POST['search'])? ' WHERE '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  
?>
  
<div class="data_area_acti col s12">
	<div class="col s12">
		<div class="col m6 l6"><font class="flow-text">activity</font></div>
		<div class="col m6 l6"><button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_acti').hide(); $('.add_area_acti').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button></div>
	</div>
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="9">
				<form class="col s12 search_form_acti">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
						            <select name="option" required>
                               <option value="title">title</option>
                 <option value="ecole">ecole</option>
                 <option value="description">description</option>
                 <option value="img">img</option>
                 <option value="date_act">date_act</option>
                 <option value="type_act">type_act</option>
                 <option value="color">color</option>
                 <option value="annee">annee</option>
            </select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_acti">Search . . .</label>
						<input type="search" id="search_text_acti" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
					</div>
					<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
				</form>
			</td>
		</tr>
		<tr>
		        <th class="table_counter">N°</th>
      <th>Title</th>
      <th>Ecole</th>
      <th>Description</th>
      <th>Img</th>
      <th>Date_act</th>
      <th>Type_act</th>
      <th>Color</th>
      <th>Annee</th>
         <th>Action</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		  foreach ($entity->rawPage($query, 'date_create', $page) as $key => $value) {
			echo '<tr>
			  <td>'.($page*$entity->pageSize+$key+1).'</td>
			  <td>'.$value['title'].'</td>
              <td>'.$value['ecole'].'</td>
              <td>'.$value['description'].'</td>
              <td>'.$value['img'].'</td>
              <td>'.$value['date_act'].'</td>
              <td>'.$value['type_act'].'</td>
              <td>'.$value['color'].'</td>
              <td>'.$value['annee'].'</td>
              
			  <td>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod_acti"><i class="material-icons">edit</i></button>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btDetails_acti"><i class="material-icons">open_in_full</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'ACTIVITY_SIZE');
		?>
	  </tbody>
	</table>
	<div class="ACTIVITY_FOOT col s12"></div>
</div>

<div class="col s12 add_area_acti" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_acti"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier activity</font></div>
    </div>
   <form class="row" id="formAdd_acti">
	 <input type="hidden" name="id" id="Id_acti" value="">
	 <input type="hidden" name="action" id="Action_acti" value="create">
	 <div class="col m6 s12 input-field">
     <label for="Title">Title</label>
       <input type="text" name="title" id="Title" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Ecole">Ecole</label>
       <input type="text" name="ecole" id="Ecole" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Description">Description</label>
       <input type="text" name="description" id="Description" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Img">Img</label>
       <input type="text" name="img" id="Img" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Date_act">Date_act</label>
       <input type="text" name="date_act" id="Date_act" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Type_act">Type_act</label>
       <input type="text" name="type_act" id="Type_act" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Color">Color</label>
       <input type="text" name="color" id="Color" required>
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

<div class="col s12 show_area_acti" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_acti').hide(); $('.data_area_acti').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
    </div>
	<div class="col s12">
		<table class="col s12">
			<tr><td>title</td><td class="title_dt label_detail"></td></tr>
      <tr><td>ecole</td><td class="ecole_dt label_detail"></td></tr>
      <tr><td>description</td><td class="description_dt label_detail"></td></tr>
      <tr><td>img</td><td class="img_dt label_detail"></td></tr>
      <tr><td>date_act</td><td class="date_act_dt label_detail"></td></tr>
      <tr><td>type_act</td><td class="type_act_dt label_detail"></td></tr>
      <tr><td>color</td><td class="color_dt label_detail"></td></tr>
      <tr><td>annee</td><td class="annee_dt label_detail"></td></tr>
      
			<tr>
				<td colspan="2"><button class="btn-small grey darken-2 waves-effect waves-light bt_delete_acti" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button></td>
			</tr>
		</table>
	</div>
</div>
<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_acti(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/activity/activity_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.load_area').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();
        
		$('.ACTIVITY_FOOT').pagination({
			items: parseInt($('#ACTIVITY_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_acti').val()),
			onPageClick: function(pageNumber, event){
				init_acti(pageNumber-1);
			}
		});
          //$('.ACTIVITY_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.bt_delete_acti').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/activity/activity_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
				  $('.LOADER').hide();
				  init_acti();
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
            }
          });
        });

        $('.btMod_acti').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/activity/activity_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_acti').val('update');
				               $('#Id_acti').val(data.id);
               $('#Title').val(data.title);
               $('#Ecole').val(data.ecole);
               $('#Description').val(data.description);
               $('#Img').val(data.img);
               $('#Date_act').val(data.date_act);
               $('#Type_act').val(data.type_act);
               $('#Color').val(data.color);
               $('#Annee').val(data.annee);


				$('.data_area_acti').hide();
				$('.add_area_acti').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_acti').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
		  $.post(
			'../php/modules/activity/activity_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('.bt_delete_acti').attr('data', tid);
				               $('.title_dt').html(data.title);
               $('.ecole_dt').html(data.ecole);
               $('.description_dt').html(data.description);
               $('.img_dt').html(data.img);
               $('.date_act_dt').html(data.date_act);
               $('.type_act_dt').html(data.type_act);
               $('.color_dt').html(data.color);
               $('.annee_dt').html(data.annee);


				$('.data_area_acti').hide();
				$('.show_area_acti').toggle('drop');
				$('.LOADER').hide();
			}
		  );
        });

        $('#formAdd_acti').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		  var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/activity/activity_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_acti')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_acti').val('create');
                  $('.LOADER').hide();
                  init_acti();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });
		
      });
	  
	  $('.bt_back_acti').click(function(){
		  $('.show_area_acti').hide();
          $('.add_area_acti').hide(); $('.data_area_acti').toggle('drop');
          init_acti();
        });
	  
	  $('.search_form_acti').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/activity/activity_util.php',
		$(this).serialize(),
		function(data){
			$('.load_area').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
