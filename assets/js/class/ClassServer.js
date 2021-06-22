class ServerFunctions extends DomFunctions{

	constructor(){

		super()

	}

	getBillNo(){

		var self = this;

		this.url = 'DataResponse.php?req=billNo';

		$.get(this.url, {counter: self.counter}, function (data) {

            self.setBillNo(data);

        },'json');

	}

	addProduct(details,element,id){

       super.addProduct(details,element,id);

	}

	getProductByCode(code,element){

		var self = this;

		this.url = 'DataResponse.php?req=product';

		$.get(this.url, {code: code}, function (data) {

            var details = data['details'];

            	var id = 0;

            if (data['success']) {

                id = details.id;

                details.disc_price =  details.price;

                if (details.stockdetails) {

	                	details.price = details.stockdetails[0].saleprice;

                    details.disc_price =  details.price;

	                    self.addProduct(details,element,id);

                }

                else{

                 self.addProduct(details,element,id);

                }

            }

            else{

                details.name ='';

                details.price = '00.00';

                details.disc_price = '00.00';

                details.unit ='-' ;

                details.qty ='0' ;

                details.tax ='0' ;

                self.items[0] = details;

                self.addProduct(details,element,id);

            }

        },'json');

	}

	getProductList(num,name=null,flag=false){

		var self = this;

		this.url = 'DataResponse.php?req=productList';

		$.get(this.url, {pagenum: num,name:name}, function (data) {
            if (data['success']){

                   var template_list= Template7.compile($('#Template-Product-List').html());

                   var html = template_list({items:data['details']});

                   if (num == 1 ) {

                   $('#ProductListBody').html(html)

                   }else{

                   $('#ProductListBody').append(html)

                   }

                   $('#pageNum').val(parseInt(num)+1)

            }

            else{

            	if (!flag) {

             	 $('#ProductListBody').html(`<tr ><td colspan="4">No Results Found</td></tr>`)

            	}

            }

		},'json')

	}
  getPendingList(num,name=null,date=null,flag=false){

    var self = this;

    this.url = 'DataResponse.php?req=pendingList';

    $.get(this.url, {pagenum: num,name:name,date:date,counterno : self.counter}, function (data) {

            if (data['success']){

                   var template_list= Template7.compile($('#Template-Pending-List').html());

                   var html = template_list({items:data['details']});

                   if (num == 1 ) {

                    $('#pendingListBody').html(html)

                   }else{

                    $('#pendingListBody').append(html)

                   }
                  $.each( data['details'],function(i,v){
                     self.pendingPos[v.posid] = v;
                   })
                   
                   $('#pendingPageNum').val(parseInt(num)+1)

            }

            else{

              if (!flag) {

               $('#pendingListBody').html(`<tr ><td colspan="4">No Results Found</td></tr>`)

              }

            }

    },'json')

  }
  getPosDetails(posid){

     var self = this;

    this.url = 'DataResponse.php?req=posDetail';

    $.get(this.url, {posid:posid}, function (data) {

      if (data['success']) {

        self.initializePendingBill(posid,data['details'])
      
      }
    },'json')
  }

  getUnsavedBill(){
    var self = this;

    this.url = 'DataResponse.php?req=Unsavedbill';

    $.get(this.url, {counterno : self.counter}, function (data) {

            if (data['success']){
                  
                self.unsavedPos[data['bill'].posid] = data['bill'];

                self.initializePendingBill(data['bill'].posid,data['details'],'unsaved')
            }

            else{
               
               

            }

    },'json')
  }


	rateCheck(code){

		var self = this;

		this.url = 'DataResponse.php?req=product';

		$.get(this.url, {code: code}, function (data) {

            var details = data['details'];

            	var id = 0;

            if (data['success']) {

                id = details.id;

                $('#rateCheckProd').val(id)

                self.rateCheckitems[id] = details

                self.rateCheckitems[id].qty = 1

                self.rateCheckTax(id)

            }

            else{

                details.name ='';

                details.price = '00.00';

                details.unit ='-' ;

                details.qty =' ' ;
                  $('.rateCheck_prod_name').val('')
                  $('.rateCheck_prod_rate').val('')
                  $('.rateCheck_prod_tax').val('')
                  $('.rateCheck_prod_qty').val('')
                  $('.rateCheck_prod_amount').val('')
                

            }

        },'json');

	}

	rateCheckTax(id,gst_type="inclusive"){

       var self = this;

      let taxableamt = 0;

      let taxamt;

      let taxrate = self.rateCheckitems[id].tax;

      if (id != 0) {



	      if (gst_type == 'inclusive') {

	       taxableamt = (parseFloat(self.rateCheckitems[id].price) * 100 / (100 + parseFloat(taxrate)));

	      }

	      else{

	       taxableamt =  self.rateCheckitems[id].price;

	      }

	      taxamt =  parseFloat(taxableamt) * (parseFloat(taxrate)/100)

	      let price = parseFloat(taxableamt)+parseFloat(taxamt);

	      self.rateCheckitems[id].taxableamt = taxableamt;

	      self.rateCheckitems[id].taxamt = taxamt;

	      self.rateCheckitems[id].price = price;

      }

      else{

      	  taxamt =  0;

	      self.rateCheckitems[id].taxableamt = 0;

	      self.rateCheckitems[id].taxamt = 0;

	      self.rateCheckitems[id].price = 0;

	      self.rateCheckitems[id].tax = 0;

      }

      $('.rateCheck_prod_name').val(self.rateCheckitems[id].name)

      $('.rateCheck_prod_rate').val(parseFloat(self.rateCheckitems[id].taxableamt).toFixed(2))

      $('.rateCheck_prod_tax').val(self.rateCheckitems[id].taxamt.toFixed(2))

      $('.rateCheck_prod_qty').val(self.rateCheckitems[id].qty)

      $('.rateCheck_prod_amount').val(parseFloat(parseFloat(self.rateCheckitems[id].price) * parseFloat(self.rateCheckitems[id].qty)).toFixed(2))

	}

	rateCheckQuantity(id,qty){

		var self = this;

		self.rateCheckitems[id].qty =qty

		$('.rateCheck_prod_qty').val(self.rateCheckitems[id].qty)

      $('.rateCheck_prod_amount').val(parseFloat(parseFloat(self.rateCheckitems[id].price) * parseFloat(self.rateCheckitems[id].qty)).toFixed(2))

	}

	getCounterDiscount(){

        var self = this;

		this.url = 'DataResponse.php?req=discount';

		$.get(this.url, {counter: self.counter}, function (data) {

            var details = data['details'];

            	var id = 0;

            if (data['success']) {

            	self.discperc = details.discountperc;

               self.calcDiscount(details.discountperc)

            }

            else{

               

                

            }

        },'json');

	}

	getFreeItems(code,id){

      var self = this;

		this.url = 'DataResponse.php?req=freeItem';

		$.get(this.url, {code: code}, function (data) {

            var details = data['details'];

            self.items[id].freeItems = details;

        },'json');

	}

	getFreeItemByCode(code){

		var self = this;

		var freedata;

		this.url = 'DataResponse.php?req=product';

		return $.get(this.url, {code: code},'json');

	}

	printDiv(divName,flag = false,data = false) {
  var self = this;
    if (flag) {
          $(`#${divName}`).print({
            stylesheet : 'assets/css/style-shift.css',
          	deferred: $.Deferred().done(function() { 

                $('#tab-shift-close').css('display','none');
                self.InsertShiftClose(data)

          		location.href='logout.php'; 
          	})
          });
      
    }
    else{
       $(`#${divName}`).print({
            deferred: $.Deferred().done(function() { 

                $('#tab-bill').css('display','none');

              location.reload(); 
            })
          });
    }

           
         
    }

	saveBill(data,flag){
    var self = this
    var content = flag ? 'Added To Pending List':'Paid Successfully!';
		this.url = `DataResponse.php?req=saveBill`;
    delete data.pendingPos;
    delete data.items;
        $.post(this.url,{data:data,flagg:flag},function(data){
        
          if (data['success'] == 'true') {

           $('#paid').val('yes');
             var qty = 0;
             var kg = 0;
             var no = 0;
             var serial = 1;
          	$.each(self.bill_items,function(i,v){
              var bqty = v.qty;
              v.qty = parseFloat(v.qty).toFixed(3)
               qty = qty + bqty;
               if (v.unit == 'Kgs') {
                kg = kg + bqty
               }
               else{
                no = no + bqty
               }
               v.serial = serial;
               serial++
            })
            var balance = self.balance == -1 ? 0 : self.balance;

	          var template_item_bill = Template7.compile($('#template-item-bill').html());

						var item_bill = template_item_bill({items:self.bill_items,

          	                                  grand:self.netTotal,

          	                                  subtotal:self.grandTotal,

          	                                  gst:self.gst,

                                              amountpaid:self.cashreceived,

                                              bill:self.billno,

                                              balance:balance,

                                              counter:self.counter,

                                              bags:self.bags,

                                              type:self.paytype,

                                              round:self.roundof,

                                              operator:self.operator,

                                              qty:parseFloat(qty).toFixed(3),

                                              KG:parseFloat(kg).toFixed(3),

                                              NOS:parseFloat(no).toFixed(3),

                                              time : self.getDateTime()


          	                                });

						$('#tab-bill').html(item_bill);

            if (!flag) {
	             $('#tab-bill').css('display','block');
	              self.printDiv('tab-bill')
            }
            else{
            	location.reload()
            }
            

          }

          else{

          	$.confirm({

                escapeKey :true,

      			    title: 'Failure!',

      			    content: 'Something Went Wrong!',

      			});

          }

        },'json')

	}
  TrackUnsavedBills(data){
   var self = this;
    
    self.url = `DataResponse.php?req=TrackUnsavedBill`;
    // delete data.pendingPos;
    // delete data.items;
    var newdata = Object.create(data);
    delete newdata.pendingPos;
    delete newdata.items;
        $.post(self.url,{data:newdata},function(data){
          if (data['UnsavedPos']) {
            self.unsavedbill = data['UnsavedPos']
          }

        },'json')
  }
  TrackDeletedItems(code,rate){
    var self = this;
    
    self.url = `DataResponse.php?req=TrackDeletedItems`;
    
        $.post(self.url,{code:code,rate:rate,bill:self.unsavedbill},function(data){

        },'json')
  }
  getCounterDetails(){
  	var self = this;
    
    self.url = `DataResponse.php?req=counterDetails`;
   
        $.post(self.url,{data:'counter'},function(data){
          if (data['success']) {
          	var template_counter = Template7.compile($('#Template-Counter-List').html());
            // console.log(data['counters'])
          	var counter_content  = template_counter({counters:data['counters']});
          	$.confirm({
              escapeKey :true,
                  title: 'Select Your Counter!',
                  content: counter_content,
                  buttons: {
                        cancel:{
                        	action:function(){
                        		location.href = 'logout.php';
                        	}
                        }
			      }
                 
            });
          }
          else{
          	$.confirm({
                  escapeKey :'cancel',
                  backgroundDismiss:true,
                  title: 'No more Counters Available!',
                  content: '<h8>'+data['msg']+'</h8>',
                  buttons: {
                        cancel:{
                        	action:function(){
                        		location.href =  'logout.php';
                        	}
                        }
			      }
                 
            });
          }

        },'json')
  }
  EnableCounter(id,key){
  	var self = this;
    
    self.url = `DataResponse.php?req=enablecounter`;
   
        $.post(self.url,{id:id},function(data){
          if (data['success']) {
            self.setCookie('counterKey',key,10)
          	
            	location.reload();
                
          }
          else{
          	$.confirm({
                  escapeKey :true,
                  title: 'Failed!',
                  content: data['msg'],
                  buttons: {
                        cancel:{
                        	action:function(){
                        		location.reload();
                        	}
                        }
			      }
                 
            });
          }

        },'json')
  }
  counterno(key){
  	var self = this;
    
    self.url = `DataResponse.php?req=getcounterno`;
   
        $.post(self.url,{key:key},function(data){
          if (data['success']) {

          	self.counter = parseInt(data['counter'].counterid)

            self.discountenabled = parseInt(data['counter'].isdiscount);

            self.editenabled = parseInt(data['counter'].isitemedit);

            self.scaleenabled = parseInt(data['counter'].isweighingscale);

            self.pendingenabled = parseInt(data['counter'].ispending);
      
            self.cardenabled = parseInt(data['counter'].iscard);

            self.maxbags = parseInt(data['counter'].bags);

            self.qty = parseInt(data['counter'].qty);

          	$('#CounterNo').html(self.counter)


          	  self.getBillNo();

              self.getUnsavedBill();

              self.initializeCounterSettings();

          }
          else{
          	$.confirm({
              escapeKey :true,
                  title: 'Sorry!',
                  content: '<h8>'+data['msg']+'</h8>',
                  buttons: {
                        cancel:{
                        	action:function(){
                        		location.href =  'logout.php';
                        	}
                        }
			      }
                 
            });
          }

        },'json')

  }
  shiftClose(){
     var self = this;
    
    self.url = `DataResponse.php?req=counterShiftClose`;
    if (!self.bill_items.length) {
      $.post(self.url,{counterno:self.counter,operator:self.operator},function(data){



                              if (data['details'].length) {
                                $.confirm({
                                    escapeKey :'cancel',
                                    confirmButton:'okay',
                                    backgroundDismiss: true,
                                        title: 'Confirmation!',
                                        content: '<h8>Are You Sure To Close Your Session</h8>',
                                        buttons: {
                                              okay:{
                                                action:function(){
                                                    var template_shift_close = Template7.compile($('#Template-Shift-Close').html());

                                                    data['details'][0]['time'] = self.getTime()
                                                    data['details'][0]['date'] = self.getDate()
                                                    var shift_close = template_shift_close({shiftClose:data['details']});

                                                    $('#tab-shift-close').html(shift_close);

                                                    $('#tab-shift-close').css('display','block');

                                                    self.printDiv('tab-shift-close',true,data['details'][0])
                                                },
                                                keys: ['enter'],
                                              },
                                              cancel:{
                                                action:function(){
                                                  
                                                },
                                                keys: ['esc'],
                                              }
                                  }
                                       
                                  });

                              
                              }
                              else{
                                $.confirm({
                                    escapeKey :'okay',
                                    backgroundDismiss: true,
                                        title: 'Sorry!',
                                        content: '<h8>No More Transaction To Close</h8>',
                                        buttons: {
                                              okay:{
                                                action:function(){
                                                  $('#ItemListBody tr').each(function(){

                                                     if (!$(this).attr('data-id') || $(this).attr('data-id') ==0){

                                                       $(this).children(':nth-child(2)').children(':first-child').focus();

                                                       $(this).click();

                                                       return false;

                                                      }

                                                   })
                                                }
                                              }
                                  }
                                       
                                  });
                              }


                            },'json')
      
       
    }
    else{
      $.confirm({
              escapeKey :true,
              backgroundDismiss: true,
                  title: 'Sorry!',
                  content: '<h8>Please Proceed the Bill First</h8>',
                  buttons: {
                        okay:{
                          action:function(){
                            
                          }
                        }
            }
                 
            });
    }
   
        

  }
  InsertShiftClose(data){
      var self = this;
    
    self.url = `DataResponse.php?req=addShiftClose`;
    $.post(self.url,{data:data,branch:self.branchid},function(data){
       if (data['success'] == true) {
        location.href = 'logout.php'
       }
    },'json')
  }

}