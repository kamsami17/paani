<?php
include_once('../../connectdb.php');
switch (htmlspecialchars($_POST['action'])) {
  case 'delete':
      Timetable::DELETE($bdd, htmlspecialchars($_POST['id']));
      echo 'done';
    break;
  case 'create':
      //$mat, $heure, $jour, $prof;
      $timetable = new Timetable();
      $timetable->mat = isset($_POST['mat'])? htmlspecialchars($_POST['mat']): 0;
      $timetable->heure = htmlspecialchars($_POST['hd']).' - '.htmlspecialchars($_POST['hf']);
      $timetable->jour = htmlspecialchars($_POST['jour']);
      $timetable->prof = htmlspecialchars($_POST['prof']);
      $timetable->act = htmlspecialchars($_POST['act']);
      $timetable->classe = $_SESSION['classe']->id;
      $timetable->annee = $_SESSION['annee'];
      $timetable->create($bdd);
      echo 'create';
    break;
  case 'update':
      //$mat, $heure, $jour, $prof;
      $timetable = new Timetable();
      $timetable->id = htmlspecialchars($_POST['id']);
      $timetable->mat = isset($_POST['mat'])? htmlspecialchars($_POST['mat']): 0;
      $timetable->heure = htmlspecialchars($_POST['hd']).' - '.htmlspecialchars($_POST['hf']);
      $timetable->jour = htmlspecialchars($_POST['jour']);
      $timetable->prof = htmlspecialchars($_POST['prof']);
      $timetable->act = htmlspecialchars($_POST['act']);
      $timetable->update($bdd);
      echo 'update';
    break;

  default: ?>
  <div class="col m12">
    <center><font class="flow-text">Emploi du temps</font></center>
  </div>


  <div class="col s12 list_area">
    <div class="col s12">
      <button type="button" onclick="$('.list_area').hide(); $('.add_area').toggle('drop');" class="btAdd btn red waves-effect waves-light" name="button"><i class="material-icons left">add</i>Ajouter</button>
    </div>
    <div class="col s6 m4 l2 yellow lighten-4">
      Lundi
      <div class="row">
        <?php foreach (Timetable::getDay($bdd, 1, $_SESSION['classe']->id, $_SESSION['annee']) as $key => $table) {
          echo '<div class="col s12 dayItem waves-effect waves-red" data="'.$table->id.'|'.$table->heure.'|'.$table->mat.'|'.$table->prof.'|'.$table->jour.'|'.$table->act.'">
            <div class="col s12"><b>'.$table->heure.'</b></div>
            <div class="col s12">'.($table->act==''? $table->getMat($bdd): $table->act).'</div>
            <div class="col s12"><b>'.$table->prof.'</b></div>
            <div class="col s12">
              <button class="btn-flat waves-effect moditem" data="'.$table->id.'|'.$table->heure.'|'.$table->mat.'|'.$table->prof.'|'.$table->jour.'|'.$table->act.'"><i class="material-icons">edit</i></button>
              <button class="btn-flat waves-effect delitem" data="'.$table->id.'"><i class="material-icons red-text">delete</i></button>
            </div>
          </div>';
        } ?>
      </div>
    </div>
    <div class="col s6 m4 l2 amber lighten-4">Mardi
      <div class="col row">
        <?php foreach (Timetable::getDay($bdd, 2, $_SESSION['classe']->id, $_SESSION['annee']) as $key => $table) {
          echo '<div class="col s12 dayItem waves-effect waves-red" data="'.$table->id.'|'.$table->heure.'|'.$table->mat.'|'.$table->prof.'|'.$table->jour.'|'.$table->act.'">
            <div class="col s12"><b>'.$table->heure.'</b></div>
            <div class="col s12">'.($table->act==''? $table->getMat($bdd): $table->act).'</div>
            <div class="col s12"><b>'.$table->prof.'</b></div>
            <div class="col s12">
              <button class="btn-flat waves-effect moditem" data="'.$table->id.'|'.$table->heure.'|'.$table->mat.'|'.$table->prof.'|'.$table->jour.'|'.$table->act.'"><i class="material-icons">edit</i></button>
              <button class="btn-flat waves-effect delitem" data="'.$table->id.'"><i class="material-icons red-text">delete</i></button>
            </div>
          </div>';
        } ?>
      </div>
    </div>
    <div class="col s6 m4 l2 orange lighten-4">Mercredi
      <div class="col row">
        <?php foreach (Timetable::getDay($bdd, 3, $_SESSION['classe']->id, $_SESSION['annee']) as $key => $table) {
          echo '<div class="col s12 dayItem waves-effect waves-red" data="'.$table->id.'|'.$table->heure.'|'.$table->mat.'|'.$table->prof.'|'.$table->jour.'|'.$table->act.'">
            <div class="col s12"><b>'.$table->heure.'</b></div>
            <div class="col s12">'.($table->act==''? $table->getMat($bdd): $table->act).'</div>
            <div class="col s12"><b>'.$table->prof.'</b></div>
            <div class="col s12">
              <button class="btn-flat waves-effect moditem" data="'.$table->id.'|'.$table->heure.'|'.$table->mat.'|'.$table->prof.'|'.$table->jour.'|'.$table->act.'"><i class="material-icons">edit</i></button>
              <button class="btn-flat waves-effect delitem" data="'.$table->id.'"><i class="material-icons red-text">delete</i></button>
            </div>
          </div>';
        } ?>
      </div>
    </div>
    <div class="col s6 m4 l2 deep-orange lighten-4">Jeudi
      <div class="col row">
        <?php foreach (Timetable::getDay($bdd, 4, $_SESSION['classe']->id, $_SESSION['annee']) as $key => $table) {
          echo '<div class="col s12 dayItem waves-effect waves-red" data="'.$table->id.'|'.$table->heure.'|'.$table->mat.'|'.$table->prof.'|'.$table->jour.'|'.$table->act.'">
            <div class="col s12"><b>'.$table->heure.'</b></div>
            <div class="col s12">'.($table->act==''? $table->getMat($bdd): $table->act).'</div>
            <div class="col s12"><b>'.$table->prof.'</b></div>
            <div class="col s12">
              <button class="btn-flat waves-effect moditem" data="'.$table->id.'|'.$table->heure.'|'.$table->mat.'|'.$table->prof.'|'.$table->jour.'|'.$table->act.'"><i class="material-icons">edit</i></button>
              <button class="btn-flat waves-effect delitem" data="'.$table->id.'"><i class="material-icons red-text">delete</i></button>
            </div>
          </div>';
        } ?>
      </div>
    </div>
    <div class="col s6 m4 l2 brown lighten-4">Vendredi
      <div class="col row">
        <?php foreach (Timetable::getDay($bdd, 5, $_SESSION['classe']->id, $_SESSION['annee']) as $key => $table) {
          echo '<div class="col s12 dayItem waves-effect waves-red" data="'.$table->id.'|'.$table->heure.'|'.$table->mat.'|'.$table->prof.'|'.$table->jour.'|'.$table->act.'">
            <div class="col s12"><b>'.$table->heure.'</b></div>
            <div class="col s12">'.($table->act==''? $table->getMat($bdd): $table->act).'</div>
            <div class="col s12"><b>'.$table->prof.'</b></div>
            <div class="col s12">
              <button class="btn-flat waves-effect moditem" data="'.$table->id.'|'.$table->heure.'|'.$table->mat.'|'.$table->prof.'|'.$table->jour.'|'.$table->act.'"><i class="material-icons">edit</i></button>
              <button class="btn-flat waves-effect delitem" data="'.$table->id.'"><i class="material-icons red-text">delete</i></button>
            </div>
          </div>';
        } ?>
      </div>
    </div>
    <div class="col s6 m4 l2 grey lighten-4">Samedi
      <div class="col row">
        <?php foreach (Timetable::getDay($bdd, 6, $_SESSION['classe']->id, $_SESSION['annee']) as $key => $table) {
          echo '<div class="col s12 dayItem waves-effect waves-red" data="'.$table->id.'|'.$table->heure.'|'.$table->mat.'|'.$table->prof.'|'.$table->jour.'|'.$table->act.'">
            <div class="col s12"><b>'.$table->heure.'</b></div>
            <div class="col s12">'.($table->act==''? $table->getMat($bdd): $table->act).'</div>
            <div class="col s12"><b>'.$table->prof.'</b></div>
            <div class="col s12">
              <button class="btn-flat waves-effect moditem" data="'.$table->id.'|'.$table->heure.'|'.$table->mat.'|'.$table->prof.'|'.$table->jour.'|'.$table->act.'"><i class="material-icons">edit</i></button>
              <button class="btn-flat waves-effect delitem" data="'.$table->id.'"><i class="material-icons red-text">delete</i></button>
            </div>
          </div>';
        } ?>
      </div>
    </div>
  </div>
  <div class="col s12 add_area" style="display: none;">
    <div class="col s12">
      <button type="button" name="button" class="btn-flat waves-effect bt_back"><i class="material-icons left">arrow_back</i>Retour</button>
    </div>
    <form class="col s12 m8 l6 offset-m2 offset-l3 addForm" method="post">
      <input type="hidden" name="action" id="ACTION" value="create">
      <input type="hidden" name="id" id="ID" value="">
      <div class="col s12 m12 input-field">
        <select class="browser-default" name="jour" id="JOUR" required>
          <option value="" disabled selected>Jour</option>
          <option value="1">Lundi</option>
          <option value="2">Mardi</option>
          <option value="3">Mercredi</option>
          <option value="4">Jeudi</option>
          <option value="5">Vendredi</option>
          <option value="6">Samedi</option>
        </select>
      </div>
      <div class="row">
        <div class="col s12 m6 input-field">
          <select name="mat" class="browser-default" id="MAT">
            <option value="0" disabled selected>Matière</option>
            <?php foreach (Matiere::get_all($bdd, $_SESSION['classe']->id) as $key => $mat) {
              echo '<option value="'.$mat->id.'">'.$mat->code.'</option>';
            } ?>
          </select>
        </div>
        <div class="col m1">/ OU /</div>
        <div class="col s12 m5 input-field">
          <label for="act">Entrer une activité</label>
          <input type="text" id="act" name="act">
        </div>
      </div>
      <div class="col s12 m6">
        <label for="Hd">Heure début</label>
        <input type="text" class="timepicker" name="hd" id="Hd" required>
      </div>
      <div class="col s12 m6">
        <label for="Hf">Heure fin</label>
        <input type="text" class="timepicker" name="hf" id="Hf" required>
      </div>
      <div class="col s12 m12">
        <label for="Prof">Chargé du cours</label>
        <input type="text" id="Prof" name="prof">
      </div>
      <div class="col s12">
        <button type="submit" class="btn orange waves-effect waves-light" name="button">Valider</button>
      </div>
    </form>
  </div>

  <style media="screen">
    .dayItem{ margin-bottom: 5px; border: solid 2px #ccc; border-radius: 6px; }
    /* .ttlegend { font-size: 12px; } */
    /* fieldset { border: solid 1px #ccc; } */
  </style>

  <script type="text/javascript">
    $(document).ready(function(){
      //$('select').formSelect();
      $('.timepicker').timepicker({ twelveHour: false });

      function init(){
        $('.LOADER').show();
        $.post(
          '../php/modules/timetable/timetable.php',
          {action: ''},
          function(data){
            $('.loadpane').html(data);
            $('.LOADER').hide();
          }
        );
      }
      $('.bt_back').click(function(){
         $('.addForm')[0].reset();
         init();
      });
      $('.addForm').submit(function(e){
        e.preventDefault();
        $('.LOADER').show();
        $.post(
          '../php/modules/timetable/timetable.php',
          $(this).serialize(),
          function(data){
            if(data=='create'){
              swal('Notification', 'Ajout avec succès!', 'success');
              $('.LOADER').hide();
              $('.addForm')[0].reset();
            }
            else{
              swal('Notification', 'Mise à jour avec succès');
              $('.LOADER').hide();
              init();
            }
          }
        );
      });

      // $('.dayItem').click(function(){
      $('.moditem').click(function(){
        let data = $(this).attr('data').split('|');
        //let id = data[0], heure = data[1], mat = data[2], prof = data[3], jour = data[4];
        $('#ACTION').val('update');
        $('#ID').val(data[0]);
        $('#JOUR').val(data[4]);
        $('#act').val(data[5]);
        $('#MAT').val(data[2]);
        $('#Hd').val(data[1].split(' - ')[0]);
        $('#Hf').val(data[1].split(' - ')[1]);
        $('#Prof').val(data[3]);
        $('.list_area').hide();
        $('.add_area').toggle('drop');
      });

      $('.delitem').click(function(){
        var tid = $(this).attr('data'), el = $(this);
        swal({
          title: 'Confirmation',
          text: 'Confirmer la suppression de l\'élément?',
          icon: 'warning',
          buttons: true
        }).then((del)=>{
          if(del){
            $('.LOADER').show();
            $.post(
              '../php/modules/timetable/timetable.php',
              {action: 'delete', id: tid},
              function(data){
                $('.LOADER').hide();
                if(data=='done'){
                  swal('Notification', 'Suppression avec succès!', 'success');
                  el.parent().parent().hide();
                }
                else
                  swal('Attention', 'Une erreur est survenue!', 'warning')
              }
            );
          }
        });

      });

    });
  </script>


<?php    break;
} ?>
