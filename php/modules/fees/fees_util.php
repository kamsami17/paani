<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_fees" />'; 
  
	$query  = 'SELECT * FROM fees'.(isset($_POST['search'])? ' WHERE '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  
?>
<fieldset>
	<legend>
		<button class="btn" onclick="home();"><i class="material-icons">arrow_back</i></button>
		Gestion de la scolarité
	</legend>
  
<div class="data_area_fees col s12">
	<div class="col s12">
		<div class="col m6 l4"><font class="flow-text">Gestion de la scolarité</font></div>
		<div class="col m6 l8">			
			<button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_fees').hide(); $('.add_area_fees').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button>
		</div>
	</div>
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="3">
				<form class="col s12 search_form_fees">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
						            <select name="option" required>
                               <option value="classe">classe</option>
                 <option value="montant">montant</option>
            </select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_fees">Search . . .</label>
						<input type="search" id="search_text_fees" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
					</div>
					<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
				</form>
			</td>
		</tr>
		<tr>
		<th class="table_counter">N°</th>
    	  <th>Classe</th>
	      <th>Montant</th>
         <th>Action</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		  foreach ($entity->rawPage($query, 'id', $page) as $key => $value) {
			echo '<tr>
			  <td>'.($page*$entity->pageSize+$key+1).'</td>
			  <td>'.Classe::getname($bdd, $value['classe']).'</td>
              <td>'.$value['montant'].'</td>
			  <td>
			  	<button data="'.$value['classe'].'" type="button" class="btn-flat waves-effect bt_open_fees blue-text"><i class="material-icons">open_in_full</i></button>
				  <button data="'.$value['classe'].'" type="button" class="btn-flat waves-effect bt_export_fee blue-text"><i class="material-icons">assignment</i></button>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod_fees"><i class="material-icons">edit</i></button>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect bt_delete_fees"><i class="material-icons red-text">delete</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'FEES_SIZE');
		?>
	  </tbody>
	</table>
	<div class="FEES_FOOT col s12"></div>
</div>

<div class="col s12 add_area_fees" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_fees"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier scolarité</font></div>
    </div>
   <form class="row" id="formAdd_fees">
	 <input type="hidden" name="id" id="Id_fees" value="">
	 <input type="hidden" name="action" id="Action_fees" value="create">
	 <div class="col m6 s12 input-field">
     	<!-- <label for="Classe">Classe</label>
       <input type="text" name="classe" id="Classe" required> -->
	   <select name="classe" id="Classe" class="browser-default" required>
		   <option value="" disabled selected>Choisir la classe</option>
		   <?php 
		 		foreach(Classe::get_all($bdd, $_SESSION['user']->ecole) as $classe){
					echo '<option value="'.$classe->id.'">'.$classe->name.'</option>';
				}  
		   ?>
	   </select>
    </div>
	<div class="col m6 s12 input-field">
     <label for="Montant">Montant</label>
       <input type="text" name="montant" id="Montant" required>
    </div>

	 <div class="col m12 s12 input-field">
	   <button type="submit" class="btn white black-text waves-effect" name="button"><i class="material-icons left">done</i>Enregistrer</button>
	 </div>
   </form>
</div>

<div class="col s12 show_area_fees" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_fees').hide(); $('.data_area_fees').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
    </div>
	<div class="col s12">
		<table class="col s12">
			<tr><td>classe</td><td class="classe_dt label_detail"></td></tr>
      		<tr><td>montant</td><td class="montant_dt label_detail"></td></tr>
      
			<tr>
				<td colspan="2"><button class="btn-small grey darken-2 waves-effect waves-light bt_delete_fees" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button></td>
			</tr>
		</table>
	</div>
</div>
</fieldset>

<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_fees(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/fees/fees_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.load_area').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();
        $('.bt_export_fee').click(function(){
			$('.LOADER').show();
			$.post('../php/modules/fees/fees_data.php',
			{ action: 'gen_fich', classe: $(this).attr('data') },
			function(data){
				$('.LOADER').hide();
				document.location.href = data;
			});
		});
		$('.FEES_FOOT').pagination({
			items: parseInt($('#FEES_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_fees').val()),
			onPageClick: function(pageNumber, event){
				init_fees(pageNumber-1);
			}
		});
          //$('.FEES_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

		$('.bt_open_fees').click(function(){
			$('.LOADER').show();
			$.post(
				'../php/modules/school_fee/school_fee_util.php',
				{ sel_classe: $(this).attr('data') },
				function(data){
					$('.load_area').html(data);
					$('.LOADER').hide();
				}
			);
		});

        $('.bt_delete_fees').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/fees/fees_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
				  $('.LOADER').hide();
				  init_fees();
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
            }
          });
        });

        $('.btMod_fees').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/fees/fees_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_fees').val('update');
				               $('#Id_fees').val(data.id);
               $('#Classe').val(data.classe);
               $('#Montant').val(data.montant);


				$('.data_area_fees').hide();
				$('.add_area_fees').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_fees').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
		  $.post(
			'../php/modules/fees/fees_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('.bt_delete_fees').attr('data', tid);
				$('.classe_dt').html(data.classe);
               $('.montant_dt').html(data.montant);
				$('.data_area_fees').hide();
				$('.show_area_fees').toggle('drop');
				$('.LOADER').hide();
			}
		  );
        });

        $('#formAdd_fees').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		  var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/fees/fees_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_fees')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_fees').val('create');
                  $('.LOADER').hide();
                  init_fees();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });
		
      });
	  
	  $('.bt_back_fees').click(function(){
		  $('.show_area_fees').hide();
          $('.add_area_fees').hide(); $('.data_area_fees').toggle('drop');
          init_fees();
        });
	  
	  $('.search_form_fees').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/fees/fees_util.php',
		$(this).serialize(),
		function(data){
			$('.load_area').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
