$(function(){

	var server = new ServerFunctions();
  let branchid = $('#branchid').val()
  let operator = $('#operator').val()
  server.branchid = parseInt(branchid)
  server.operator = operator
 
	Template7.registerHelper('$price$', function (price){

        return Number(price).toFixed(2);

  });
  
  //server.printDiv = c_sharp.print(server);

	  var template_item_bill = Template7.compile($('#template-item-bill').html());

    $('#ItemListBody').children(':nth-child(1)').addClass('selected');

    $('#ItemListBody').children(':nth-child(1)').addClass('table-info');

    $('#ItemListBody').children(':nth-child(1)').children(':nth-child(2)').children(':nth-child(1)').focus();

    $('#ItemListBody').on('keydown','.prod_qty',function(e){

    	switch(e.keyCode){

    		case 13:

                var lastTime = $('#lasttime').val();
                var curTime = new Date().getTime();

                if (lastTime && (curTime - lastTime < 1000)) {
                  if(!e.notPop){

                    $('#lasttime').val( null);
                    $(document).trigger(

                      $.Event( 'keydown', { keyCode: 68, which: 68 , ctrlKey:true } )

                    );
                  }
                }else{

                  $('#lasttime').val( curTime);
                  let s_no = parseInt($(this).closest('tr').siblings(':last-child').children(':first-child').html());

                  let qty = $(this).val();

                  let id = $(this).closest('tr').attr('data-id');

                  if (id) {

                    if (qty && qty != 0) {

                         let code =  $(this).closest('tr').find('.prod_code').val();

                         server.UpdateExistingQuantity($(this),code,qty)

                         if (!e.hasOwnProperty('trigger')) {
                          
                          setTimeout(function(){

                              $('#ItemListBody tr').each(function(){

                               if (!$(this).attr('data-id') || $(this).attr('data-id') ==0){

                                 $(this).children(':nth-child(2)').find('input.prod_code').focus();
                              
                                 $(this).click();

                                 return false;

                                }

                              })

                          },100)

                         }

                         // server.checkFreeItem(id,qty);

                      }

                    else{

                        server.unsetRow($(this));

                        if (id != 0 && id !='' && $('tr[data-id="'+id+'"]').length < 1) {
                          server.deleteObject(id)
                        }

                        $(this).closest('tr').children(':nth-child(2)').children(':first-child').focus();

                        $(this).closest('tr').click();

                        server.TrackUnsavedBills(server)
                      }

                  }

                  else{

                    server.unsetRow($(this));

                    $(this).closest('tr').children(':nth-child(2)').children(':first-child').focus();

                    $(this).closest('tr').click();

                  }

                  if ($(this).closest('tr').is(':nth-last-child(2)') ) {

                      server.appendRow(s_no,'#ItemListBody');

                  }
                  if ($(this).closest('tr').is(':nth-last-child(1)')) {
                    server.appendRow(parseInt($(this).closest('tr').children(':first-child').html()),'#ItemListBody');
                  }

                };
        break;
        case 38:

  	        e.preventDefault();

  	        if ( $(this).closest('tr').prev('tr').find('.prod_qty').focus()){
               $('#ItemListBody tr').removeClass('selected table-info')

  	           $(this).closest('tr').prev('tr').addClass('selected table-info')

  	        }

        break;

        case 40:

            e.preventDefault();

  	        if ($(this).closest('tr').next('tr').attr('data-id') &&$(this).closest('tr').next('tr').attr('data-id') !=0 ){
                 
                 $(this).closest('tr').next('tr').find('.prod_qty').focus()

                 $('#ItemListBody tr').removeClass('selected table-info')

  	           $(this).closest('tr').next('tr').addClass('selected table-info')

  	        }
  	        else{
                 $(this).closest('tr').next('tr').find('.prod_code').focus()

                 $('#ItemListBody tr').removeClass('selected table-info')

  	           $(this).closest('tr').next('tr').addClass('selected table-info')	        	
  	        }


        break;
        case 37:

            e.preventDefault();

  	        if ($(this).closest('tr').attr('data-id') && $(this).closest('tr').attr('data-id') !=0){
              
               $(this).closest('tr').find('.prod_code').focus()

  	        }


        break;
        case 39:

            e.preventDefault();

  	        if ($(this).closest('tr').attr('data-id') && $(this).closest('tr').attr('data-id') !=0){
              
               $(this).closest('tr').find('.prod_code').focus()

  	        }


        break;
        case 9:
           
           e.preventDefault();

           $('.logout').focus()

        break;

        default:



      	break;

    	}

	  })

    $('#ItemListBody tr').on('change','.prod_qty',function(){

      $(this).trigger(

          $.Event( 'keydown', { keyCode: 13, which: 13 , notPop:true} )

       );

    })
    
    $('#ItemListBody tr .prod_qty').focusin(function(e){
       let id = $(this).closest('tr').attr('data-id');
       if(server.items[id] && server.items[id].weighingscale == 1){

          let qty = document.getElementById('in-accordex-poshelper-field-weighing-machine').value
          qty = Number.parseFloat(qty);
          if(qty > 0){
            $(this).val(qty)
            $(this).trigger(

                  $.Event( 'keydown', { keyCode: 13, which: 13 , notPop:true} )

               );
          }

       }
       
    })

	$('#ItemListBody').on('keyup','.prod_rate',function(e){

		switch (e.keyCode) {

			case 13:

			// e.preventDefault();

				// $(this).closest('tr').children(':nth-child(7)').children(':nth-child(1)').focus();

			break;

		}

	})

	$('#ItemListBody').on('input','.prod_code',function(e){

				let code = $(this).val();
				server.getProductByCode(code,$(this))
        if (code == '') {

          var id = $(this).closest('tr').attr('data-id')
         
          if (id != 0 && id !='' && $('tr[data-id="'+id+'"]').length <= 1) {
            console.log(server.bill_items)
            server.deleteObject(id)
          }
          server.unsetRow($(this));

          $(this).closest('tr').children(':nth-child(2)').children(':first-child').focus();

          $(this).closest('tr').click();

          server.TrackUnsavedBills(server)
        }
	})

  $('#ItemListBody').on('keydown','.prod_code',function(e){
    var _this = $(this);

    switch (e.keyCode) {

      case 13:
	      e.preventDefault()
	      // var lastTime = $('#lasttime').val();
	      // var curTime = new Date().getTime();
	      // if (lastTime && (curTime - lastTime < 150)) {
	      //   $('#lasttime').val( null);
	      //    $(document).trigger(

	      //       $.Event( 'keydown', { keyCode: 68, which: 68 , ctrlKey:true } )

	      //     );
	      // }else{

	      //   $('#lasttime').val( curTime);
	        if ($.active ==0) {
	          _this.trigger(

	            $.Event( 'change', { keyCode: 13, which: 13 } )

	          );
	        }
	        else{
            setTimeout(function(){
           
	            _this.trigger(

	              $.Event( 'change', { keyCode: 13, which: 13 } )

	            );
	          },500)
	        }

	      // }

      break;

      case 38:

	        e.preventDefault();

	        if ( $(this).closest('tr').prev('tr').find('.prod_code').focus()){
             $('#ItemListBody tr').removeClass('selected table-info')

	           $(this).closest('tr').prev('tr').addClass('selected table-info')

	        }

      break;

      case 40:

           e.preventDefault();

	        if ($(this).closest('tr').attr('data-id') &&$(this).closest('tr').attr('data-id') !=0 && $(this).closest('tr').next('tr').find('.prod_code').focus()){
             $('#ItemListBody tr').removeClass('selected table-info')

	           $(this).closest('tr').next('tr').addClass('selected table-info')

	        }


      break;
      case 37:

           e.preventDefault();

	        if ($(this).closest('tr').attr('data-id') && $(this).closest('tr').attr('data-id') !=0){
            
             $(this).closest('tr').find('.prod_qty').focus()

	        }


      break;
      case 39:

           e.preventDefault();

	        if ($(this).closest('tr').attr('data-id') && $(this).closest('tr').attr('data-id') !=0){
            
             $(this).closest('tr').find('.prod_qty').focus()

	        }


      break;
      case 9:
         
         e.preventDefault();
         $('.logout').focus()
      break;
      case 8:
      if ($(this).closest('tr').attr('data-id') && $(this).closest('tr').attr('data-id' ) !=0 && server.bill_items[id]){
          var id = $(this).closest('tr').attr('data-id')
          server.TrackDeletedItems(server.bill_items[id].code,server.bill_items[id].price);
      }
       
      break;
      case 46:
      if ($(this).closest('tr').attr('data-id') && $(this).closest('tr').attr('data-id') !=0 && server.bill_items[id]){
          var id = $(this).closest('tr').attr('data-id')
          server.TrackDeletedItems(server.bill_items[id].code,server.bill_items[id].price);
      }
      break;

    }

  });

  $('#ItemListBody').on('change','.prod_code',function(e){


    if(e.keyCode == 13){
      if ($(this).closest('tr').attr('data-id') == 0) {
          $.confirm({

                    escapeKey :true,

                    backgroundDismiss: true,
 
                    title: 'Item Not Found!',

                    content: 'Please Enter Valid ItemCode/Barcode !',
                    buttons: {
                      ok: {
                          action: function () {
                          }
                      },
                    }

                });
      }
      else{
       $(this).closest('tr').find('.prod_qty').focus();
      }

    }

    else{
        let id = $(this).closest('tr').attr('data-id');

        if(id != 0 && server.items[id]){
           $(this).val(server.items[id].code)
        };
        
        let qty =  $(this).closest('tr').find('.prod_qty').val();

        server.CheckStockDetails($(this) ,$(this).val(),false);

        // server.updateBillItems()

        // server.checkFreeItem(id,qty);

    }

    })

	$('#ItemListBody').on('click','tr',function(e){

       $('#ItemListBody tr').removeClass('selected')

       $('#ItemListBody tr').removeClass('table-info')

       $(this).addClass('selected')

       $(this).addClass('table-info')

	});
   $(document).on('keyup','.cashreceived',function(e){
      switch(e.keyCode){
         case 115:

           $('#pay_type').focus();
        break;
      }
    })
   $(document).on('keydown','#pay_type',function(e){if (e.keyCode == 13){e.preventDefault()}})
    $(document).on('keyup','#pay_type',function(e){
      if (e.keyCode == 115) {
        $('#amount_paid').focus()
      
      }
      else if (e.keyCode == 13) {
        e.preventDefault()
        if ($(this).val() == 'card') {

        $('#amountPaid').trigger('click');
        }
        else{
          $('#amount_paid').focus()
        }

      }

    })


	$(document).keydown(function(e){

      switch (e.keyCode) 

      {

        case 112 : 

          e.preventDefault();

           server.getProductList($('#pageNum').val())

          $('#productListModal').on('shown.bs.modal',function(){

              $('#search').val("")
              $('#search').focus()
          });
           $('#productListModal').modal('show')

        break;

        // case 113 : 

        //   e.preventDefault();

        //   server.shiftClose();

        // break;

        case 114 : 

          e.preventDefault();

          location.href="pos.php";

        break;

        case 115 : 

          e.preventDefault();

         

        break;  

        case 116 : 

          e.preventDefault();

          $(document).trigger(

                      $.Event( 'keydown', { keyCode: 68, which: 68 , ctrlKey:true } )

          );

         

        break;

        case 117 : 

          e.preventDefault();

          $('#c_address').focus();

        break;

        case 118 : 

          e.preventDefault();

          $('#noBags').focus()

        break;

        case 119 : 

          e.preventDefault();

          $('#rateCheckModal').modal()
         

        break;

        case 120 : 

          e.preventDefault();



        break;

        case 121 : 

          e.preventDefault();
          // if (server.pendingenabled) {

          if($.isEmptyObject(server.bill_items)){

                $.confirm({

                    escapeKey :true,

                    backgroundDismiss: true,
 
                    title: 'No Items in List!',

                    content: 'Please Add Items First!',
                    buttons: {
                      ok: {
                          action: function () {
                          }
                      },
                    }

                });

              }

              else{
                 
                  server.saveBill(server,true)
                   $("#billPayModal").on("hidden.bs.modal", function () {
                       $('#ItemListBody tr').each(function(){

                         if (!$(this).attr('data-id') || $(this).attr('data-id') ==0){

                           $(this).children(':nth-child(2)').children(':first-child').focus();

                           $(this).click();

                           return false;

                          }

                       })
                  });
              }
          // }
          // else{
          //    $.confirm({

          //           escapeKey :true,

          //           backgroundDismiss: true,

          //           title: 'Not Allowed!',

          //           content: 'Pending Not Allowed!',
          //           buttons: {
          //             ok: {
          //                 action: function () {
          //                 }
          //             },
          //           }

          //       });
          // }

        break;
        case 113 : 

          e.preventDefault();
          server.getPendingList($('#pendingPageNum').val())

          $('#pendingListModal').on('shown.bs.modal',function(){

              $('#pendingSearch').focus()

              // $('#billDate').pickadate()
           });
           $('#pendingListModal').modal('show')

        break;

        case 123 : 

          e.preventDefault();

           $('#discountFlag').focus()

        break;

        case 16 :

        break;
        case 65:
          if (e.ctrlKey) {
            e.preventDefault()
            $('#ItemListBody tr').each(function(){

               if (!$(this).attr('data-id') || $(this).attr('data-id') ==0){

                 $(this).children(':nth-child(2)').children(':first-child').focus();

                 $(this).click();

                 return false;

                }

             })
          }
        break;

        case 69:
        if (e.ctrlKey) {
          e.preventDefault()
          $('#c_email').focus()
        }
        break;

        case 80:

        if (e.ctrlKey) {
          e.preventDefault()
          $('#c_number').focus()
        }

        break;

        case 82:

        break;

        case 81 :

          if (e.ctrlKey) {

            if ( $('#ItemListBody tr.selected').attr('data-id') && $('#ItemListBody tr.selected').attr('data-id') != 0 ) {

	               if($('#ItemListBody tr.selected').children(':nth-child(7)').children(':first-child').is(':focus')){

                    $('#ItemListBody tr').each(function(){

                      if (!$(this).hasClass('selected') && !$(this).next().hasClass('selected')) {

                        if ( $(this).next().attr('data-id') && $(this).next().attr('data-id') != 0 ) {

                           $(this).next().children(':nth-child(7)').children(':first-child').focus();

                        }

                        else{

                           $(this).children(':nth-child(7)').children(':first-child').focus();

                           $(this).click()

                        }

                      }

                      else{

                        $(this).click()

                        return false;

                      }

                   })

                 }

                 else{

                    $('#ItemListBody tr.selected').children(':nth-child(7)').children(':first-child').focus();

                 }

            }

            else{

                $('#ItemListBody tr').each(function(){

                  if ( $(this).next().attr('data-id') && $(this).next().attr('data-id') != 0 ) {

                   $(this).next().children(':nth-child(7)').children(':first-child').focus();

                   $(this).next().click();

                  }
                  else if($(this).attr('data-id') && $(this).attr('data-id') != 0){
                    $(this).children(':nth-child(7)').children(':first-child').focus();

                   $(this).click();

                  }

               })

            }

          }

        break;

        case 90:

            if (e.ctrlKey) {

            	e.preventDefault();

              if($.isEmptyObject(server.bill_items)){

                $.confirm({

                    escapeKey :true,

                    backgroundDismiss: true,
 
                    title: 'No Items in List!',

                    content: 'Please Add Items First!',
                    buttons: {
                      ok: {
                          action: function () {
                          }
                      },
                    }

                });

              }

              else{
                $('#billPayModal').on('shown.bs.modal',function(){

                    $('.cashreceived').focus()
                });

            	server.paymentMethods();

              }

            }

        break;
        case 68:
            e.preventDefault()
            $(".discountAmt").focus()

        break;

      }

	});

	$('.modal-body').scroll(function() {

      if($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {

      	var name = $('#search').val();

       server.getProductList($('#pageNum').val(),name,true)

      }

    });
  $('.list_pending').scroll(function() {

      if($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {

        var name = $('#pendingSearch').val();

        var date = $('#billDate').val();

       server.getPendingList($('#pendingPageNum').val(),name,date,true)

      }

    });

    $(document).on('keyup','#pendingSearch',function(){

    	var name = $(this).val();

      var date = $('#billDate').val();

       server.getPendingList($('#pendingSearchPageNum').val(),name,date);

    })
     $(document).on('change','#billDate',function(){

      var name = $('#pendingSearch').val();

      var date = $(this).val();

      server.getPendingList($('#pendingSearchPageNum').val(),name,date);

    })
    $(document).on('keyup','#search',function(){

      var name = $(this).val();

       server.getProductList($('#searchPageNum').val(),name);

    })

    $(document).on('keyup','.rateCheck_prod_code',function(){

    	var name = $(this).val();

    	server.rateCheck(name)

    });

    $(document).on('keyup','.rateCheck_prod_qty',function(){

    	var name = $(this).val();

    	var id = $('#rateCheckProd').val();

    	server.rateCheckQuantity(id,name)

    });
    
   

    $('#discountFlag').on('keyup',function(){

    	switch($('#discountFlag').val()){

    		case 'y':
            server.discountFlag = 1;

    		    server.getCounterDiscount();

    		break;

    		case 'n':
                 server.discountFlag = 0;

                 server.removeDiscoount();

    		break;

        default:

          $('#discountFlag').val('')

        break;

    	}

    })

    $(document ).on('focusout','.thisSalePrice',function(){

    	// server.setStockPrice($(this).val(), $(this),$('.item_id').val())

    })
    $(document).on('keyup','.thisSalePrice',function(e){
      e.preventDefault();
      switch(e.keyCode){

        case 13:

            // server.setStockPrice($(this).val(), $('#ItemListBody tr.selected input.prod_code'),$('.item_id').val())

        break;
      }
    })
    $('.pending').click(function(){

      $(document).trigger(

              $.Event( 'keydown', { keyCode: 121, which: 121 } )

            );

    })
    $('.showPending').click(function(){

      $(document).trigger(

              $.Event( 'keydown', { keyCode: 113, which: 113 } )

            );

    })
 

    $(document).on('keyup','#c_number',function(){

    	server.phone = $(this).val()

    })

    $(document).on('keyup','#c_email',function(){

    	server.email = $(this).val()

    })

    $(document).on('keyup','#c_address',function(){

       server.partyname =$(this).val() 

    })

    $(document).on('keyup','#noBags',function(){

    	server.bags = $(this).val()

    })
    
    $(document).on('keyup','.cashreceived',function(e){
     
      switch(e.keyCode){
        case 13:
           $('#amountPaid').trigger('click');
        break;
       
        default:
      
                server.cashreceived = $(this).val() ? parseFloat($(this).val()) : 0

                server.calcBalance(server.cashreceived)
          	
        break;
      }


    })

    $(document).on('keyup','.roundof',function(){

    	server.roundof =  $(this).val() ? parseFloat($(this).val()): 0 

    	server.calcNetTotal(server.roundof)

    })
    $(document).on('keyup','.discountAmt',function(){
      server.disc = $(this).val() ? parseFloat($(this).val()): 0 

      server.calcGrandTotal()
    })

    $(document).on('click','#amountPaid',function(){

    	if (server.balance >= 0) {
           if ($('#paid').val()=='yes') {
             $.confirm({

                    escapeKey :true,

                    backgroundDismiss: true,


                title: 'Paid!',

                content: 'Paid Already!',
                buttons: {
                      ok: {
                          action: function () {
                          }
                      },
                    }

            });
           }
           else{
            server.saveBill(server,false)
           }

    	}

    	else{

    		$.confirm({

                escapeKey :'OKAY',

                title: 'Short on Cash!',

                content: 'Please Provide Enough Cash',
                buttons: {
                      OKAY: {
                          action: function () {
                            $(document).trigger(

                                        $.Event( 'keydown', { keyCode: 68, which: 68 , ctrlKey:true } )

                            );
                          }
                      },
                    }

            });

    	}

    })

    $(document).on('change','#pay_type',function(){

    	server.paytype= $(this).val()

      if (server.paytype == 'card') {
        $('#amount_paid').val(server.netTotal)
        $('#amount_paid').trigger('keyup');
      }

    })
    
    $(document).on('focus','.prod_code',function() {
       $(this).select();
    });
    $(document).on('focus','.prod_qty',function() {
       $(this).select();
    });

    
     $( "#rateCheckModal" ).on('shown.bs.modal', function(){
             $('input.rateCheck_prod_code').focus()
      });
    $("#listFreeItemsModal").on("hidden.bs.modal", function () {

         $('#ItemListBody tr').each(function(){

           if (!$(this).attr('data-id') || $(this).attr('data-id') ==0){

             $(this).children(':nth-child(2)').children(':first-child').focus();

             $(this).click();

             return false;

            }

         })
    });
    $("#rateCheckModal").on("hidden.bs.modal", function () {
         $('#ItemListBody tr').each(function(){

           if (!$(this).attr('data-id') || $(this).attr('data-id') ==0){

             $(this).children(':nth-child(2)').children(':first-child').focus();

             $(this).click();

             return false;

            }

         })
    });
	$("#productListModal").keyup(function(e) {
      if(e.keyCode == 27){
         $('#ItemListBody tr').each(function(){

           if (!$(this).attr('data-id') || $(this).attr('data-id') ==0){

             $(this).children(':nth-child(2)').children(':first-child').focus();

             $(this).click();

             return false;

            }

         })
       }
    });
    $('#billPayModal').keyup(function(e){
      if(e.keyCode == 27){
        $('#ItemListBody tr').each(function(){

           if (!$(this).attr('data-id') || $(this).attr('data-id') ==0){

             $(this).children(':nth-child(2)').children(':first-child').focus();

             $(this).click();

             return false;

            }

         })
      }
})
    $(document).on('keyup','#listFreeItemsModal',function(e){
      if (e.keyCode == 13) {
        $('#listFreeItemsModal').modal('hide')
      
      }
    })
    $(document).on('keyup','#rateCheckModal',function(e){
      if (e.keyCode == 13) {
        $('#listFreeItemsModal').modal('hide')
      
      }
    })

    $(document).on('keyup','.loadBill',function(e){
      switch(e.keyCode){
        case 13:
        var posid = $(this).attr('data-posid');
          
        server.getPosDetails(posid)

        $('#pendingListModal').modal('hide')
        break;
        
      }


    })

    $('.logout').focusout(function(){
    	$(this).children(':nth-child(1)').css({'background':'#07645e','box-shadow': '0px 0px 0px'})
    })
    $('.logout').focusin(function(){
    	$(this).children(':nth-child(1)').css({'background':'#ec0a0a','box-shadow': '0px 5px 0px #D56363'})
    })
    $('#billDate').focusin(function(){
      $(this).addClass('picker__input--target')
      $('#billDate_root').addClass('picker--focused')
    })
    $(document).on('dblclick','.searchProduct',function(e){
         var _this = $(this)
       $('#ItemListBody tr').each(function(i,v){
          if (!$(v).attr('data-id') || $(v).attr('data-id') ==0){
            $('#productListModal').modal('hide')
           var tr = $(v).children(':nth-child(2)').children('.prod_code');
           console.log(tr)
           tr.focus();
           tr.val(_this.attr('data-code'));
           // tr.input();
           if ($.active ==0) {
            tr.trigger(

              $.Event( 'input', {} )

            );
          }
          else{
            setTimeout(function(){
           
             tr.trigger(

              $.Event( 'input', {} )

            );
            },500)
          }
           return false;
          }
        })
    })
    $(document).on('keyup','.searchProduct',function(e){
      var _this = $(this)
      switch (e.keyCode) {
      case 13:
        $('#ItemListBody tr').each(function(i,v){
          if (!$(v).attr('data-id') || $(v).attr('data-id') ==0){
            $('#productListModal').modal('hide')
           var tr = $(v).children(':nth-child(2)').children('.prod_code');
           tr.focus();
           tr.val(_this.attr('data-code'));
           // tr.input();
           if ($.active ==0) {
            tr.trigger(

              $.Event( 'input', {} )

            );
          }
          else{
            setTimeout(function(){
           
             tr.trigger(

              $.Event( 'input', {} )

            );
            },500)
          }
           return false;
          }
        })
      break;
      case 38:
       _this.prev('tr').focus();
      break;
      case 40:
       _this.next('tr').focus();
      break;
      }
    })
    

    function startTime() {

        var today = new Date();

        var h = today.getHours();

        var ampm = h >= 12 ? 'PM' : 'AM';

        h = h % 12;

        h = h ? h : 12

        var m = today.getMinutes();

        var s = today.getSeconds();

        var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

        h = checkTime(h);

        m = checkTime(m);

        s = checkTime(s);

        document.getElementById('form_datetime').innerHTML =

        date + " " + h + ":" + m + ":" + s + ' ' +ampm ;

        var t = setTimeout(startTime, 500);

    }

    function checkTime(i) {

      if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10

        return i;

    }

    $('.printThis').click(function(e){
       $(document).trigger(

              $.Event( 'keydown', { keyCode: 116, which: 116 } )

            );
    })

    
    var key = server.getCookie('counterKey')
    if (key) {
      server.counterno(key);
      
    }
    else{
      server.getCounterDetails()
    }
    
    $(document).on('click','.setMyCounter',function(){
      let id = $(this).attr('data-counterid');
      let key = $(this).attr('data-counterkey');
      server.EnableCounter(id,key)
      
    })
     $(document).on('click','#loadReturnBill',function() {
      server.loadReturnBill();
    })
    $(document).on('click','.returnButton',function(){
      server.processReturn()
    })
    $(document).on('keyup','.ret_itemqty',function(){
      server.calculateAmountToRepay($(this))
    }) 
    
    startTime()

});