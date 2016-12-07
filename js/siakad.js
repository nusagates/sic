/* 
 * Name: siakad.js
 * Author: Ahmad Budairi
 * Author_URL: www.nusagates.com
 */

function auto(id, sumber){
	$(""+id+"").autocomplete({
		source : ""+sumber+""	
	});
}