$(document).ready(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click','button.remove-cart', function(){
        removeCart( $(this).data("product-id") );
    });

    $(document).on('click','button.add-cart', function(){
        addCart( $(this).data("product-id"), $(this).data("campaign-id") );
    });

    $(document).on("change", "input.update-cart", function() {
        updateCart( $(this).data("product-id"), $(this).val() );
    });

});

function addCart(id, campaign) {
    $.ajax({
        type: 'GET',
        url: `/${window.Locale}/cart/add/${id}/${campaign}`,
    }).done(function(response){
        $('#cart_popup').html(response.view);
        toastJS({
          type:'success',
          title:'Cart',
          body: response.message
        });
    }).fail(function( response ) {
      toastJS({
        type:'danger',
        title:'Error',
        body: response.responseJSON.error ?? 'Something went wrong :/'
      });
    });
};

function removeCart(id){
    $.ajax({
        type: 'POST',
        url: `/${window.Locale}/cart/remove`,
        data: { id },
    }).done(function(response){
      $('#cart_popup').html(response);
      location.reload();
    }).fail(function( response ) {
      toastJS({
        type:'danger',
        title:'Error',
        body:'Something went wrong :/'
      });
    });
}

function updateCart(id,quantity) {
    $.ajax({
        type: 'POST',
        url: `/${window.Locale}/cart/update`,
        data: { id, quantity }
    }).done(function(response){
        $('#subtotal-cart').text(response);
    }).fail(function( response ) {
      toastJS({
        type:'danger',
        title:'Error',
        body:'Something went wrong :/'
      });
    });
};
