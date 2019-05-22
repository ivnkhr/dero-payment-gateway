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
  
});

$(function () {
  
  $('[data-toggle="tooltip"]').tooltip();
  
});