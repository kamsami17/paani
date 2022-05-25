<?php
include_once('../connectdb.php');
include_once('../api.php');
$entity = new Entity();
Entity::$bdd = $bdd;
$entity->pageSize = 10;

$page = isset($_POST['page'])? htmlspecialchars($_POST['page']): 0;

switch (htmlspecialchars($_POST['action'])) {
  case 'readUser':
      $activity = new Activity();
      $activity->id = htmlspecialchars($_POST['id']);
      $activity->read($bdd);
      echo json_encode($activity);
    break;
  case 'delete':
      Activity::DELETE($bdd, htmlspecialchars($_POST['id']));
    break;

  case 'create':
      $activity = new Activity();
      $activity->title = htmlspecialchars($_POST['name']);
      //$activity->classe = htmlspecialchars($_POST['category']);
      $activity->color = htmlspecialchars($_POST['color']);
      $activity->type_act = htmlspecialchars($_POST['type_act']);
      $activity->ecole = $_SESSION['user']->ecole;
      $activity->description = htmlspecialchars($_POST['description']);
      $activity->date_act = htmlspecialchars($_POST['date_act']);
      if(isset($_FILES['classFile'])){
        $path = pathinfo($_FILES['classFile']['name']);
        $ext = $path['extension'];
        $name = $_SESSION['user']->id.'_activity_'.date("Ymd_His").'.'.$ext;
        move_uploaded_file($_FILES['classFile']['tmp_name'], '../act_files/'.$name);
        $activity->img = $name;
      }
      else{
        $activity->img='';
      }
      $activity->annee = $_SESSION['annee'];
      $activity->create($bdd);

      // $nn = $bdd->prepare('INSERT INTO notif(title, content, ecole, parent, type_notif, annee) VALUES(?,?,?,?,?, ?)');
      // $nn->execute(array(
      //   htmlspecialchars($_POST['type_act'])=='devoir'? 'Devoir prévu le '.dateb($activity->date_act): 'Nouvelle activité',
      //   Activity::show_concern($bdd, explode(',' , htmlspecialchars($_POST['category']))),
      //   $_SESSION['college'],
      //   0,
      //   'activity',
      //   $_SESSION['annee']
      // ));
      //$notif = Entity::lastID($bdd, 'notif');

      foreach (explode(',' , htmlspecialchars($_POST['category'])) as $key => $value) {
        $cact = new Cact();
        $cact->classe = $value;
        $cact->activity = $activity->id;
        if($value!=0){
          $cact->create($bdd);
          // $nc = $bdd->prepare('INSERT INTO notif_classe(classe, notif) VALUES(?,?)');
          // $nc->execute(array($value, $notif));
        }
      }

    break;

  case 'update':
      $activity = new Activity();
      $activity->title = htmlspecialchars($_POST['name']);
      $activity->id = htmlspecialchars($_POST['id']);
      $activity->ecole = $_SESSION['user']->ecole;
      $activity->description = htmlspecialchars($_POST['description']);
      $activity->date_act = htmlspecialchars($_POST['date_act']);
      if(isset($_FILES['classFile'])){
        $path = pathinfo($_FILES['classFile']['name']);
        $ext = $path['extension'];
        $name = $_SESSION['user']->id.'_activity_'.date("Ymd_His").'.'.$ext;
        move_uploaded_file($_FILES['classFile']['tmp_name'], '../act_files/'.$name);
        $activity->img = $name;
      }
      else{
        $activity->img= htmlspecialchars($_POST['path']);
      }
      $activity->update($bdd);

      foreach (explode(',' , htmlspecialchars($_POST['category'])) as $key => $value) {
        $cact = new Cact();
        $cact->classe = $value;
        $cact->activity = $activity->id;
        if($value!=0){
          $cact->create($bdd);
        }

      }
    break;

  default: echo '<input type="hidden" value="'.($page+1).'" id="CURR_PAGE" />'; ?>
  <fieldset>
    <legend>
      <button class="btn" onclick="home();"><i class="material-icons">arrow_back</i></button>
      Activités de l'établissement
    </legend>
  <!-- <div class="col m10 offset-m1 col s12">
    <button type="button" onclick="go_back();" class="btn grey BTBACK darken-4 left" name="button"><i class="material-icons left">arrow_back</i>Retour</button>
    <center><font class="flow-text">Les activités</font></center>
  </div> -->
  <div class="col m12 s12 l12">

  </div>
  <div class="col m10 offset-m1">
    <table class="col m12 striped responsive-table">
      <thead>
        <tr>
          <td colspan="7">
            <form class="input-field col s12 m8 l6" id="searchForm">
                <input id="search" placeholder="Rechercher..." class="search" value="<?php echo isset($_POST['search'])? htmlspecialchars($_POST['search']) : ''; ?>" type="text"/>
                <i class="material-icons prefix">search</i>
            </form>
          </td>
        </tr>
        <tr>
          <th>N°</th>
          <th>Image</th>
          <th>Titre</th>
          <th>Description</th>
          <th>Date</th>
          <th>Concerné</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $query = 'SELECT id, img, title, description, date_act, type_act FROM activity WHERE annee='.$_SESSION['annee'].' AND ecole='.$_SESSION['user']->ecole.(isset($_POST['search'])? ' AND title LIKE \'%'.htmlspecialchars($_POST['search']).'%\'': '');
        foreach ($entity->rawPage($query, 'date_act', $page) as $key => $value) {
          echo '<tr>
            <td>'.($page*$entity->pageSize+$key+1).'</td>
            <td>'.
              ($value['type_act']=='activity'? '<img src="../php/act_files/'.$value['img'].'" class="materialboxed" style="height: 80px; max-width: 100%;" />':'<i class="material-icons">book</i>')
            .'</td>
            <td>'.$value['title'].'</td>
            <td>'.$value['description'].'</td>
            <td>'.$value['date_act'].'</td>
            <td>'.Activity::getConcern($bdd, $value['id']).'</td>
            <td>
              <button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btMod"><i class="material-icons">edit</i></button>
              <button data="'.$value['id'].'" type="button" class="btn-flat waves-effect btDel"><i class="material-icons red-text">delete</i></button>
            </td>
          </tr>';
        }
        $entity->psize($query, 'ACT_SIZE');
       ?>
      </tbody>
    </table>
    <div class="col s12 ACT_FOOT"></div>

  </div>

  <div class="col m12">
    <a class="waves-effect waves-light btn orange darken-2 modal-trigger" href="#modal1">Ajouter</a>

     <div id="modal1" class="modal">
       <div class="modal-content">
         <form class="row" id="formAdd">
           <input type="hidden" name="id" id="id" value="">
           <input type="hidden" name="path" id="Path" value="">
           <input type="hidden" name="action" id="action_act" value="create">
           <center><font class="flow-text">Ajouter une activité</font></center>
           <div class="col s12">
             <button type="button" style="background: rgba(51, 231, 255, 0.99);" class="btn-flat btt_act bta" name="button">Activité</button>
             <button type="button" class="btn-flat btt_dev bta" name="button">Devoir</button>
           </div>
           <div class="col m6 input-field TITLE_DIV">
             <label for="Nom">Titre</label>
             <input type="text" name="name" id="Nom">
           </div>
           <div class="col m6 input-field DESC_DIV">
             <label for="Prenom">Description (optionnelle)</label>
             <input type="text" name="description" id="Prenom">
           </div>
           <div class="col m6 input-field IMG_DIV">
             <label for="classFile">Image</label>
             <input type="file" name="classFile" class="classFile" />
           </div>

           <div class="col m6 input-field">
             <select name="category" id="Cat" multiple>
               <option value="0">Toute l'école</option>
               <?php
                foreach (Classe::get_all($bdd) as $key => $value) {
                  echo '<option value="'.$value->id.'">'.$value->nom.'</option>';
                }
                ?>
             </select>
           </div>

           <div class="col m6 input-field">
             <label for="Dact">Date</label>
             <input type="text" id="Dact" name="date_act" class="datepicker" required />
           </div>

           <div class="col s12 COLOR_DIV">
             <div class="col s2 color_item orange" data="orange"><span class="orange"></span></div>
             <div class="col s2 color_item blue" data="blue"><span class="blue"></span></div>
             <div class="col s2 color_item green" data="green"><span class="green"></span></div>
             <div class="col s2 color_item yellow" data="yellow"><span class="yellow"></span></div>
             <div class="col s2 color_item grey" data="grey"><span class="grey"></span></div>
             <div class="col s2 color_item teal" data="teal"><span class="teal"></span></div>
           </div>

           <div class="col m12 input-field">
             <button type="submit" class="btn white black-text waves-effect" name="button"><i class="material-icons left">done</i>Enregistrer</button>
             <button type="button" class="modal-close btn right white red-text waves-effect" name="button"><i class="material-icons left">clear</i>Fermer</button>
           </div>
         </form>
       </div>
     </div>
  </div>

</fieldset>
  <style media="screen">
    .modal{max-height: 95% !important }
    .color_item span{height: 30px; width: 30px; border-radius: 50%;}
    .color_item{ text-align: center; height: 35px;}
    .COLOR_DIV{height: 35px;}
    /* td { border: solid 1px #ccc; } */
  </style>
  <script type="text/javascript">
    function init(pg){
        pg = pg || 0;
        $('.LOADER').show();
        $.post('../php/utils/act.php',
          {page: pg, action: ''},
          function(data){
            $('.LOADER').hide();
            $('.loadPane').html(data);
        });
      }
    $(document).ready(function(){
      $('.modal').modal({
        endingTop: '5%'
      });
      $('.ACT_FOOT').pagination({
        items: parseInt($('#ACT_SIZE').val()),
        itemsOnPage: PAGE_SIZE,
        cssStyle: 'light-theme',
        currentPage: parseInt($('#CURR_PAGE').val()),
        onPageClick: function(pageNumber, event){
            init(pageNumber-1);
        }
      });

      $('#searchForm').submit(function(e){
         e.preventDefault();
         $('.LOADER').show();
         $.post(
           '../php/utils/act.php',
           {action: '', search: $('.search').val()},
           function(data){
             $('.LOADER').hide();
             $('.loadPane').html(data);
           }
         );
       });
       var color = 'blue', type_act='activity';
      $('.datepicker').datepicker({format: 'yyyy-mm-dd'});
      $('.color_item').click(function(){
          $('.color_item').css('border-bottom', 'none');
          $(this).css('border-bottom', 'solid 2px #fff');
          color = $(this).attr('data');
      });
      $('.btt_act').click(function(){
        $('.DESC_DIV').show();
        $('.IMG_DIV').show();
        $('.bta').css('background', 'none');
        $(this).css('background', 'rgba(51, 231, 255, 0.99)');
        type_act='activity';
      });
      $('.btt_dev').click(function(){
        $('.DESC_DIV').hide();
        $('.IMG_DIV').hide();
        $('.bta').css('background', 'none');
        $(this).css('background', 'rgba(51, 231, 255, 0.99)');
        type_act='devoir';
      });

      $('.materialboxed').materialbox();
      $('select').formSelect();
      $('.btPage0').click(function(){
        $('.LOADER').show();
        $.post('../php/utils/act.php',
        {action: '', page: $(this).attr('data')},
        function(data){
          $('.LOADER').hide();
          $('.loadPane').html(data);
        });
      });
      function load(){
        $('.LOADER').show();
        $.post('../php/utils/act.php',
        {action: ''},
        function(data){
          $('.LOADER').hide();
          $('.loadPane').html(data);
        });
      }

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
              '../php/utils/act.php',
              {
                action: 'delete',
                id: $(this).attr('data')
              },
              function(data){
                $('.LOADER').hide();
                swal('Notification', 'Suppression avec succès', 'success');
              }
            );
            $(this).parent().parent().hide();
          }
        });


      });

      $('.btMod').click(function(){
        $.post(
          '../php/utils/act.php',
          {action: 'readUser', id: $(this).attr('data')},
          function(data){
            let prod = JSON.parse(data);
            $('#Nom').val(prod.name);
            $('#Prenom').val(prod.description);
            $('#Dnaiss').val(prod.price);
            $('#Cnib').val(prod.quantity);
            $('#Cat').val(prod.category);
            $('#action_act').val('update');
            $('#Path').val(prod.image);
            $('#id').val(prod.id);
            M.updateTextFields();
            $('#modal1').modal('open');
          }
        );
      });

      $('#formAdd').submit(function(e){
        e.preventDefault();

        $('#modal1').modal('close');
        $('.LOADER').show();
        var fd = new FormData();
        if($('.classFile').get(0).files.length != 0)
          fd.append('classFile', $('.classFile')[0].files[0]);
        //fd.append('classFile', $('.classFile')[0].files[0]);
        //fd.append('category', $('#Cat').find(':selected').attr('value'));
        fd.append('category', $('#Cat').val());
        fd.append('action', $('#action_act').val());
        fd.append('name', $('#Nom').val());
        fd.append('date_act', $('#Dact').val());
        fd.append('description', $('#Prenom').val());
        fd.append('color', color);
        fd.append('type_act', type_act);
        fd.append('id', $('#id').val());
        fd.append('path', $('#Path').val());

        $.ajax({
            url : '../php/utils/act.php',
            data : fd,
            type : 'POST',
            processData: false,
            contentType: false,
            success : function(data){
              $('.LOADER').hide();
              if(data=='update') $('#action_act').val('create');
              swal('Notification', 'Opération avec succès', 'success');
              load();
            }
          });
      });
    });

  </script>
<?php  break;
}

 ?>
