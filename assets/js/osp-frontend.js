/* 
 * 
 */

;
(function($){
    
//    console.log( location.protocol + '//' + location.host + location.pathname );
//console.log(ops_scripts_vars.desable_form);
//    var scripts_vars = ops_scripts_vars;
$(document).mouseup(function(e) {
    return;
//    console.log($(e.target).hasClass('select2-selection__arrow'));
    var isSelect2 = $(e.target).hasClass('select2-selection__arrow');
    var isInput = $(e.target).hasClass('select2-search__field');
    var container = $(".osp-shipping");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0 && !isSelect2 && !isInput ) {
            $('.osp-shipping').removeClass('active');
            $('i.open-country').removeClass('ion-chevron-up');
    }
});

$('a.switcher-info').on('click',function(e){
    e.stopPropagation();
    $(this).parent().toggleClass('active');
    $('i.open-country').toggleClass('ion-chevron-up');
});
function formatState (state) {
  if (!state.id) {
    return state.text;
  }
  var baseUrl = "https://openair-sport.com/wp-content/plugins/osp/assets/flag-icon/flags/1x1";
  var $state = $(
    '<span><img src="' + baseUrl + '/' + state.element.value.toLowerCase() + '.svg" class="osp-img-flag" /> ' + state.text + '</span>'
  );
  return $state;
};
var isMobile = false; //initiate as false
// device detection
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
    isMobile = true;
}
if( !isMobile){
$(document).ready(function(){
   $('select.osp-country-field').select2({
       templateResult: formatState
   });
//   $('select.osp-currency-field').select2();
});   
}
//    $('#osp-flag-wrapper').on('click', function (){
//        
//        $('p.osp-country-field').toggleClass('osp-country-field-show-hide', 2000);
//    });
    
    $('#osp-country-field').on('change',function(){
    
    var country = this.value;
    var europe = 'AD,AT,BE,CY,EE,FI,FR,GF,TF,DE,GR,GP,IE,IT,LV,LT,LU,MT,MQ,YT,MC,ME,NL,PT,RE,PM,SM,SK,SI,ES';
    var gbp = 'IM,JE,GS,GB';
    var currency;
    if (europe.indexOf(country) >-1){
        currency = 'EUR';
    } else if( gbp.indexOf(country) >-1){
         currency = 'GBP';
    }else{
        currency = 'USD';
    }
//    alert(currency);
//    $('select.osp-currency-field')
//     .removeAttr('selected')
//     .filter('[value=' + currency + ']')
//         .attr('selected', true);
    $('select.osp-currency-field').val(currency);
    

    });
    $('.go-contiune-btn').on('click',function(){
        
        var country = $('select.osp-country-field').val();
        var currency = $('select.osp-currency-field').val();
        
        var url = location.protocol + '//' + location.host + location.pathname;
        if (url.indexOf('?') > -1) {
            url += '&currency=' + currency + '&country=' + country;
        } else {
            url += '?currency=' + currency + '&country=' + country;
        }
        window.location.href = url; 
    });
    

    if ( window.ops_scripts_vars && typeof ops_scripts_vars !== 'undefined' ){
        if ( ops_scripts_vars.desable_form == 1 ){
            
            $('body.single-product div.product form.cart').addClass("desable_form_cart");
            $('body.single-product div.product form.cart').append('<div class="overlay_form_cart"><p>Sorry <br> this product cannot be delivered to your country</p></div>');    
            $('button.single_add_to_cart_button').addClass('disabled');    
        }
    } 
    $( document ).ajaxComplete(function( event, xhr, settings ) {
        if(settings.url === "/?wc-ajax=get_variation"){
            var ops_data  = JSON.parse(  xhr.responseText) ;
            if ( !ops_data.max_qty > 0){
                $('button.single_add_to_cart_button').addClass('disabled');
                if ( $('div.woocommerce-variation-availability') ){
                 $('div.woocommerce-variation-availability').html('<p class="stock out-of-stock">Out of Stock</p>');
                }
            }
        }
    });
    //Keep ALi2Woo shipping sybmbol
    $( document ).ajaxComplete(function( event , xhr, settings ){
        $('div.a2w_to_shipping').find('select option').each( function(){
            var s = $(this).text();
            
            var st = s.replace( /,\s(\d+).\d{2}\s(\$|€|£)/ ,'') ;
            $(this).text(st);
        } );    
    });
    
})(jQuery);
