<?php 
    include_once('../../connectdb.php');
    $classe = Classe::read($bdd, htmlspecialchars($_POST['id']));
    echo '<input type="hidden" id="CLASSE" value="'.$classe->id.'" />';
    $_SESSION['classe'] = $classe;
?>

<div class="col l2">
    <div class="col s12 flow-text blue-text text-darken-4" style="margin-bottom: 20px;"><?php echo $classe->name; ?></div>
    <div class="col s12 waves-effect left_menu_item" data="../php/modules/eleve/eleve_util.php"><i class="material-icons left">sentiment_satisfied_alt</i>Elèves</div>
    <div class="col s12 waves-effect left_menu_item" data="../php/modules/matiere/matiere_util.php"><i class="material-icons left">list</i>Matières</div>
    <div class="col s12 waves-effect left_menu_item" data="../php/modules/trimestre/trimestre_util.php"><i class="material-icons left">list</i>Trimetres</div>
    <div class="col s12 waves-effect left_menu_item0 btn_open_note"><i class="material-icons left">note</i>Notes</div>
    <div class="col s12 waves-effect left_menu_item" data="../php/modules/timetable/timetable.php"><i class="material-icons left">today</i>Emploi du temps</div>
    <div class="col s12 waves-effect waves-light left_menu_item0 grey darken-3 white-text bt_back"><i class="material-icons left">arrow_back</i>Retour</div>
</div>
<div class="col l10 loadpane"></div>

<div id="modal_open_note" class="modal">
  <div class="modal-content">
    <div class="col m12">
        <font class="flow-text">Choisir la matière</font>
    </div>
    <div class="col s12 mat_list"></div>
  </div>
</div>



<style>
    .left_menu { border-right: solid 1px #ccc; } 
    .left_menu_item, .left_menu_item0 { line-height: 40px; border-bottom: solid .8px #ccc; }
</style>
<script>
    $('.bt_back').click(function(){
        $('.LOADER').show();
        $.post(
            '../php/modules/classe/classe_util.php',
            function(data){
                $('.LOADER').hide();
                $('.load_area').html(data);
            }
        );
    });

    function ini(url){
        $('.LOADER').show();
        $.post(
            url,
            { classe: $('#CLASSE').val(), action: '' },
            function(data){
                $('.LOADER').hide();
                $('.loadpane').html(data);
            }
        );
    }
    ini('../php/modules/eleve/eleve_util.php');

    $('.left_menu_item').click(function(){
        $('.left_menu_item').css('box-shadow', 'none');
        $('.left_menu_item0').css('box-shadow', 'none');
        $(this).css({'box-shadow': 
            '0 0 4px 2px #fff, 0 0 8px 3px rgb(0, 255, 179)',
            'transition-duration': '.2s'});
        ini($(this).attr('data'));
    });

    $(document).ready(function(){
        $('.modal').modal();
        $('.btn_open_note').click(function(){
            $('.left_menu_item').css('box-shadow', 'none');
            $(this).css({'box-shadow': 
                '0 0 4px 2px #fff, 0 0 8px 3px rgb(0, 255, 179)',
                'transition-duration': '.2s'});
            $('.LOADER').show();
            $.post(
                '../php/modules/matiere/matiere_data.php',
                {action: 'get_all'},
                function(data){
                    $('.LOADER').hide();
                    $('.mat_list').html(data);
                    $('#modal_open_note').modal('open');
                }
            );
            
        });
    });
</script>