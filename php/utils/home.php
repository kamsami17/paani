
<div class="center col s12 flow-text btitle">
    Bienvenue dans votre tableau de bord (administration)
</div>
<div class="center col s12 l2 offset-l1">
    <img src="../img/logo.jpeg" alt="" style="max-width: 100%;" />
    <?php 
        include_once('../connectdb.php');
        $st =  Eleve::minstat($bdd, $_SESSION['user']->ecole);     
    ?>
    <div class="col s12" style="margin-bottom: 15px; border-bottom: solid 1px #ccc;">
        <font><b>Elèves</b></font><br />
        <font class="flow-text"><?php echo $st['eleves']; ?></font>
    </div>
    <div class="col s12" style="margin-bottom: 15px; border-bottom: solid 1px #ccc;">
        <font><b>Parents d'élèves</b></font><br />
        <font class="flow-text"><?php echo $st['eleves']; ?></font>
    </div>
    <!-- <div class="col s12" style="margin-bottom: 15px; border-bottom: solid 1px #ccc;">
        <font><b>Messages envoyés</b></font><br />
        <font class="flow-text"><?php #echo Message::ctmsg($bdd, $_SESSION['user']->ecole); ?></font>
    </div> -->
</div>
<?php include_once('../connectdb.php'); 
?>
<div class="col s12 l8 offset main_menu">
    <div class="col l4 m6 s12">
        <div class="col s12 card waves-effect waves-orange main_menu_item" data="../php/modules/classe/classe_util.php">
            <div class="col s12 card-title blue-text text-darken-4"><i class="material-icons left">view_quilt</i>Classes / Eleves</div>
            <p class="col s12">Gestion des classes et des <b>élèves</b></p>
        </div>
    </div>
    <div class="col l4 m6 s12">
        <div class="col s12 card waves-effect waves-orange main_menu_item" data="../php/utils/act.php">
            <div class="col s12 card-title blue-text text-darken-4"><i class="material-icons left">local_activity</i>Activités</div>
            <p class="col s12">Gestion des activités de l'établissement</p>
        </div>
    </div>
    <div class="col l4 m6 s12">
        <div class="col s12 card waves-effect waves-orange main_menu_item" data="../php/modules/parent/parent_util.php">
            <div class="col s12 card-title blue-text text-darken-4"><i class="material-icons left">group</i>Parents</div>
            <p class="col s12">Gestion des <b>parents d'élèves</b></p>
        </div>
    </div>
    <div class="col l4 m6 s12">
        <div class="col s12 card waves-effect waves-orange main_menu_item" data="../php/modules/users/users_util.php">
            <div class="col s12 card-title blue-text text-darken-4"><i class="material-icons left">group</i>Personnel</div>
            <p class="col s12">Gestion du <b>personnel administratif</b></p>
        </div>
    </div>
    <div class="col l4 m6 s12">
        <div class="col s12 card waves-effect waves-orange main_menu_item" data="../php/modules/fees/fees_util.php">
            <div class="col s12 card-title blue-text text-darken-4"><i class="material-icons left">view_quilt</i>Scolarité</div>
            <p class="col s12">Gestion de la scolarité</p>
        </div>
    </div>
    <div class="col l4 m6 s12">
        <div class="col s12 card waves-effect waves-orange main_menu_item" data="../php/modules/message/sms_history.php">
            <div class="col s12 card-title blue-text text-darken-4"><i class="material-icons left">history</i>Historique</div>
            <p class="col s12"><?php echo '<b>'.Message::ctmsg($bdd, $_SESSION['user']->ecole).'</b> messages'; ?></p>
        </div>
    </div>
    <div class="col l4 m6 s12">
        <div class="col s12 card waves-effect waves-orange main_menu_item0" data="../php/modules/classe/classe_util.php">
            <div class="col s12 card-title blue-text text-darken-4"><i class="material-icons left">view_quilt</i>Mon compte</div>
            <p class="col s12">Paramètres du compte</p>
        </div>
    </div>
    
</div>

<style>
    .main_menu_item, .main_menu_item0 { height: 110px; border-radius: 8px; }
    .btitle { margin: 30px;} 
</style>

<script>
    $('.main_menu_item0').click(function(){ swal('Information', 'En cours de construction', 'info'); });

    $('.main_menu_item').click(function(){
        $('.LOADER').show();
        $.post( 
            $(this).attr('data'),
            {action: ''},
            function(data){
                $('.LOADER').hide();
                $('.load_area').html(data);
            }
        );
    });
</script>