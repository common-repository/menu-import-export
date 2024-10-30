(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

$(document).on("change", "#import_menus", function(){
	if (this.files && this.files[0]) {
	    var myFile = this.files[0];
	    var reader = new FileReader();
	    
	    reader.addEventListener('load', function (e) {
	      $('.import_menus_data_as_txt').html(e.target.result);
	    });
	    
	    reader.readAsBinaryString(myFile);
	  } 
});

$(document).on("click", ".btn-export_menus", function(){
	 var datas = {
	  'action': 'recorp_export_menus',
	  'nonce': recorp_menu.nonce
	};
	
	$.ajax({
	    url: recorp_menu.ajax_url,
	    data: datas,
	    type: 'post',
	
	    success: function(r){
			exportToCsv('menu export '+recorpGetDateTime()+'.csv', r);
	    }, error: function(){
	    	alert('Something went wrong !');
	  }
	});
});

$(document).on("click", ".btn-import_menus", function(){


	 var datas = {
	  'action': 'recorp_import_menus',
	  'csv_data': $('.import_menus_data_as_txt').html(),
	  'nonce': recorp_menu.nonce
	};
	
	$.ajax({
	    url: recorp_menu.ajax_url,
	    data: datas,
	    type: 'post',
	
	    success: function(r){
	    	jQuery('[data-dismiss="modal"]').click();

	    	$.notify({
					// options
					message: 'Successfully imported the menus!' 
				},{
					// settings
					type: 'success',
					placement: {
						from: "top",
						align: "center"
					},
					animate:{
						enter: "animated fadeInDown",
						exit: "animated fadeOutUp"
					},
					delay: 2000
				}

				);
	    	setTimeout(function() {
	    		window.location.href = 'nav-menus.php';
	    	}, 5000);
	    	
	    }, error: function(){
	    	alert('Something went wrong !');
	  }
	});
});

//$('[data-toggle="popover"]').popover();

$(function () {
  $('[data-toggle="popover"]').popover({
  	trigger: 'hover focus',
  	delay: { "show": 500, "hide": 100 }
  })
});

function recorpGetDateTime() {
        var now     = new Date(); 
        var year    = now.getFullYear();
        var month   = now.getMonth()+1; 
        var day     = now.getDate();
        var hour    = now.getHours();
        var minute  = now.getMinutes();
        var second  = now.getSeconds(); 
        if(month.toString().length == 1) {
             month = '0'+month;
        }
        if(day.toString().length == 1) {
             day = '0'+day;
        }   
        if(hour.toString().length == 1) {
             hour = '0'+hour;
        }
        if(minute.toString().length == 1) {
             minute = '0'+minute;
        }
        if(second.toString().length == 1) {
             second = '0'+second;
        }   
        var dateTime = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;   
         return dateTime;
    }



function exportToCsv(filename, rows) {
        var processRow = function (row) {
            var finalVal = '';
            for (var j = 0; j < row.length; j++) {
                var innerValue = row[j] === null ? '' : row[j].toString();
                if (row[j] instanceof Date) {
                    innerValue = row[j].toLocaleString();
                };
                var result = innerValue.replace(/"/g, '""');
                if (result.search(/("|,|\n)/g) >= 0)
                    result = '"' + result + '"';
                if (j > 0)
                    finalVal += ',';
                finalVal += result;
            }
            return finalVal + '\n';
        };


        var blob = new Blob([rows], { type: 'text/csv;charset=utf-8;' });
        if (navigator.msSaveBlob) { // IE 10+
            navigator.msSaveBlob(blob, filename);
        } else {
            var link = document.createElement("a");
            if (link.download !== undefined) { // feature detection
                // Browsers that support HTML5 download attribute
                var url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", filename);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }
    }
    


})( jQuery );

