/*let produit = document.getElementById('produit');
let qtite = document.getElementById('qtite');
let plus = document.getElementById('plus');

let form = document.getElementsByTagName('form');

plus.addEventListener("click",function(e){
	e.preventDefault();

	let colpro= document.createElement('td');
	let colqua = document.createElement('td');
	let colplus = document.createElement('td');
	let row =document.createElement('tr');

	row.appendChild(colpro);
	row.appendChild(colqua);
	row.appendChild(colplus);

	colpro.innerText = produit.value;
	colqua.innerText = qtite.value;
	colplus.innerText = plus.value;

	let button = document.createElement('button');
	button.innerText = "+";
	plus.appendChild(button);


});*/


$(function () {

            $("#plus").click(function (e) { 
            		e.preventDefault();

                $("#t>tbody:last").append("<tr><td>" + $("#produit").val() + "</td><td>" + $("#qtite").val() + "</td></tr>");
                
            });
 });