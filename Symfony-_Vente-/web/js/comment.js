/*$(document).ready(function(){
    $('#envoyer').click(function(event){
        $.ajax({
            method: 'POST',
            url: '/message/new',
            data: { message: $('#commentairebundle_message_message').val() },
            error: function(e) {
                console.log(e);
            },
            success: function(data) {
                $('#commentairebundle_message_message').before('<span class="text-info">Enregistrement reussi</span>');
                $('#commentairebundle_message_message').val('');
            }
        });
        event.preventDefault();
      });


    $('#del').click(function(e){
    alert('sure to delete');
      $.ajax({
        url: $(this).attr('href'),//valeur anle attribut href no alainy = '/delete/{id}
        method: 'DELETE',
        success: function(data) {
            console.log('supprimer avec succes!');
            window.document.location = '/message';
        },
        error: function(e){
            console.log('Delete not success!!!')
        }
      });
      e.preventDefault();
    });
});


