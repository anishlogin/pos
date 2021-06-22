$(document).ready(function(){
	function generatebarcode(code){
		$.get("barcode.php", {text: code}, function (data) {
			$("#barcode").attr("src",data)
	    });
	}
	$("#code").on("change",function(e){
		var code = $(this).val()
		if (code) {
			$("#barcode").html(`<img alt='${code}' style="margin:auto;width:20%" src='barcode.php?text=${code}'/>`)
			// generatebarcode(code)
		}
		else{
			$("#barcode").html("")
		}
	})
	$("#print_barcode").click(function(){
		var count = $("#print_count").val()
		if(!count){
			alert('Please Enter print count')
			$('#print_count').focus()
			return false
		}
		var barcode_no = $("#code").val();
		var count = $("#print_count").val()
		var mrp = $("#MRP").val()
		window.open("print.php?code="+barcode_no+"&qty="+count+"&mrp="+mrp,"__blank")


		// var barcode = $("#barcode").html()
		// for (var i = 0; i < count; i++) {
		// 	$("#barcode_div").append(`
		// 		<div style="width:140px; font-family:verdana; height:60px; font-size:11px; text-align:center; word-wrap: break-word;margin-top:5px;margin-bottom:5px;float:left;">
  //                               <div style="margin-bottom:0px;">Lakshmi Market</div>
  //                               <div style="margin-top:0px;">${barcode}</div>
  //                       </div>
		// 		`)
		// }
		// $("#barcode_div").find("img").css("margin","none")
		// $("#barcode_div").print({
		// 	stylesheet : "assets/css/print_bar.css",
		// })
	})
	

});
function randomBarcode(){
        var barcode = Math.floor(100000 + Math.random() * 900000)
        var url = "item_functions.php";

        $.get( url,{'barcode':barcode} ,function( data ) {
            var data_arr = data.split(",")
            
            if(data_arr[0] == 'success'){
                $('#code').val(data_arr[1])
                $('#code').trigger("change");
            }else{
                randomBarcode()
            }
        });
    }

