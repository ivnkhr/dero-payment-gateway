/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
var qrcode = require('qrcode-generator');

$(document).ready(function(){
  
  $('.more-info-button').click(function(){
    
    $(this).hide();
    $('.more-info-box').show();
    
  });
  
  $('.clipboard-copy').click(function(){
    
    $(this).parent().parent().find('.form-control').select();
    document.execCommand("copy");
    
  });
  
  $('#qr').each(function(){
    
    var typeNumber = 10;
    var errorCorrectionLevel = 'L';
    var qr = qrcode(typeNumber, errorCorrectionLevel);
    qr.addData($(this).text());
    qr.make();
    $(this).html(qr.createImgTag(3));
    
  });
  
  // Invoice Page Code 
  function initInvoiceStatusRefresh(){
    
    var statusRequest = function(){ 
      if ($("#invoice_page").data('status')==1){
        $.ajax({
          url: 'api/public/invoice/' + $('#payment_id').val(),
          dataType: 'json',
          success: function(data){
            if( $("#invoice_page").data('status') != data.status ){
              window.location.reload();
            }
            $("#invoice_page").data('remaining-ttl', data.remaining_ttl);
            
            if( data.txs.length ){
              $('.tx-box').removeClass('d-none');
              $('.tx-box tbody').html("");
              total_tx_sum = 0;
              for(var i=0; i<data.txs.length; i++){
                total_tx_sum += data.txs[i].amount;
                $('.tx-box tbody').append("<tr><td>"+data.txs[i].obfuscated_tx+"...</td><td>"+data.txs[i].confirmations+" <small>/ "+$('#invoice_page').data('confirmations')+"</small></td><td>"+(data.txs[i].amount/1000000000000)+"</td></tr>");
              }
              invoice_price = parseInt($('#invoice_page').data('price'));
              if( invoice_price > total_tx_sum ){
                $('.tx-box tfoot').html("<tr><td colspan='3'>Amount due: <b>"+((invoice_price-total_tx_sum)/1000000000000)+"</b> DERO</td></tr>");
              }else{
                $('.tx-box tfoot').html("");
              }
            }
            
          },
          timeout: 12000 //3 second timeout
        });
      }
    }
    
    setInterval(function(){ statusRequest(); }, 12 * 1000 + 1); //each 12 sec (12 sec block time)
    
    statusRequest();
    
  };

  function initInvoiceTimerClock(){
    
    var refreshIntervalId = setInterval(function(){ 
      
      remaining_ttl = parseInt( $("#invoice_page").data('remaining-ttl') );
      percentage = ( remaining_ttl ) / parseInt( $("#invoice_page").data('ttl') ) * 100;
      $("#invoice_page").data('remaining-ttl', remaining_ttl - 1);

      $('.progress-bar').css('width', percentage + '%');
      $('.progress-bar').text(new Date(remaining_ttl * 1000).toISOString().substr(11, 8));
      
      if( remaining_ttl <= 0 ) {
         $('.progress').fadeOut();
         clearInterval(refreshIntervalId);
      }
      
    }, 1000);
    
  };
  
  if($("#invoice_page").length){
    initInvoiceStatusRefresh();
    initInvoiceTimerClock();
  }
  
});

$(function () {
  
  $('[data-toggle="tooltip"]').tooltip();
  
});