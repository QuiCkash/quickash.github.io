
$(function () {

            $("#plus").click(function (e) { 
            		e.preventDefault();

                $("#t>tbody:last").append("<tr><td>" + $("#produit").val() + "</td><td>" + $("#qtite").val() + "</td></tr>");
                
            });

            $('#facture').click(function (e) {
            	$("tr:not(.required)").remove();
            });
 });
