<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_subs" />'; 
  
	$query  = 'SELECT * FROM subs'.(isset($_POST['search'])? ' WHERE '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  
?>
  
<div class="data_area_subs col s12">
	<div class="col s12">
		<div class="col m6 l6"><font class="flow-text">subs</font></div>
		<div class="col m6 l6"><button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_subs').hide(); $('.add_area_subs').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button></div>
	</div>
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="4">
				<form class="col s12 search_form_subs">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
						            <select name="option" required>
                               <option value="parent">parent</option>
                 <option value="state">state</option>
                 <option value="validated_by">validated_by</option>
            </select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_subs">Search . . .</label>
						<input type="search" id="search_text_subs" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
					</div>
					<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
				</form>
			</td>
		</tr>
		<tr>
		        <th class="table_counter">N°</th>
      <th>Parent</th>
      <th>State</th>
      <th>Validated_by</th>
         <th>Action</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		  foreach ($entity->rawPage($query, 'date_create', $page) as $key => $value) {
			echo '<tr>
			  <td>'.($page*$entity->pageSize+$key+1).'</td>
			  <td>'.$value['parent'].'</td>
              <td>'.$value['state'].'</td>
              <td>'.$value['validated_by'].'</td>
              
			  <td>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod_subs"><i class="material-icons">edit</i></button>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btDetails_subs"><i class="material-icons">open_in_full</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'SUBS_SIZE');
		?>
	  </tbody>
	</table>
	<div class="SUBS_FOOT col s12"></div>
</div>

<div class="col s12 add_area_subs" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_subs"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier subs</font></div>
    </div>
   <form class="row" id="formAdd_subs">
	 <input type="hidden" name="id" id="Id_subs" value="">
	 <input type="hidden" name="action" id="Action_subs" value="create">
	 <div class="col m6 s12 input-field">
     <label for="Parent">Parent</label>
       <input type="text" name="parent" id="Parent" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="State">State</label>
       <input type="text" name="state" id="State" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Validated_by">Validated_by</label>
       <input type="text" name="validated_by" id="Validated_by" required>
    </div>


	 <div class="col m12 s12 input-field">
	   <button type="submit" class="btn white black-text waves-effect" name="button"><i class="material-icons left">done</i>Enregistrer</button>
	 </div>
   </form>
</div>

<div class="col s12 show_area_subs" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_subs').hide(); $('.data_area_subs').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
    </div>
	<div class="col s12">
		<table class="col s12">
			<tr><td>parent</td><td class="parent_dt label_detail"></td></tr>
      <tr><td>state</td><td class="state_dt label_detail"></td></tr>
      <tr><td>validated_by</td><td class="validated_by_dt label_detail"></td></tr>
      
			<tr>
				<td colspan="2"><button class="btn-small grey darken-2 waves-effect waves-light bt_delete_subs" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button></td>
			</tr>
		</table>
	</div>
</div>
<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_subs(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/subs/subs_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.loadpane').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();
        
		$('.SUBS_FOOT').pagination({
			items: parseInt($('#SUBS_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_subs').val()),
			onPageClick: function(pageNumber, event){
				init_subs(pageNumber-1);
			}
		});
          //$('.SUBS_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.bt_delete_subs').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/subs/subs_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
				  $('.LOADER').hide();
				  init_subs();
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
            }
          });
        });

        $('.btMod_subs').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/subs/subs_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_subs').val('update');
				               $('#Id_subs').val(data.id);
               $('#Parent').val(data.parent);
               $('#State').val(data.state);
               $('#Validated_by').val(data.validated_by);


				$('.data_area_subs').hide();
				$('.add_area_subs').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_subs').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
		  $.post(
			'../php/modules/subs/subs_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('.bt_delete_subs').attr('data', tid);
				               $('.parent_dt').html(data.parent);
               $('.state_dt').html(data.state);
               $('.validated_by_dt').html(data.validated_by);


				$('.data_area_subs').hide();
				$('.show_area_subs').toggle('drop');
				$('.LOADER').hide();
			}
		  );
        });

        $('#formAdd_subs').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		  var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/subs/subs_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_subs')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_subs').val('create');
                  $('.LOADER').hide();
                  init_subs();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });
		
      });
	  
	  $('.bt_back_subs').click(function(){
		  $('.show_area_subs').hide();
          $('.add_area_subs').hide(); $('.data_area_subs').toggle('drop');
          init_subs();
        });
	  
	  $('.search_form_subs').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/subs/subs_util.php',
		$(this).serialize(),
		function(data){
			$('.loadpane').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
