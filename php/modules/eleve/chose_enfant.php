<?php
  include_once('../../connectdb.php');
  include_once('../../api.php');
  $entity = new Entity();
  Entity::$bdd = $bdd;
  $entity->pageSize = 10;

  $_SESSION['classe'] = isset($_POST['classe'])? htmlspecialchars($_POST['classe']) : (isset($_SESSION['classe'])? $_SESSION['classe']: 0);

  $page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;
  $_POST['action'] = isset($_POST['action'])? htmlspecialchars($_POST['action']): '';
  echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE_elev" />'; 
  
	$query  = 'SELECT * FROM eleve WHERE id IN (SELECT eleve FROM classe_eleve WHERE classe='.$_SESSION['classe'].' AND annee='.$_SESSION['annee'].')'.(isset($_POST['search'])? ' AND '.htmlspecialchars($_POST['option']).' LIKE \''.htmlspecialchars($_POST['search']).'%\'' : '');
  
?>
  
<div class="data_area_elev col s12">	
	<table class="col s12 striped">
	  <thead>
		<tr>
			<td colspan="8">
				<form class="col s12 search_form_elev">
					<input type="hidden" name="action" value="" />
					<div class="col s4 input-field">
              <select name="option" required>
                <option value="nom">nom</option>
                 <option value="prenom">prenom</option>
                 <option value="date_naissance">date_naissance</option>
                 <option value="matricule">matricule</option>
                 <option value="uname">uname</option>
                 <option value="passwd">passwd</option>
                 <option value="parent">parent</option>
              </select>

					</div>
					<div class="col s6 input-field">
						<label for="search_text_elev">Search . . .</label>
						<input type="search" id="search_text_elev" class="search_text" name="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']): ''; ?>" />
					</div>
					<div class="col s2"><button type="submit" class="btn-floating white waves-effect"><i class="material-icons black-text">search</i></button></div>
				</form>
			</td>
		</tr>
		<tr>
        <th class="table_counter">N°</th>
        <th>Nom</th>
        <th>Prenom</th>
        <th>Date de naissance</th>
        <th>Matricule</th>
        <!-- <th>Uname</th>
        <th>Passwd</th>
        <th>Parent</th> -->
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
            <td>'.labdate($value['date_naissance']).'</td>
            <td>'.$value['matricule'].'</td>
			  <td>
				<button data="'.$value['id'].'" type="button" class="btn-flat waves-effect bt_add_enfant"><i class="material-icons">touch_app</i></button>
			  </td>
			</tr>';
		  }
		  $entity->psize($query, 'ELEVE_SIZE');
		?>
	  </tbody>
	</table>
	<div class="ELEVE_FOOT col s12"></div>
</div>

<style> .label_detail { font-weight: bold; } </style>

    <script type="text/javascript">
		function init_elev(pg){
          pg = pg || 0;
          $('.LOADER').show();
          $.post('../php/modules/eleve/eleve_util.php',
            {page: pg},
            function(data){
              $('.LOADER').hide();
              $('.enfant_load_area').html(data);
          });
        }
		
      $(document).ready(function(){
        $('select').formSelect();
        
		$('.ELEVE_FOOT').pagination({
			items: parseInt($('#ELEVE_SIZE').val()),
			itemsOnPage: PAGE_SIZE,
			cssStyle: 'light-theme',
			currentPage: parseInt($('#CURR_PAGE_elev').val()),
			onPageClick: function(pageNumber, event){
				init_elev(pageNumber-1);
			}
		});

        $('.bt_add_enfant').click(function(){
            var eid = $(this).attr('data');
            swal({
                title: 'Confirmation',
                text: 'Confirmer l\'ajout dans la liste des enfants?',
                icon: 'info',
                buttons: true
            }).then((del)=>{
                if(del){ 
                    $('.LOADER').show();
                    $.post(
                        '../php/modules/eleve/eleve_data.php',
                        {
                            action: 'set_parent',
                            parent: $('#PARENT_CH_ID').val(),
                            eleve: eid
                        },
                        function(data){
                            $('.LOADER').hide();
                            if(data=='done'){
                                swal('Notification', 'Ajout avec succès', 'success');
                            }
                            else swal('Attention!', 'Une erreur est survenue', 'warning');
                        }
                    );
                }
            });
        });
          
		
      });
	  
	  $('.search_form_elev').submit(function(e){
		e.preventDefault();
		$('.LOADER').show();
		$.post('../php/modules/eleve/eleve_util.php',
		$(this).serialize(),
		function(data){
			$('.enfant_load_area').html(data);
			$('.LOADER').hide();
		});
	  });

    </script>
