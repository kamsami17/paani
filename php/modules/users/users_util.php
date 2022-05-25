<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_user" />'; 
  
	$query  = 'SELECT * FROM users WHERE ecole='.$_SESSION['user']->ecole.(isset($_POST['search'])? ' AND '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  
?>
<fieldset>
    <legend>
      <button class="btn" onclick="home();"><i class="material-icons">arrow_back</i></button>
      Gestion des utilisateurs
    </legend>

<div class="data_area_user col s12">
	<div class="col s12">
		<div class="col m6 l6"><font class="flow-text">Personnel</font></div>
		<div class="col m6 l6"><button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_user').hide(); $('.add_area_user').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button></div>
	</div>
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="8">
				<form class="col s12 search_form_user">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
						            <select name="option" required>
                               <option value="nom">nom</option>
                 <option value="prenom">prenom</option>
                 <option value="contact">contact</option>
                 <option value="email">email</option>
                 <option value="passwd">passwd</option>
                 <option value="role">role</option>
                 <option value="ecole">ecole</option>
            </select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_user">Search . . .</label>
						<input type="search" id="search_text_user" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
					</div>
					<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
				</form>
			</td>
		</tr>
		<tr>
        <th class="table_counter">N°</th>
        <th>Nom</th>
        <th>Prenom</th>
        <th>Contact</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		  foreach ($entity->rawPage($query, 'date_create', $page) as $key => $value) {
			echo '<tr>
			  <td>'.($page*$entity->pageSize+$key+1).'</td>
			  <td>'.$value['nom'].'</td>
              <td>'.$value['prenom'].'</td>
              <td>'.$value['contact'].'</td>
              <td>'.$value['email'].'</td>
              <td>'.$value['role'].'</td>
              
			  <td>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod_user"><i class="material-icons">edit</i></button>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btDetails_user"><i class="material-icons">open_in_full</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'USERS_SIZE');
		?>
	  </tbody>
	</table>
	<div class="USERS_FOOT col s12"></div>
</div>

<div class="col s12 add_area_user" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_user"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier personnel</font></div>
    </div>
   <form class="row" id="formAdd_user">
	 <input type="hidden" name="id" id="Id_user" value="">
	 <input type="hidden" name="action" id="Action_user" value="create">
	 <div class="col m6 s12 input-field">
     <label for="Nom">Nom</label>
       <input type="text" name="nom" id="Nom" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Prenom">Prenom</label>
       <input type="text" name="prenom" id="Prenom" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Contact">Contact</label>
       <input type="text" name="contact" id="Contact" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Email">Email</label>
       <input type="text" name="email" id="Email" required>
    </div>
<div class="col m6 s12 input-field">
     <label for="Passwd">Mot de passe</label>
       <input type="password" name="passwd" id="Passwd" required>
    </div>
    <div class="col m6 s12 input-field">
     <label for="Confpass">Confirmer</label>
       <input type="password" name="confpass" id="Confpass" required>
    </div>
<div class="col m6 s12 input-field">
       <select name="role" id="Role" class="browser-default">
          <option value="" disabled selected>Choix du rôle</option>
          <option value="SURVEILLANT">Surveillant</option>
          <option value="ADMIN">Administrateur</option>
       </select>
    </div>

	 <div class="col m12 s12 input-field">
	   <button type="submit" class="btn white black-text waves-effect" name="button"><i class="material-icons left">done</i>Enregistrer</button>
	 </div>
   </form>
</div>

<div class="col s12 show_area_user" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_user').hide(); $('.data_area_user').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
    </div>
	<div class="col s12">
		<table class="col s12">
			<tr><td>nom</td><td class="nom_dt label_detail"></td></tr>
      <tr><td>prenom</td><td class="prenom_dt label_detail"></td></tr>
      <tr><td>contact</td><td class="contact_dt label_detail"></td></tr>
      <tr><td>email</td><td class="email_dt label_detail"></td></tr>
      <tr><td>role</td><td class="role_dt label_detail"></td></tr>
			<tr>
				<td colspan="2"><button class="btn-small grey darken-2 waves-effect waves-light bt_delete_user" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button></td>
			</tr>
		</table>
	</div>
</div>

</fieldset>
<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_user(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/users/users_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.load_area').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();

        $('#Passwd').keyup(function(){
          $(this).css('color', $(this).val().length>=8? 'black': 'red');  
        });
        $('#Confpass').keyup(function(){
          $(this).css('color', $(this).val()==$('#Passwd').val() ? 'black' : 'red');
        });
        
		$('.USERS_FOOT').pagination({
			items: parseInt($('#USERS_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_user').val()),
			onPageClick: function(pageNumber, event){
				init_user(pageNumber-1);
			}
		});
          //$('.USERS_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.bt_delete_user').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/users/users_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
				  $('.LOADER').hide();
				  init_user();
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
            }
          });
        });

        $('.btMod_user').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/users/users_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_user').val('update');
              $('#Id_user').val(data.id);
               $('#Nom').val(data.nom);
               $('#Prenom').val(data.prenom);
               $('#Contact').val(data.contact);
               $('#Email').val(data.email);
               $('#Role').val(data.role);
               $('#Ecole').val(data.ecole);


				$('.data_area_user').hide();
				$('.add_area_user').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_user').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
		  $.post(
			'../php/modules/users/users_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('.bt_delete_user').attr('data', tid);
              $('.nom_dt').html(data.nom);
               $('.prenom_dt').html(data.prenom);
               $('.contact_dt').html(data.contact);
               $('.email_dt').html(data.email);
              //  $('.passwd_dt').html(data.passwd);
               $('.role_dt').html(data.role);
               $('.ecole_dt').html(data.ecole);


				$('.data_area_user').hide();
				$('.show_area_user').toggle('drop');
				$('.LOADER').hide();
			}
		  );
        });

        $('#formAdd_user').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		  var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/users/users_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_user')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_user').val('create');
                  $('.LOADER').hide();
                  init_user();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });
		
      });
	  
	  $('.bt_back_user').click(function(){
		  $('.show_area_user').hide();
          $('.add_area_user').hide(); $('.data_area_user').toggle('drop');
          init_user();
        });
	  
	  $('.search_form_user').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/users/users_util.php',
		$(this).serialize(),
		function(data){
			$('.load_area').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
