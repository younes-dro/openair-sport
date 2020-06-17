/* 
 * 
 */

;
(function($){
    
//    console.log( location.protocol + '//' + location.host + location.pathname );
//console.log(ops_scripts_vars.desable_form);
//    var scripts_vars = ops_scripts_vars;
    $('#osp-flag-wrapper').on('click', function (){
        
        $('p.osp-country-field').toggleClass('osp-country-field-show-hide', 2000);
    });
    
    $('#osp_country_field').on('change',function(){
    
    var country = this.value;
    var europe = 'AD,AT,BE,CY,EE,FI,FR,GF,TF,DE,GR,GP,IE,IT,LV,LT,LU,MT,MQ,YT,MC,ME,NL,PT,RE,PM,SM,SK,SI,ES';
    var gbp = 'IM,JE,MA,GS,GB';
    if (europe.indexOf(country) >-1){
        var currency = 'EUR';
    } else if( gbp.indexOf(country) >-1){
        var currency = 'GBP';
    }else{
        var currency = 'USD';
    }
    var url = location.protocol + '//' + location.host + location.pathname ;    
    if (url.indexOf('?') > -1){
   url += '&alg_currency=' + currency + '&country=' +country;
    }else{
        url += '?alg_currency=' + currency + '&country=' +country;
    }
    window.location.href = url;
    });
    

    if ( window.ops_scripts_vars && typeof ops_scripts_vars !== 'undefined' ){
        if ( ops_scripts_vars.desable_form == 1 ){
                $('body.single-product div.product form.cart').addClass("desable_form_cart");

                $('body.single-product div.product form.cart').append('<div class="overlay_form_cart"><p>Sorry <br> this product cannot be delivered to your country</p></div>');
        }
    } 
    $( document ).ajaxComplete(function( event, xhr, settings ) {
        if(settings.url === "/?wc-ajax=get_variation"){
            var ops_data  = JSON.parse(  xhr.responseText) ;
            if ( !ops_data.max_qty > 0){
                $('button.single_add_to_cart_button').addClass('disabled');
            }
        }
    });
})(jQuery);


