<?php include_once('../../connectdb.php'); 
$q = $bdd->prepare('SELECT * FROM eleve WHERE parents=?');
$q->execute(array(htmlspecialchars($_POST['parent'])));

?>
<table class="striped">
    <tr>
        <th>Nom</th>
        <th>Prenom</th>
        <th>Date de naissance</th>
        <th>Matricule</th>
        <th>Action</th>
    </tr>
<?php
    while($data = $q->fetch()){
        echo '<tr>
            <td>'.$data['nom'].'</td>
            <td>'.$data['prenom'].'</td>
            <td>'.$data['date_naissance'].'</td>
            <td>'.$data['matricule'].'</td>
            <td>
                <button class="btn-flat waves-effect bt_del_enfant" data="'.$data['id'].'"><i class="material-icons red-text">delete</i></button>
            <td>
        </tr>';
    }
?>
</table>
<script>
    $('.bt_del_enfant').click(function(){
        var eid = $(this).attr('data');
        var elm = $(this);
        swal({
            title: 'Confirmation',
            text: 'Retirer de la liste des enfants?',
            icon: 'info',
            buttons: true
        }).then((del)=>{
            if(del){ 
                $('.LOADER').show();
                $.post(
                    '../php/modules/eleve/eleve_data.php',
                    {
                        action: 'unset_parent',
                        eleve: eid
                    },
                    function(data){
                        $('.LOADER').hide();
                        if(data=='done'){
                            swal('Notification', 'L\'enregistrement a été retiré de la liste des enfants', 'success');
                            elm.parent().parent().toggle('drop');
                        }
                        else swal('Attention!', 'Une erreur est survenue', 'warning');
                    }
                );
            }
        });
    });
</script>
