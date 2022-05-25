$('.connect_form').submit(function(e){
    e.preventDefault();
    $('.loader').show();
    $.post(
        'php/modules/users/users_data.php',
        $(this).serialize(),
        function(data){
            if(data=='success') document.location.href = 'dashboard';
            else{
                $('.loader').hide();
                swal('Attention', 'Informations incorrectes! Veuillez réessayer', 'warning');
                $('.connect_form')[0].reset();
            }
        }
    );
});