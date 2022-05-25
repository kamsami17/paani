<?php
  include_once('../connectdb.php');
  include_once('../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE" />';
  
  switch (htmlspecialchars($_POST['action'])) {
	case 'read':
		echo json_encode(Cact::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		    $cact = new Cact();
    $cact->classe = htmlspecialchars($_POST['classe']);
    $cact->activity = htmlspecialchars($_POST['activity']);
    $cact->create($bdd);
		echo 'create';
      break;
    case 'update':
		    $cact = new Cact();
    $cact->id = htmlspecialchars($_POST['id']);
    $cact->classe = htmlspecialchars($_POST['classe']);
    $cact->activity = htmlspecialchars($_POST['activity']);
    $cact->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Cact::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

    default: ?>
	<div class="data_area col s12">
		<button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area').hide(); $('.add_area').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button>
		<table class="col s12 striped">
		  <thead>
			<tr>
				<td colspan="3">
					<form class="col s12 search_form">
						<input type="hidden" name="action" value="" />
						<div class="col s4 input-field">
							            <select name="option" required>
                               <option value="classe">classe</option>
                 <option value="activity">activity</option>
            </select>

						</div>
						<div class="col s6 input-field">
							<label for="search_text">Search . . .</label>
							<input type="search" id="search_text" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
						</div>
						<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
					</form>
				</td>
			</tr>
			<tr>
			        <th class="table_counter">N°</th>
      <th>Classe</th>
      <th>Activity</th>
         <th>Action</th>
			</tr>
		  </thead>
		  <tbody>
			<?php
				$query  ='SELECT * FROM cact'.(isset($_POST['search'])? ' WHERE '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
			  foreach ($entity->rawPage($query, 'date_create', $page) as $key => $value) {
				echo '<tr>
				  <td>'.($page*$entity->pageSize+$key+1).'</td>
				  <td>'.$value['classe'].'</td>
              <td>'.$value['activity'].'</td>
              
				  <td>
					<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod"><i class="material-icons">edit</i></button>
					<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btDel"><i class="material-icons red-text">delete</i></button>
				  </td>
				</tr>';
			  }
			  $entity->psize($query, 'CACT_SIZE');
			?>
		  </tbody>
		</table>
		<div class="CACT_FOOT col s12"></div>
	</div>
    <div class="col s12 add_area" style="display: none;">
		<button class="waves-effect btn white black-text bt_back"><i class="material-icons black-text left">arrow_back</i>Données</button>

	   <form class="row" id="formAdd">
		 <input type="hidden" name="id" id="id" value="">
		 <input type="hidden" name="action" id="action" value="create">
		 <center><font class="flow-text">Ajouter/Modifier cact</font></center>
		 <div class="col m6 s12 input-field">
   <label for="Classe">Classe</label>
    <input type="text" name="classe" id="Classe" required>
</div>
<div class="col m6 s12 input-field">
   <label for="Activity">Activity</label>
    <input type="text" name="activity" id="Activity" required>
</div>


		 <div class="col m12 s12 input-field">
		   <button type="submit" class="btn white black-text waves-effect" name="button"><i class="material-icons left">done</i>Enregistrer</button>
		 </div>
	   </form>
        
    </div>
    <script type="text/javascript">
		function init(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/utils/cact_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.loadpane').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();
        

					$('.CACT_FOOT').pagination({
							items: parseInt($('#CACT_SIZE').val()),
							itemsOnPage: PAGE_SIZE,
							cssStyle: 'light-theme',
              currentPage: parseInt($('#CURR_PAGE').val()),
							onPageClick: function(pageNumber, event){
									init(pageNumber-1);
							}
					});
          //$('.CACT_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.btDel').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/utils/cact_util.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
              $(this).parent().parent().hide();
			  $('.LOADER').hide();
            }
          });
        });

        $('.btMod').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/utils/cact_util.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp.split('/>')[1]);
				$('#action').val('update');
				$('#action').val('update');
				               $('#Id').val(data.id);
               $('#Classe').val(data.classe);
               $('#Activity').val(data.activity);


				$('.data_area').hide();
				$('.add_area').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });

        $('#formAdd').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		  var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/utils/cact_util.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action').val('create');
                  $('.LOADER').hide();
                  init();
                }
              },
              error: function(resp){
                $('.LOADER0').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });

      });
	  
	  $('.bt_back').click(function(){
          $('.add_area').hide(); $('.data_area').toggle('drop');
          init();
        });
	  
	  $('.search_form').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/utils/cact_util.php',
		$(this).serialize(),
		function(data){
			$('.loadpane').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>

<?php
    break;
  }
 ?>
