/* 
 * 
 */

;
(function($){
    
    console.log( location.protocol + '//' + location.host + location.pathname );
    
    $('#osp-country-field-icon').on('click', function (){
        
        $('p.osp-country-field').toggleClass('osp-country-field-show-hide', 1000);
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
    
})(jQuery);


