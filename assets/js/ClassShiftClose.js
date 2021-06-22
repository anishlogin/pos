class ShiftClose{
    constructor(){
        this.cardtotal = 0
        this.cashTotal =0
        this.adjustedTotal =0
        this.Total=0
        this.remittedTotal=0
        this.diffTotal=0
        this.bills={};
    }
    addData(data){
        var self =this;
        self.bills = data;
        self.cardtotal = 0;
        self.cashTotal = 0;
        self.adjustedTotal = 0;
        self.Total = 0;
        $.each(data,function(i,v){
            self.cardtotal  += v.cardsales != null ? parseFloat(v.cardsales) :0
            self.cashTotal +=v.cashsales != null ? parseFloat(v.cashsales) :0
            self.adjustedTotal +=parseFloat(v.adjusted)
            self.Total +=parseFloat(v.total)
        })
        var shiftInputs = { cardtotal :parseFloat(self.cardtotal).toFixed(2),
            cashTotal :parseFloat(self.cashTotal).toFixed(2),
            adjustedTotal :parseFloat(self.adjustedTotal).toFixed(2),
            Total :parseFloat(self.Total).toFixed(2),
            remittedTotal :parseFloat(self.remittedTotal).toFixed(2),
            diffTotal :parseFloat(self.diffTotal).toFixed(2)
        }
        var template_shift_inputs = Template7.compile($('#template-shift-inputs').html());
        var shift_inputs = template_shift_inputs({ items:shiftInputs });

        var template_item_bill = Template7.compile($('#template-List-bills').html());
        var item_bill = template_item_bill({ items:data });

        $('#listBills').html(item_bill);
        $('.shift-input').html(shift_inputs)
    }
    calcDiff(remitted,element){
        var remittedTotal = 0
        var self = this;
        remittedTotal = parseFloat(remitted)
        var id = element.closest('tr').attr('data-id')
        var diff =  parseFloat(self.bills[id].cashsales)  - parseFloat(remitted)
        self.bills[id].remittedTotal = remittedTotal
        self.bills[id].diffTotal = diff
        element.closest('tr').find('.difference').val(parseFloat(diff).toFixed(2))
        self.calcRemittedTotal()
        self.calcDiffTotal()
    }
    calcRemittedTotal(){
        var self = this;
        self.remittedTotal = 0
        $.each(self.bills,function(i,v){
            self.remittedTotal  += v.remittedTotal !=undefined ? v.remittedTotal : 0
        })
        $('#remittedTotal').val(parseFloat(self.remittedTotal).toFixed(2))
    }
    calcDiffTotal(){
        var self = this;
        self.diffTotal = 0
        $.each(self.bills,function(i,v){
            self.diffTotal  += v.diffTotal !=undefined ? v.diffTotal : 0
        })
        $('#diffTotal').val(parseFloat(self.diffTotal).toFixed(2))
    }
    closeShifts(bills){


        var url = 'DataResponse.php?req=closeBills';

        $.post(url, {bills: bills,user:$('input[name="user"]').val()}, function (data) {

            if(data['success']){
                $.alert('Closed Successfully')
                setTimeout(function(){
                    location.reload();
                },2000)
            }

        },'json');
    }
    calcNetSales(){

    }
    showAdjustmentDetails(id,element){
        var self =this;
        var amount = element.find('input[name="adjusted"]').val();
        var adjustment_details = self.bills[id]['adjustments'];
        var template_adjustment = Template7.compile($('#Template-Adjustment-List').html());
        var adjustment = template_adjustment({ adjustment:adjustment_details });
        $('#adjustmentDetailsPopup').html(adjustment);
        $('#adjustmentDetailsModel').modal('toggle');
        $('.saveAdjustment').attr({'data-id':id,'data-amount':amount});
        self.addNewRow();

    }
    addAdjustmentDetails(id,data){
        var self =this;
        self.bills[id]['adjustments'] = data;
    }

    addNewRow(){
        var self =this;
        $('#AdjustmentBody').append(
            '<tr>\n' +
            '<td class="tb-10 tb-center"><input type="text" name="adjmtRemark" autocomplete="off"\n' +
            'class="form-control table-input" ></td>\n' +
            '<td class="tb-10 tb-center"><input type="text" name="adjmtAmount" autocomplete="off"\n' +
            'class="form-control table-input">\n' +
            '<td><a href="#" class="remove" style="margin: 0 auto;display: table;"><i class="fa fa-trash"></i></a></td>\n' +
            '</tr>'
        );
    }

}