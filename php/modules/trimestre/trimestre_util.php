<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_trim" />'; 
	$query  = 'SELECT * FROM trimestre WHERE classe='.$_SESSION['classe']->id.(isset($_POST['search'])? ' AND '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
?>
  
<div class="data_area_trim col s12 l8 offset-l2">
	<div class="col s12">
		<div class="col m6 l6"><font class="flow-text">Trimestre</font></div>
		<div class="col m6 l6"><button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_trim').hide(); $('.add_area_trim').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button></div>
	</div>
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="3">
				<form class="col s12 search_form_trim">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
						            <select name="option" required>
                 <option value="lib">lib</option>
            	</select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_trim">Search . . .</label>
						<input type="search" id="search_text_trim" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
					</div>
					<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
				</form>
			</td>
		</tr>
		<tr>
			<th class="table_counter">N°</th>
			<th>Libellé</th>
			<th>Action</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		  foreach ($entity->rawPage($query, 'id', $page) as $key => $value) {
			echo '<tr>
			  <td>'.($page*$entity->pageSize+$key+1).'</td>
              <td>'.$value['lib'].'</td>              
			  <td>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod_trim"><i class="material-icons">edit</i></button>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect bt_delete_trim"><i class="material-icons red-text">delete</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'TRIMESTRE_SIZE');
		?>
	  </tbody>
	</table>
	<div class="TRIMESTRE_FOOT col s12"></div>
</div>

<div class="col s12 l6 offset-l3 add_area_trim" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_trim"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier trimestre</font></div>
    </div>
   <form class="row" id="formAdd_trim">
	 <input type="hidden" name="id" id="Id_trim" value="">
	 <input type="hidden" name="action" id="Action_trim" value="create">
<div class="col m6 s12 input-field">
     <label for="Lib">Libellé</label>
       <input type="text" name="lib" id="Lib" required>
    </div>


	 <div class="col m12 s12 input-field">
	   <button type="submit" class="btn white black-text waves-effect" name="button"><i class="material-icons left">done</i>Enregistrer</button>
	 </div>
   </form>
</div>

<div class="col s12 show_area_trim" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_trim').hide(); $('.data_area_trim').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
    </div>
	<div class="col s12">
		<table class="col s12">
      <tr><td>libellé</td><td class="lib_dt label_detail"></td></tr>
      
			<tr>
				<td colspan="2"><button class="btn-small grey darken-2 waves-effect waves-light bt_delete_trim" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button></td>
			</tr>
		</table>
	</div>
</div>
<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_trim(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/trimestre/trimestre_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.loadpane').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();
        
		$('.TRIMESTRE_FOOT').pagination({
			items: parseInt($('#TRIMESTRE_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_trim').val()),
			onPageClick: function(pageNumber, event){
				init_trim(pageNumber-1);
			}
		});
          //$('.TRIMESTRE_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.bt_delete_trim').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/trimestre/trimestre_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
				  $('.LOADER').hide();
				  init_trim();
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
            }
          });
        });

        $('.btMod_trim').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/trimestre/trimestre_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_trim').val('update');
				$('#Id_trim').val(data.id);
               $('#Lib').val(data.lib);


				$('.data_area_trim').hide();
				$('.add_area_trim').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_trim').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
		  $.post(
			'../php/modules/trimestre/trimestre_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('.bt_delete_trim').attr('data', tid);
               $('.lib_dt').html(data.lib);
				$('.data_area_trim').hide();
				$('.show_area_trim').toggle('drop');
				$('.LOADER').hide();
			}
		  );
        });

        $('#formAdd_trim').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		  var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/trimestre/trimestre_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_trim')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_trim').val('create');
                  $('.LOADER').hide();
                  init_trim();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });
		
      });
	  
	  $('.bt_back_trim').click(function(){
		  $('.show_area_trim').hide();
          $('.add_area_trim').hide(); $('.data_area_trim').toggle('drop');
          init_trim();
        });
	  
	  $('.search_form_trim').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/trimestre/trimestre_util.php',
		$(this).serialize(),
		function(data){
			$('.loadpane').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
