class DomFunctions {

    constructor(){

      this.posid = 0;

      this.billno = 1;

      this.branchid = 4;

      this.counter;

      this.get_type = 4;

      this.partyname ='';

      this.phone ='';

      this.email ='';

      this.bill_items = {};

      this.subTotal = 0;

      this.grandTotal = 0;

      this.roundof = 0;

      this.netTotal = 0;

      this.gst = 0;

      this.cgst = 0;

      this.sgst = 0;

      this.disc = 0;

      this.others = 0;

      this.discperc =0;

      this.discountFlag = 0;

      this.freeitems = {};

      this.paytype ='cash';

      this.cashreceived =0;

      this.balance =-1;

      this.bags =0;

      this.maxbags =0;

      this.items = {};

      this.rateCheckitems = {};

      this.lastbillamt = 0.00;

      this.data_id = [];

      this.operator = '';

      this.pendingPos = {};

      this.unsavedPos = {};

      this.pending = 2;

      this.posDetails = {};

      this.unsavedbill = 0;

      this.discountenabled = 0;

      this.editenabled = 0;

      this.scaleenabled = 0;

      this.pendingenabled = 0;

      this.cardenabled = 0;

      this.qty = 1;

  	}
    initializePendingBill(posid,posDetails,type='pending'){
      
      var self = this;

      self.unsetAllRow()
      
      if (type =='pending') {

        self.posid = parseInt(posid)

        var bill = self.pendingPos[posid];

        self.billno = parseInt( bill.BillNo );

      }
      else{

        self.unsavedbill = parseInt(posid);

        var bill = self.unsavedPos[posid];

      	self.posid = parseInt(bill.postblid);

      }



      self.branchid = parseInt(bill.BranchId);

      self.counter = parseInt(bill.counterno);

      self.get_type = 4;

      self.partyname =bill.Partyname;

      self.phone =bill.PartyAddress;

      self.email =bill.PartyEmail;

      self.bill_items = {};

      self.subTotal = parseFloat(bill.Total);

      self.grandTotal = parseFloat(bill.GrandTotal);

      self.roundof = parseFloat(bill.RoundOff);

      self.netTotal = parseFloat(bill.BillAmount);

      self.gst = parseFloat(bill.CGST) + parseFloat(bill.SGST);

      self.cgst = parseFloat(bill.CGST);

      self.sgst = parseFloat(bill.SGST);

      self.disc = parseFloat(bill.TotalDiscount);

      self.others = 0;

      self.discperc =parseFloat(bill.discountperc);

      self.discountFlag = parseInt(bill.isdiscountapplied)

      self.freeitems = {};

      self.paytype ='cash';

      self.cashreceived =parseFloat(bill.paidamount);

      self.balance =parseFloat(bill.changeamount);

      self.bags =parseInt(bill.noofbags);

      self.items = {};

      self.data_id = [];

      self.loadPendingBillItems(posDetails)
      
    }
    loadPendingBillItems(posDetails){
      var self = this;
      $.each(posDetails,function(i,v){
        let item = {};
        if(v.freeqty == null){
          item.availableFreeItems =[] ;
          item.freeItems = v.freeItems;
          item.cgst = parseFloat(v.cgsttax);
          item.code = v.ItemCode;
          item.disc_price = v.DiscAmt ? parseFloat(v.DiscAmt) : 0;
          item.id =  v.id;
          item.name = v.ItemName;
          item.negAllow = v.negAllow;
          item.price =  parseFloat(v.MRP);
          item.qty =  parseFloat(v.Qty);
          item.ratewise = v.ratewise;
          item.sgst =  parseFloat(v.sgsttax);
          item.tax =  v.taxrate;
          item.counts = v.counts;
          item.taxableamt =  parseFloat(v.taxableamt);
          item.taxamt =  parseFloat(v.cgsttax) + parseFloat(v.sgsttax);
          item.taxcode = v.taxcode;
          item.total =  parseFloat(v.Amount);
          item.unit = v.Unit;
          item.stockdetails = v.stockdetails;
          self.items[item.id] = item
          self.bill_items[item.id] = self.items[item.id]
        }
        else{
          var availableFreeItems = [];
          // self.items[item.id].availableFreeItems = []
          availableFreeItems['freeitemcode'] = v.ItemCode
          availableFreeItems['freename'] = v.ItemName
          availableFreeItems['freeqty'] = parseFloat(v.Qty)
          availableFreeItems['unit'] = v.Unit
          // self.freeItems[]=availableFreeItems
        }
        if (v.freeItems && v.freeItems.length>=1) {
         self.checkFreeItem(item.id,parseFloat(v.Qty))
        }
      })
      self.bindDataToBill();
    }
    bindDataToBill(){
      var self = this;
      $('.billno').val(self.billno)
      $('#c_number').val(self.phone)
      $('#c_email').val(self.email)
      $('#c_address').val(self.partyname)
      $('#discountFlag').val(self.discountFlag ? 'Y':'N')
      $('#posid').val(self.posid)
      $('#noBags').val(self.bags)
      if (Object.keys(self.bill_items).length > $('#ItemListBody tr').length) {
        var l = $('#ItemListBody tr').length;
        for (var i = 0 ; i <= Object.keys(self.bill_items).length - l+1 ; i++) {
          let s_no = parseInt($('#ItemListBody tr:last-child').children(':nth-child(1)').html())
          self.appendRow(s_no,'#ItemListBody');
        }
        self.bindDataToBill()

      }
      else{
        $.each(self.bill_items,function(i,v){

          $('#ItemListBody tr').each(function(n,m){

            if (!$(m).closest('tr').attr('data-id')) {

              $(m).closest('tr').attr('data-id',v.id)

              $(m).closest('tr').children(':nth-child(2)').children(':nth-child(1)').val(v.code)

              $(m).closest('tr').children(':nth-child(3)').html(v.name)

              $(m).closest('tr').children(':nth-child(4)').html(v.unit)

              $(m).closest('tr').children(':nth-child(5)').html(parseFloat(v.disc_price).toFixed(2))

              $(m).closest('tr').children(':nth-child(6)').html(parseFloat(v.taxamt).toFixed(2))

              $(m).closest('tr').children(':nth-child(7)').children(':nth-child(1)').val(v.qty)

              $(m).closest('tr').children(':nth-child(8)').html(parseFloat(v.total).toFixed(2))
              return false;
            }
          })
        })

          self.calcSubTotal()

          self.calcGrandTotal()

          self.focusLastElement()

      }
    }
    focusLastElement(){
       $('#ItemListBody tr').each(function(n,m){
        if (!$(m).attr('data-id')) {
          $(m).addClass('selected table-info')
          $(m).closest('tr').children(':nth-child(2)').children(':nth-child(1)').focus()
          return false;
        }
       })
    }
    appendRow (s_no,element) {

        let html = `<tr>

                      <td class="td-7" align="center">${++s_no}</td>

                      <td class="td-9"><input type="text" name="productCode" class="prod_code"></td>

                      <td class="td-33"></td>

                      <td>-</td>

                      <td align="center" class="prod_rate">00.00</td>

                      <td align="center">00.00</td>

                      <td align="center"><input type="number" name="productQty" class="prod_qty" min="0"></td>

                      <td align="right">00.00</td>

                    </tr>`;

        $(element).append(html);

    }

    unsetAllRow(){

       $('#ItemListBody tr').closest('tr').attr('data-id','')

    	 $('#ItemListBody tr').closest('tr').children(':nth-child(2)').children(':nth-child(1)').val('')

    	 $('#ItemListBody tr').closest('tr').children(':nth-child(3)').html('')

    	 $('#ItemListBody tr').closest('tr').children(':nth-child(4)').html('-')

       $('#ItemListBody tr').closest('tr').children(':nth-child(5)').html('00.00')

    	 $('#ItemListBody tr').closest('tr').children(':nth-child(6)').html('00.00')

    	 $('#ItemListBody tr').closest('tr').children(':nth-child(7)').children(':nth-child(1)').val('')

       $('#ItemListBody tr').closest('tr').children(':nth-child(8)').html('00.00')



    }
    unsetRow(element){
      var closeTR = element.closest('tr');

      closeTR.attr('data-id','')

      closeTR.children(':nth-child(2)').children(':nth-child(1)').val('')

      closeTR.children(':nth-child(3)').html('')

      closeTR.children(':nth-child(4)').html('-')

      closeTR.children(':nth-child(5)').html('00.00')

      closeTR.children(':nth-child(6)').html('00.00')

      closeTR.children(':nth-child(7)').children(':nth-child(1)').val('')

      closeTR.children(':nth-child(8)').html('00.00')
      closeTR = element.closest('tr').html();
      element.closest('tr').remove();
      $('#ItemListBody').append('<tr>'+closeTR+'</tr>')
      var check = 0;
      $('#ItemListBody tr').each(function(i,v){

        $(v).children(':nth-child(1)').html(i+1)

        if (check  == 0 && (!$(v).attr('data-id') || $(v).attr('data-id') ==0)){

         $(v).children(':nth-child(2)').children(':first-child').focus();

         $(v).click();

         check = 1;

        }

      })

    }

    mathRound(value,places){

             return Math.round(value *100) /100;

    }
    capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }

    addProduct(product,element,id){

    	let self = this;

    	if (!self.items[id]) {

        product.availableFreeItems = [];

        self.items[id] = product;

      }

    	var qty = self.items[id].qty ? self.items[id].qty : self.qty;

      self.items[id].qty = qty;

    	self.items[id].name = self.capitalizeFirstLetter(self.items[id].name);

        self.calcTaxForProduct(id,element);

        var price = self.items[id].disc_price

    	element.closest('tr').attr('data-id',id)

    	element.closest('tr').children(':nth-child(3)').html(product.name)

    	element.closest('tr').children(':nth-child(4)').html(product.unit)

    	element.closest('tr').children(':nth-child(7)').children(':nth-child(1)').val('')

        self.calcProductTotal(self.qty,price,element,id)

        // this.calcSubTotal()

    }

    CheckStockDetails(element,code,trigger=true){
       
        let self = this;

       var id  = element.closest('tr').attr('data-id')

       var check = self.bill_items.hasOwnProperty(id)
        

          if (id && id != 0) {

              self.getFreeItems(code,id);

	          if (self.items[id].stockdetails && self.items[id].stockdetails.length>1) {

	            self.ListSalePrice(self.items[id].stockdetails,element,id);

	            // self.bill_items[id] = self.items[id];

	             self.items[id].counts = self.items[id].counts ? self.items[id].counts+1 : 1;

	            // self.calcDiscount(self.discperc)

	          }

	        }
	        else{

	        	self.updateBillItems()

	        	self.calcSubTotal()

	            self.calcGrandTotal()

	            self.calcDiscount(self.discperc)

	        	self.TrackUnsavedBills(self)
	        }

    }

    UpdateExistingQuantity(element,code,qty){

       let self = this;

       var id  = element.closest('tr').attr('data-id')

       var check = self.bill_items.hasOwnProperty(id)

       if(check){	       	
                    if($('tr[data-id="'+id+'"]').length > 1){

			       	   self.unsetRow(element);

			           var qty = parseFloat(self.bill_items[id].qty)+ parseFloat(qty);

                    }
                    else{
			         	var qty =  parseFloat(qty);
                    	
                    }


			       	self.bill_items[id].qty = qty;

			       	self.addQuantity(qty,$('#ItemListBody tr[data-id="'+id+'"]'),id);

			        $('#ItemListBody tr[data-id="'+id+'"]').children(':nth-child(7)').children(':nth-child(1)').focus()

			        $('#ItemListBody tr[data-id="'+id+'"]').click()

			        self.calcDiscount(self.discperc) 	

	    }

       else{
        

          if (id && id != 0) {

          	    self.items[id].qty = parseFloat(qty)

              self.bill_items[id] = self.items[id];

	            self.calcSubTotal()

	            self.calcGrandTotal()

	            self.calcDiscount(self.discperc)

	            self.TrackUnsavedBills(self)

	          }

	          

	        }

       }

    addQuantity(qty,element,id){

    	let self = this;

    	if (id != 0) {

	    	let price = self.items[id].disc_price;

        self.bill_items[id] = self.items[id]

	    	self.items[id].qty = parseFloat(qty);

    	    element.closest('tr').children(':nth-child(7)').children(':nth-child(1)').val(qty)

	        self.calcProductTotal(parseFloat(qty),price,element,id);

       	if(self.bill_items[id].total){

       		self.bill_items[id].total = self.items[id].total;

       	} 

	        self.calcSubTotal()

       	  self.calcDiscount(self.discperc)

       	  self.TrackUnsavedBills(self)

    	}

    }

    calcProductTotal(qty,price,element,id){

    	let self = this;

       var productTotal = qty*parseFloat(self.items[id].disc_price);

    	self.items[id].total = productTotal;

        element.closest('tr').children(':nth-child(8)').html(parseFloat(productTotal).toFixed(2))

    }

    calcSubTotal(){

    	var self = this;

      self.subTotal = 0;

    	var subTotal = parseFloat(0).toFixed(2);

    	self.gst = 0;

    	self.price = 0;

      $.each(self.bill_items,function(i,v){

        self.subTotal += v.taxableamt *parseFloat(v.qty)

        self.gst += v.taxamt *parseFloat(v.qty)

        self.price += parseFloat(v.price) *parseFloat(v.qty)

        subTotal = parseFloat(self.subTotal).toFixed(2)

      	let gst = parseFloat(self.gst).toFixed(2)

        $('.gst').val(`${gst}`);

        self.sgst =gst/2;

        self.cgst =gst/2;

      })
      $('.subTotal').val(`${subTotal}`);

      self.calcGrandTotal();



    }

    calcPayableAmount(){

      var self = this;

      self.payablaAmt = 0;

        self.payablaAmt += self.subTotal+self.gst+self.others

        var payableamt = parseFloat(self.price).toFixed(2)

        let disc_amt = parseFloat(self.disc).toFixed(2)

        $('#payable_amt').val(payableamt)

        $('#discount').val(disc_amt)

        $('#net').val(self.netTotal)

    }

    calcRoundOf(grandTotal){
      
    }

    calcGrandTotal(){

    	var self = this;

    	self.grandTotal = 0;

        self.grandTotal += self.subTotal+self.gst+self.others;

         self.grandTotal = parseFloat(self.grandTotal).toFixed(2)

         self.roundof = 0
         
         self.netTotal= parseFloat(self.grandTotal).toFixed(2)
        var floor = Math.floor(self.grandTotal)
        if (self.grandTotal - floor > 0.5) {
          self.roundof = Math.ceil(self.grandTotal) - self.grandTotal 
          self.netTotal= parseFloat(Math.ceil(self.grandTotal)).toFixed(2)
        }
        else if(self.grandTotal - floor < 0.5){
           self.roundof = Math.floor(self.grandTotal) - self.grandTotal  
          self.netTotal= parseFloat(Math.floor(self.grandTotal)).toFixed(2)

        }
        let grandTotal = parseFloat(self.grandTotal).toFixed(2)

        let disc_amt = parseFloat(self.disc).toFixed(2)

        $('.roundof').val( parseFloat(self.roundof).toFixed(2))

        $('.grandTotal').val(`${grandTotal}`);

        $('.discountAmt').val(`${disc_amt}`)

        self.calcNetTotal(self.roundof);

    }

    calcTaxForProduct(id,element,gst_type='inclusive'){

      var self = this;

      let taxableamt = 0;

      var price = 0;

      let taxamt;

      let taxrate = self.items[id].tax;

      if (id != 0 && taxrate) {

	      if (gst_type == 'inclusive') {

	       taxableamt = (parseFloat(self.items[id].disc_price) * 100 / (100 + parseFloat(taxrate)));

	      }

	      else{

	       taxableamt =  self.items[id].disc_price;

	      }

	      taxamt =  parseFloat(taxableamt) * (parseFloat(taxrate)/100)

	      price = parseFloat(taxableamt)+parseFloat(taxamt);

	      self.items[id].taxableamt = taxableamt;

	      self.items[id].taxamt = taxamt;

          self.items[id].disc_price = price;

          self.items[id].sgst = taxamt/2;

	      self.items[id].cgst = taxamt/2;

      }

      else{

      	  taxamt =  0;

	      self.items[id].taxableamt = 0;

	      self.items[id].taxamt = 0;

	      self.items[id].price = 0;

	      self.items[id].tax = 0;

      }

      if (!self.items[id].stockdetails || (self.items[id].stockdetails && self.items[id].stockdetails.length==1)) {

    	  element.closest('tr').children(':nth-child(5)').html(parseFloat(price).toFixed(2))

      }

    	element.closest('tr').children(':nth-child(6)').html(parseFloat(taxamt).toFixed(2) +' ('+self.items[id].tax+'%)')

    }

    calcDiscount(discperc){

      var self = this;

      self.disc = 0;

     if (self.discountenabled) {

      $.each(this.bill_items,function(i,v){

       v.disc_amt = parseFloat(v.price)*parseFloat(discperc)/100 

       v.disc_perc = parseFloat(discperc) 

       v.disc_price = parseFloat(v.price) - parseFloat(v.disc_amt) 

       var element =$(`#ItemListBody tr[data-id="${v.id}"]`).find('input.prod_qty');

       self.disc += (v.disc_amt * v.qty) 

       self.calcTaxForProduct(v.id,element);

       self.calcProductTotal(v.qty,v.disc_price,element,v.id)

      })

     }
      self.calcSubTotal()

      self.calcGrandTotal()



    }

    calcNetTotal(round){

      var self = this;

        $('.netTotal').val(`${self.netTotal}`);

        $('.billTotal').html(`${self.netTotal}`);



    }

    calcBalance(cash){

      var self = this ;

      self.balance =  parseFloat(cash) - parseFloat(self.netTotal);

      $('.balance').val(parseFloat(self.balance).toFixed(2));

    }

    removeDiscoount(){

      var self = this;

       self.discperc = 0;

       self.calcDiscount(self.discperc)

    }

    ListSalePrice(list,element,id){

      var template_list= Template7.compile($('#Template-Saleprice-List').html());

      var html = template_list({items:list,id:id});

      element.closest('tr').children(':nth-child(5)').html(html)

      element.closest('tr').children(':nth-child(5)').children(':nth-child(1)').focus()

    }

    setStockPrice(price,element,id){

      var self = this;

      var qty;

        if (self.bill_items[id]) {

        	if (price == self.bill_items[id].price) {

		    	element.children(':nth-child(7)').children(':nth-child(1)').focus()

			    self.calcProductTotal(self.qty,price,element,id)

        	}

    		else{

    			var new_id = 'cpy-'+id

				    self.items[new_id] =Object.assign({},self.items[id]);

				    self.items[new_id].id = new_id;

					self.items[new_id].disc_price = price;

				    self.items[new_id].price = price;

				    self.items[new_id].qty = 1;
				    
				    qty = self.items[new_id].qty;

				    element.closest('tr').attr('data-id',new_id)

				    element.closest('tr').find('.prod_qty').focus();

					self.calcProductTotal(qty,price,element,new_id)

    			}

    		}

    	}

    checkAndListPrice(element,id){

      var self = this;

      if (self.bill_items[id].stockdetails && self.bill_items[id].stockdetails.length >1) {

        var list = self.bill_items[id].stockdetails

        self.ListSalePrice(list,element,id);

      }

    }

    checkFreeItem(id,qty){

      var self = this;

      if (self.bill_items[id] && self.bill_items[id].freeItems&&self.bill_items[id].freeItems.length>0) {

        var free = [];

       $.each(self.bill_items[id].freeItems,function(i,v){

          if(v.qty <= qty ){

             let frees = self.getFreeItemByCode(v.freeitemcode)

             frees.then(function(data){

              data  = JSON.parse(data)

              v.freename = data['details'].name

              v.unit = data['details'].unit

             },'json')

             free.push(v);

          }

       })

       self.bill_items[id].availableFreeItems = free;

       self.freeitems[id] = free;

       if (self.bill_items[id].availableFreeItems && self.bill_items[id].availableFreeItems.length >= 1) {

            self.listFreeItems(free)

       }

      }

    }

    listFreeItems(list){

       var template_list= Template7.compile($('#Template-FreeItems-List').html());

       var html = template_list({items:list});

       $('#freeItems').html(html);

       $('#listFreeItemsModal').modal();

    }

    paymentMethods(){

      var self = this;

      self.calcPayableAmount();

     $('#billPayModal').modal()

    }

    setBillNo(data){

      var self = this;

      self.billno = data['details'].BillNo != null ? parseInt(data['details'].BillNo)+1 : self.billno;

      $('.billno').val(self.billno);

      self.lastbillamt = data['details'].BillAmount != null ? parseFloat(data['details'].BillAmount) : self.lastbillamt

      $('.lastamt').val(parseFloat(self.lastbillamt).toFixed(2));

    }

    deleteObject(id){

      var self = this;

      if(self.bill_items[id]){
        self.TrackDeletedItems(self.bill_items[id].code,self.bill_items[id].price);
      }

      delete self.bill_items[id]

      self.calcSubTotal()

      self.calcGrandTotal()

    }

    updateBillItems(){

      var self = this;

      self.data_id = []

      $('#ItemListBody tr').each(function(i,v){

        if ($(v).attr('data-id')) {

        self.data_id.push($(v).attr('data-id'))

        }

      })

     
      $.each(self.bill_items,function(i,v){

         if(self.data_id.indexOf(i) == -1){
            
            delete self.bill_items[i]

         }
         else{
             self.bill_items[i].serial = serial;

         }

      })

    }
    initializeCounterSettings(){
      var self = this;

      if(!self.discountenabled){
         $('#discountFlag').attr('readonly','true')
      }
      // self.editenabled
      // self.scaleenabled
      if (!self.cardenabled) {
        $("#pay_type option[value='card']").remove();
      }
     
      $('#noBags').attr('max',self.maxbags)
    }
    setCookie(cname, cvalue, exdays=365) {
  	    var d = new Date();
  	    d.setYear(d.getFullYear() + exdays);
  	    var expires = "expires="+ d.toUTCString();
  	    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  	}
  	getCookie(cname) {
  	    var name = cname + "=";
  	    var decodedCookie = decodeURIComponent(document.cookie);
  	    var ca = decodedCookie.split(';');
  	    for(var i = 0; i <ca.length; i++) {
  	        var c = ca[i];
  	        while (c.charAt(0) == ' ') {
  	            c = c.substring(1);
  	        }
  	        if (c.indexOf(name) == 0) {
  	            return c.substring(name.length, c.length);
  	        }
  	    }
  	    return "";
  	}
    getDateTime(){
      var self = this

        var today = new Date();

        var h = today.getHours();

        var ampm = h >= 12 ? 'PM' : 'AM';

        h = h % 12;

        h = h ? h : 12

        var m = today.getMinutes();

        var s = today.getSeconds();

        var date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear().toString().substr(-2);

        h = self.checkTime(h);

        m = self.checkTime(m);

        s = self.checkTime(s);

        return date + " " + h + ":" + m +" "+ampm ;

    }
     checkTime(i) {

      if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10

        return i;

    }
    getTime(){
      var self = this

        var today = new Date();

        var h = today.getHours();

        var ampm = h >= 12 ? 'PM' : 'AM';

        h = h % 12;

        h = h ? h : 12

        var m = today.getMinutes();

        h = self.checkTime(h);

        m = self.checkTime(m);


        return h + ":" + m +" "+ampm ;

    }
    getDate(){

      var self = this

      var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        var today = new Date();

        var date = today.getDate()+'-'+months[today.getMonth()]+'-'+today.getFullYear();

        return date;

    }



}