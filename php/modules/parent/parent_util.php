<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_pare" />'; 
  
	$query  = 'SELECT * FROM parent WHERE ecole='.$_SESSION['user']->ecole.(isset($_POST['search'])? ' AND '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  
?>

<fieldset>
	<legend>
		<button class="btn grey darken-3" onclick="home();"><i class="material-icons">arrow_back</i></button>
		 Gestion des parents d'élèves
	</legend>
  
<div class="data_area_pare col s12">
	<div class="col s12">
		<div class="col m6 l6"><font class="flow-text">Parents</font></div>
		<div class="col m6 l6"><button class="waves-effect waves-light btn btn-small red" onclick="$('.data_area_pare').hide(); $('.add_area_pare').toggle('drop');"><i class="material-icons left">add</i>Ajouter</button></div>
	</div>
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="6">
				<form class="col s12 search_form_pare">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
						            <select name="option" required>
                               <option value="nom">nom</option>
                 <option value="prenom">prenom</option>
                 <option value="contact">contact</option>
                 <option value="email">email</option>
                 <option value="passwd">passwd</option>
            </select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_pare">Search . . .</label>
						<input type="search" id="search_text_pare" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
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
        <th>Action</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		  foreach ($entity->rawPage($query, 'concat(nom,prenom)', $page, 'ASC') as $key => $value) {
			echo '<tr>
			  <td>'.($page*$entity->pageSize+$key+1).'</td>
			  <td>'.$value['nom'].'</td>
        <td>'.$value['prenom'].'</td>
        <td>'.$value['contact'].'</td>
        <td>'.$value['email'].'</td>
			  <td>
          <button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod_pare"><i class="material-icons">edit</i></button>
          <button data-contact="'.$value['contact'].'" data="'.$value['id'].'" type="button" class="btn-flat waves-effect bt_pass_mod"><i class="material-icons blue-text">lock</i></button>
          <button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btDetails_pare"><i class="material-icons">open_in_full</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'PARENT_SIZE');
		?>
	  </tbody>
	</table>
	<div class="PARENT_FOOT col s12"></div>
</div>

<!-- <div id="modal1" class="modal">
  <div class="modal-content col s12">
    <center><font class="flow-text">Modifier le mot de passe</font></center>
    <form class="pass_change col s12">
      <input type="hidden" name="action" value="pass_change">
      <input type="hidden" name="parent_id" value="">
      <div class="col s12 input-field">
        <input type="password">
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a>
  </div>
</div> -->

<div class="col s12 add_area_pare" style="display: none;">
	<div class="col s12">
      <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text bt_back_pare"><i class="material-icons black-text left">arrow_back</i>Données</button></div>
	  <div class="col l10 m8 s6"><font class="flow-text">Ajouter/Modifier parent</font></div>
    </div>
   <form class="row" id="formAdd_pare">
	 <input type="hidden" name="id" id="Id_pare" value="">
	 <input type="hidden" name="action" id="Action_pare" value="create">
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
       <input type="email" name="email" id="Email" required>
    </div>
  <!-- <div class="col m6 s12 input-field">
     <label for="Passwd">Mot de passe</label>
       <input type="password" name="passwd" id="Passwd" required>
    </div> -->


	 <div class="col m12 s12 input-field">
	   <button type="submit" class="btn white black-text waves-effect" name="button"><i class="material-icons left">done</i>Enregistrer</button>
	 </div>
   </form>
</div>

<div class="col s12 show_area_pare" style="display: none;">
	<div class="col l6">
    <div class="col s12">
        <div class="col l2 m4 s6"><button class="waves-effect btn-small white black-text" onclick="$('.show_area_pare').hide(); $('.data_area_pare').toggle('drop');"><i class="material-icons black-text left">arrow_back</i>Retour</button></div>
      <div class="col l10 m8 s6"><font class="flow-text">Détails</font></div>
      </div>
    <div class="col s12">
      <table class="col s12">
        <tr><td>nom</td><td class="nom_dt label_detail"></td></tr>
        <tr><td>prenom</td><td class="prenom_dt label_detail"></td></tr>
        <tr><td>contact</td><td class="contact_dt label_detail"></td></tr>
        <tr><td>email</td><td class="email_dt label_detail"></td></tr>        
        <tr>
          <td colspan="2"><button class="btn-small grey darken-2 waves-effect waves-light bt_delete_pare" data="" type="button"><i class="material-icons left">delete</i>Supprimer</button></td>
        </tr>
      </table>
    </div>
  </div>
  <div class="col l6">
    <input type="hidden" id="PARENT_CH_ID" value="" />
    <div class="col s12"><font class="flow-text">Enfants</font>
      <button class="btn-floating btn-small white waves-effect" onclick="$('.enfant_zone').hide(); $('.add_enfant_zone').toggle('drop');"><i class="material-icons red-text">add</i></button>
    </div>
    <div class="enfant_zone col s12"></div>
    <div class="add_enfant_zone col s12" style="display: none;">
      <div class="col s12">
      <button class="btn btn-small grey waves-effect bt_kids_list"><i class="material-icons left">arrow_back</i>Liste des enfants</button>
        <select id="classe_select" class="browser-default">
          <option value="" disabled selected>Choisir la classe</option>
          <?php 
          foreach(Classe::get_all($bdd) as $classe){
            echo '<option value="'.$classe->id.'">'.$classe->name.'</option>';
          }
          ?>
        </select>
      </div>
      <div class="row enfant_load_area"></div>
    </div>
  </div>
</div>

</fieldset>

<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_pare(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/parent/parent_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.load_area').html(data);
          });
        }

    function mykids(parent_id){
      $('.LOADER').show();
      $.post(
        '../php/modules/parent/enfant.php',
        { parent: parent_id },
        function(data){
          $('.LOADER').hide();
          $('.enfant_zone').html(data);
        }
      );
    }
		
  $(document).ready(function(){
    $('select').formSelect();

    $('.bt_pass_mod').click(function(){
      var id = $(this).attr('data'), contact = $(this).attr('data-contact');
      swal({
        title: 'Confirmation',
        text: 'Voulez vous réinitialiser le mot de passe de cet utilisateur ?',
        icon: 'warning',
        buttons: true
      }).then((del)=>{
        if(del){
          $('.LOADER').show();
          $.post(
            '../php/modules/parent/parent_data.php',
            {action: 'init_pass', id: id, contact: contact},
            function(data){
              $('.LOADER').hide();
              swal('Notification', 'Réinitialisé avec succès!', 'success');
            }
          );
        }
      });
    });
        
		$('.PARENT_FOOT').pagination({
			items: parseInt($('#PARENT_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_pare').val()),
			onPageClick: function(pageNumber, event){
				init_pare(pageNumber-1);
			}
		});

      $('#classe_select').change(function(){
        $('.LOADER').show();
        $.post(
          '../php/modules/eleve/chose_enfant.php',
          { classe: $(this).val() },
          function(data){
            $('.LOADER').hide();
            $('.enfant_load_area').html(data);
          }
        );
      });
          //$('.PARENT_FOOT').pagination('selectPage', parseInt($('#CURR_PAGE').val()));

        $('.bt_delete_pare').click(function(){
          swal({
            title: 'Confirmation',
            text: 'Confirmer la suppression de l\'élément?',
            icon: 'warning',
            buttons: true
          }).then((del)=>{
            if(del){
              $('.LOADER').show();
              $.post(
                '../php/modules/parent/parent_data.php',
                {action: 'delete', id: $(this).attr('data')},
                function(data){
				  $('.LOADER').hide();
				  init_pare();
                  swal('Notification', 'Suppression avec succès!', 'success');
                }
              );
            }
          });
        });

        $('.btMod_pare').click(function(){
          $('.LOADER').show();
		  $.post(
			'../php/modules/parent/parent_data.php',
			{ action: 'read', id: $(this).attr('data') },
			function(resp){
				var data = JSON.parse(resp);
				$('#Action_pare').val('update');
          $('#Id_pare').val(data.id);
          $('#Nom').val(data.nom);
          $('#Prenom').val(data.prenom);
          $('#Contact').val(data.contact);
          $('#Email').val(data.email);
          //$('#Passwd').val(data.passwd);

				$('.data_area_pare').hide();
				$('.add_area_pare').toggle('drop');
				M.updateTextFields();
				$('.LOADER').hide();
			}
		  );
        });
		
		$('.btDetails_pare').click(function(){
          $('.LOADER').show();
		  var tid = $(this).attr('data');
      $('#PARENT_CH_ID').val(tid);
		  $.post(
			'../php/modules/parent/parent_data.php',
			{ action: 'read', id: $(this).attr('data') },
        function(resp){
          var data = JSON.parse(resp);
          $('.bt_delete_pare').attr('data', tid);
              $('.nom_dt').html(data.nom);
              $('.prenom_dt').html(data.prenom);
              $('.contact_dt').html(data.contact);
              $('.email_dt').html(data.email);
                //  $('.passwd_dt').html(data.passwd);
          $('.data_area_pare').hide();
          $('.show_area_pare').toggle('drop');
          $('.LOADER').hide();
          
          mykids(tid);

        }
		  );

      $('.bt_kids_list').click(function(){ 
        mykids(tid); 
        $('.add_enfant_zone').hide();
        $('.enfant_zone').toggle('drop');
      });
  });

        $('#formAdd_pare').submit(function(e){
          e.preventDefault();
          $('.LOADER').show();
		      var fd = new FormData($(this)[0]);
          $.ajax({
              url : '../php/modules/parent/parent_data.php',
              data : fd,
              type : 'POST',
              processData: false,
              contentType: false,
              success : function(data){
                if(data.indexOf('create')>-1){
                  swal('Notification', 'Création avec succès', 'success');
                  $('#formAdd_pare')[0].reset();
                  $('.LOADER').hide();
                }else{
                  swal('Notification', 'Mise à jour avec succès', 'success');
                  $('#Action_pare').val('create');
                  $('.LOADER').hide();
                  init_pare();
                }
              },
              error: function(resp){
                $('.LOADER').hide();
                swal('Erreur', 'Une erreur est survenue', 'error');
              }
          });
        });
		
      });
	  
	  $('.bt_back_pare').click(function(){
		  $('.show_area_pare').hide();
          $('.add_area_pare').hide(); $('.data_area_pare').toggle('drop');
          init_pare();
        });
	  
	  $('.search_form_pare').submit(function(e){
      e.preventDefault();
      $('.LOADER').show();
      $.post('../php/modules/parent/parent_util.php',
        $(this).serialize(),
        function(data){
          $('.load_area').html(data);
          $('.LOADER').hide();
        });
	  });

    </script>
