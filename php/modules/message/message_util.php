<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_mess" />'; 
  
	$query  = 'SELECT * FROM message'.(isset($_POST['search'])? ' WHERE '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  
?>
  
<div class="data_area_mess col s12">
	<div class="col s12">
		<div class="col m6 l6"><font class="flow-text">message</font></div>
		<div class="col m6 l6"><button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_mess').hide(); $('.add_area_mess').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button></div>
	</div>
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="4">
				<form class="col s12 search_form_mess">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
						            <select name="option" required>
                               <option value="classe">classe</option>
                 <option value="message">message</option>
                 <option value="surveillant">surveillant</option>
            </select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_mess">Search . . .</label>
						<input type="search" id="search_text_mess" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
					</div>
					<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
				</form>
			</td>
		</tr>
		<tr>
		        <th class="table_counter">N°</th>
      <th>Classe</th>
      <th>Message</th>
      <th>Surveillant</th>
         <th>Action</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		  foreach ($entity->rawPage($query, 'date_create', $page) as $key => $value) {
			echo '<tr>
			  <td>'.($page*$entity->pageSize+$key+1).'</td>
			  <td>'.$value['classe'].'</td>
              <td>'.$value['message'].'</td>
              <td>'.$value['surveillant'].'</td>
              
			  <td>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod_mess"><i class="material-icons">edit</i></button>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btDetails_mess"><i class="material-icons">open_in_full</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'MESSAGE_SIZE');
		?>
	  </tbody>
	</table>
	<div class="MESSAGE_FOOT col s12"></div>
</div>

<div class="col s12 add_area_mess" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_mess"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier message</font></div>
    </div>
   <form class="row" id="formAdd_mess">
	 <input type="hidden" name="id" id="Id_mess" value="">
	 <input type="hidden" name="action" id="Action_mess" value="create">
	 <div class="col m6 s12 input-field">
     <label for="Classe">Classe</label>
       <input type="text" name="classe" id="Classe" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Message">Message</label>
       <input type="text" name="message" id="Message" required>
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

<div class="col s12 show_area_mess" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_mess').hide(); $('.data_area_mess').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
    </div>
	<div class="col s12">
		<table class="col s12">
			<tr><td>classe</td><td class="classe_dt label_detail"></td></tr>
      <tr><td>message</td><td class="message_dt label_detail"></td></tr>
      <tr><td>surveillant</td><td class="surveillant_dt label_detail"></td></tr>
      
			<tr>
				<td colspan="2"><button class="btn-small grey darken-2 waves-effect waves-light bt_delete_mess" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button></td>
			</tr>
		</table>
	</div>
</div>
<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_mess(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/message/message_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.loadpane').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();
        
		$('.MESSAGE_FOOT').pagination({
			items: parseInt($('#MESSAGE_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_mess').val()),
			onPageClick: function(pageNumber, event){
				init_mess(pageNumber-1);
			}
		});
          //$('.MESSAGE_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.bt_delete_mess').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/message/message_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
				  $('.LOADER').hide();
				  init_mess();
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
            }
          });
        });

        $('.btMod_mess').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/message/message_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_mess').val('update');
				               $('#Id_mess').val(data.id);
               $('#Classe').val(data.classe);
               $('#Message').val(data.message);
               $('#Surveillant').val(data.surveillant);


				$('.data_area_mess').hide();
				$('.add_area_mess').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_mess').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
		  $.post(
			'../php/modules/message/message_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('.bt_delete_mess').attr('data', tid);
				               $('.classe_dt').html(data.classe);
               $('.message_dt').html(data.message);
               $('.surveillant_dt').html(data.surveillant);


				$('.data_area_mess').hide();
				$('.show_area_mess').toggle('drop');
				$('.LOADER').hide();
			}
		  );
        });

        $('#formAdd_mess').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		  var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/message/message_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_mess')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_mess').val('create');
                  $('.LOADER').hide();
                  init_mess();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });
		
      });
	  
	  $('.bt_back_mess').click(function(){
		  $('.show_area_mess').hide();
          $('.add_area_mess').hide(); $('.data_area_mess').toggle('drop');
          init_mess();
        });
	  
	  $('.search_form_mess').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/message/message_util.php',
		$(this).serialize(),
		function(data){
			$('.loadpane').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
