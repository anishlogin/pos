$(function(){
    var shiftC = new ShiftClose()
    $('#thisDate').on('change',function(){
        var date = $('#thisDate').val()
        var url = 'DataResponse.php?req=posclose';

        $.get(url, {date: date}, function (data) {

            shiftC.addData(data['details'])
            $('.remitted').trigger('keyup');

        },'json');
    });
    $('#thisDate').trigger('change');
    $(document).on('keyup','.remitted',function(){
        var remitted = $(this).val()=='' ? 0 :$(this).val()
        shiftC.calcDiff(remitted,$(this))
    })
    $('#closeShifts').click(function(){
        var billToSave = [];
        $('#listBills tr').each(function(i,v){
            if ($(v).attr('data-id')) {
                if (! $(v).attr('data-remitted') || $(v).attr('data-remitted') == 0) {
                    billToSave.push(shiftC.bills[$(v).attr('data-id')])
                }

            }
            else{
                alert('No data to save')
                return false;
            }
        })
        shiftC.closeShifts(billToSave)
    })
    /*$('#showList').trigger('click');*/
    $(document).on('click','.adjmntDetail',function(){

        tr = $(this).closest('tr');
        var id = tr.attr('data-id');
        shiftC.showAdjustmentDetails(id,tr);
    });

    $(document).on('keyup','input[name="adjmtRemark"]',function () {
        var tr = $(this).closest('tr');
        if(!tr.next().length) {
            shiftC.addNewRow();
        }
    });

    $(document).on('click','.remove',function () {
        var tr = $(this).closest('tr');
        if(tr.next().length) {
            tr.remove();
        }
    });


    $(document).on('click','.saveAdjustment',function(){
        var id = $(this).attr('data-id');
        var amount = $(this).attr('data-amount');
        if(isNaN(amount=Number(amount))) amount = 0;
        var data = [];
        var total = 0;
        $('#AdjustmentBody tr').each(function(){
            var tr = $(this).closest('tr');
            var remark = tr.find('input[name="adjmtRemark"]').val();
            var amt = tr.find('input[name="adjmtAmount"]').val();
            if(isNaN(amt=Number(amt))) amt = 0;
            data.push({'Remarks':remark,'Amount':amt});
            total +=amt;
        });
        if(parseFloat(amount).toFixed(2) === parseFloat(total).toFixed(2)) {
            shiftC.addAdjustmentDetails(id,data);
            $('#adjustmentDetailsModel').modal('toggle');
        } else
            showNotification('Error','Please Enter Correct Amount','error');

    })



})