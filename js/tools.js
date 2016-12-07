/* CARTE */
/* ***** */
var icon2=null;
var geo_temp={leg_lat:null,leg_lng:null,lat:null,lng:null,rubber:false};
var map_big={obj:null,js_charge:false,refresh_site:false,liste_geo:null,poly_mark:null,poly_legs:null,poly_sommets:null,t_refresh:0,edit_tool_en_cours:false,info_timer:0};
var latlg_c=null;
var flightPath;
var Markers=[];
var map_={edit:false,centre:null,leg_lat:"",leg_lng:""};
function initMap() { //Map unique pour tous les sites

	var Les_Para = JSON.parse(sessionStorage.getItem("last_geo"));
	if (Les_Para!=null) {
		geo.latitude=Les_Para.lat;
		geo.longitude=Les_Para.lng;
		geo.zoom=Les_Para.zoom;
		geo.idx_last=Les_Para.idx_geo;
		console.dir(Les_Para);
		
	}
	map_big.obj = new google.maps.Map(document.getElementById('big_map'), {
			center : {
				lat : geo.latitude,
				lng : geo.longitude
			},
			zoom : geo.zoom,
			draggable : true,
			scrollwheel : true,
			mapTypeId : google.maps.MapTypeId.HYBRID,
			draggableCursor : "crosshair",
		});
	
	
		google.maps.event.addListener(map_big.obj, 'click', function(event) {
						
							if (map_big.edit_tool_en_cours) {
								tool_click_map(event.latLng);
							} 
						

		});	
		
		google.maps.event.addListener(map_big.obj, "mousemove", function(event) {
				tool.latlg_move=event.latLng;
		
				var s='<table  class="tab_pos" ><tr><td>'+m_lat60(tool.latlg_move.lat())+'</td><td>'+m_lng60(tool.latlg_move.lng())+'</td>';
				s+='<td>'+latDMS(tool.latlg_move.lat())+'</td><td>'+lngDMS(tool.latlg_move.lng())+'</td></tr></table>';
				
				document.getElementById("curseur").innerHTML=s;
				
				latlg_c=tool.latlg_move;
				if (tool.create_en_cours) {
							new_tool_leg(tool.idx_courant,tool.courant);
								if (tool.action=="waypoints") {
									var dist=arrondi(distance(tool.latlg_last.lat(),tool.latlg_last.lng(),latlg_c.lat(),latlg_c.lng()));
									var s=dist+" nm<br>"+cap_1_2+" deg";
									bulle(s,latlg_c);
								}
								if (tool.action=="bearings") {
									var dist=arrondi(distance(latlg_c.lat(),latlg_c.lng(),tool.latlg_last.lat(),tool.latlg_last.lng()));
									var s=dist+" nm<br>"+cap_1_2+" deg";
									bulle(s,latlg_c);
								}
								if (tool.action=="area") {
									var dist=arrondi(distance(tool.latlg_last.lat(),tool.latlg_last.lng(),latlg_c.lat(),latlg_c.lng()));
									var s=dist+" nm<br>"+cap_1_2+" deg";
									bulle(s,latlg_c);
								}
								if (tool.action=="circle") {
									var dist=arrondi(distance(tool.latlg_last.lat(),tool.latlg_last.lng(),latlg_c.lat(),latlg_c.lng()));
									var s=dist+" nm<br>"+cap_1_2+" deg";
									bulle(s,latlg_c);
									
								}
				}
				if (tool.rubber) {
					var s='<img src="images/boutons/gomme.png"  alt="" >';
					bulle(s,latlg_c);
				}
				
				
		});
	
		google.maps.event.addListener(map_big.obj, 'bounds_changed', function() {
							var centre= map_big.obj.getCenter();
							geo.latitude=centre.lat();
							geo.longitude=centre.lng();
							geo.zoom=map_big.obj.getZoom();
							var s = {
								idx_geo : geo.idx_last,
								lat : geo.latitude,
								lng : geo.longitude,
								zoom :geo.zoom
							};
							sessionStorage.setItem("last_geo", JSON.stringify(s));
		});
		
		for (var i=0;i<tools.length;i++) {
				bt_map_css("bt_tool_"+tools[i],false);	// Init boutons
		}
		tool_timer();
}
var Souris={X:0,Y:0};

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
	
}


function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
	Souris.X=ev.clientX; Souris.Y=ev.clientY; 
	
	$("#"+data).css("left",Souris.X);
   $("#"+data).css("top",Souris.Y);
	
}






function pos_mouse(evt) {
	var x=0; var y=0;
				if (document.layers) {
					x=evt.x; y=evt.y;
				} 
				if (document.all) {
					x=event.clientX; y=event.clientY;
				} else {
					if (document.getElementById) {
						x=evt.clientX; y=evt.clientY; 
					}
				}
				mouse_top_=y;
				mouse_left=x;
}

/* Bouton Toolbox Carte_2D*/
var tools=new Array("waypoints","bearings","area","circle","rubber");
var tool_line=new Array;
var tool_sommet=null;  //MArqueur des sommets
var tool={action:"",create_en_cours:false,latlg_fin:null,latlg_last:null,latlg_move:null,distance:0,modif_cercle:false,courant:0,rubber:false,idx_courant:0};
function tool_click(action){
	//on arrete tout en cours
	
				if (action=="rubber") {
					tool.rubber=!tool.rubber;
					bt_map_css("bt_tool_rubber",tool.rubber);	
				} else {
						if(map_big.edit_tool_en_cours && !tool.create_en_cours) {
							map_big.edit_tool_en_cours=false; 
							if (tool_sommet!=null) tool_image_au_sommet(tool.courant); //
						}
						
						if (tool.create_en_cours) {
							tool.create_en_cours=false;
							var x=tool_line[tool.courant].legs.pop(); // Enleve dernier leg
							tool_image_au_sommet(tool.courant);	
							
						}
						 
						tool.rubber=false;
						
						for (var i=0;i<tools.length;i++) {
							bt_map_css("bt_tool_"+tools[i],false);	
						}
						if (tool.action==action || action=="stop" ) { //on arrete
								tool.action="";
						} else {  //new action
							if(action!="") {
								
									cancel_edit_carte();// Arrete edition carte
									tool.action=action;
									bt_map_css("bt_tool_"+action,true);	 			
									map_big.edit_tool_en_cours=true;
									
							}
						}
				}
}

function tool_click_map(latlg) {
	tool.latlg_last=latlg;
	if (!tool.create_en_cours && tool.action!="") {
		tool.create_en_cours=true;
		tool.latlg_fin=latlg;
		
		switch(tool.action) {
			case "waypoints":
					var color= "#FF6666";
			break;
			case "bearings":
					var color="#FFAA00";
			break;
			case "area":
					var color= "#FFFF00";			
			break;
			case "circle":
					var color= "#FF0000";
			break;
			default:
			break;
		}
		
			
		  tool_line.push(new tool_create_obj(new google.maps.MVCArray(),tool.action,color));//Creation objet line
		  tool.courant=tool_line.length-1;
		  var t=tool.courant;
		  tool_rajoute_sommet(t,latlg);
		   tool_rajoute_sommet(t,latlg);
			  
	  	if(tool.action=="area") {
			tool_line[t].mark = new google.maps.Polygon({
					  paths:tool_line[t].legs,
					  geodesic:true,
					  strokeColor: tool_line[t].color,
					  strokeOpacity: 0.8,
					  strokeWeight: 2,
					  fillColor: tool_line[t].color,
					  fillOpacity: 0
			});
		} else {
			if(tool.action=="circle") {
					tool_line[t].mark = new google.maps.Circle({
					  center:latlg,
					  radius: 1,
					  strokeColor: tool_line[t].color,
					  strokeOpacity: 0.8,
					  strokeWeight: 2,
					  fillColor: tool_line[t].color,
					  fillOpacity: 0
					});
				
		   } else {
			
					tool_line[t].mark = new google.maps.Polyline({
					path: tool_line[t].legs,
					geodesic:true,
					strokeColor: tool_line[t].color,
					strokeOpacity: 0.8,
					strokeWeight: 2
				  });
		   }
		}
			
			tool_line[t].mark.setMap(map_big.obj); //Creation du poly
			google.maps.event.addListener(tool_line[t].mark, "click", function(event) {
				
				if(tool.rubber && ( !map_big.edit_tool_en_cours || tool_line[t].action=="cercle" || tool_line[t].action=="bearings") ){ // Efface tout l'objet
						efface_objet(t);
				} else {
					if(map_big.edit_tool_en_cours) {
							tool_click_map(event.latLng); //Click sur la ligne du leg
							
						} else {
							tool_click(tool_line[t].action); // On relance l'edition et non la création
							tool_click(tool_line[t].action); //Pas de creation
							map_big.edit_tool_en_cours=true;
							tool_image_au_sommet(t);
						}
				}
				
			});
			google.maps.event.addListener(tool_line[t].mark, "mousemove", function(event) {
				tool.latlg_move=event.latLng;
				info_tool_line(t);				
			});
			google.maps.event.addListener(tool_line[t].mark, "click", function(event) {
				tool.latlg_move=event.latLng;
				info_tool_line(t);				
			});
		
	} else {  //En cours
	  if (tool.create_en_cours ) {
		tool_rajoute_sommet(tool.courant,latlg);
		if ( tool_line[tool.courant].legs.getLength() >2 &&  tool_line[tool.courant].action=="bearings"     ) tool_click(tool_line[tool.courant].action); // On arrete le bearing
		if ( tool_line[tool.courant].legs.getLength() >2 &&  tool_line[tool.courant].action=="circle"  ) {
			tool_line[tool.courant].legs.setAt(1,latlg);
			tool_click(tool_line[tool.courant].action); // On arrete le cercle
		}

	  } else {
		  map_big.edit_tool_en_cours=false;
	  }
	}
	tool_image_au_sommet(tool.courant);
}
function tool_rajoute_sommet(t,latlg){
		tool_line[t].legs.push(latlg);
		tool.idx_courant=tool_line[t].legs.getLength()-1;
}
function new_tool_leg(idx,t){ // Sur mouvement souri sur carte ou sur sommet
	
	var point=tool.latlg_move;
	tool_line[t].legs.setAt(idx,point); //Deplace le dernier sommet mis
	if(tool_line[t].action=="circle") {
		var latlg=tool_line[t].legs.getAt(0); //Centre
		tool_line[t].mark.setCenter(latlg);
		if (!tool.create_en_cours) point=tool_line[t].legs.getAt(1); // sur le cercle
		var d=1852*distance(latlg.lat(),latlg.lng(), point.lat(), point.lng()) ;
		tool_line[t].mark.setRadius(d);
	} 
	info_tool_line(t);
}
function tool_image_au_sommet(t){
	
			var nb=tool_line[t].legs.getLength();
			if (tool.create_en_cours) {
				nb--;
			} 
			if (tool_sommet!=null) { // Efface ancien image au sommet
												for (var i=0;i<tool_sommet.length;i++) {
													tool_sommet[i].setMap(null);
												}
												tool_sommet=null;
			}
			
			if (map_big.edit_tool_en_cours) { // On est en edition, creation ou non de points
						tool_sommet=new Array;
						
						var image_r = new google.maps.MarkerImage('images/carre11s.png',
								// This marker is 20 pixels wide by 32 pixels tall.
								new google.maps.Size(11,11),
								// The origin for this image is 0,0.
								new google.maps.Point(0,0),
								// The anchor for this image 
								new google.maps.Point(5,5));
						var image_i = new google.maps.MarkerImage('images/carre9i.png',
								// This marker is 20 pixels wide by 32 pixels tall.
								new google.maps.Size(9, 9),
								// The origin for this image is 0,0.
								new google.maps.Point(0,0),
								// The anchor for this image 
								new google.maps.Point(4,4));
						
						for (var i=0;i<nb;i++){
							var latlg=tool_line[t].legs.getAt(i);
							var marker = new google.maps.Marker({
										  position: latlg,
										  map:map_big.obj,
										  raiseOnDrag:false,
										  icon: image_r,
										 
										  draggable:true
									  });
							tool_sommet.push(marker);
							tool_attache_carre_sommet(marker,i,t,true);
							
										//Point intermediare
							if((tool_line[t].action=="waypoints" && i>0) ||  tool_line[t].action=="area") {
										var j=(i-1+nb)%nb;
												var latlg_p=tool_line[t].legs.getAt(j); //point precedent
												var lng1=latlg.lng();
												var lng2=latlg_p.lng();
												if(lng1*lng2<0 && (Math.abs(lng1)+ Math.abs(lng2))>180) { lng1=(lng1+360)%360; lng2=(lng2+360)%360;}
												lng1=(lng1+lng2)/2;
												lng1=-180+(lng1+180)%360;
												
												var point_inter = new google.maps.LatLng((latlg.lat()+latlg_p.lat()) /2 ,lng1);  
												 marker = new google.maps.Marker({
															  position:  point_inter,
															  map:map_big.obj,
															  icon: image_i,
															  raiseOnDrag:false,
															  
															  draggable:true
														  });
												tool_sommet.push(marker);
												tool_attache_carre_sommet(marker,i,t,false);
							
							}
						}
			}
	
}
function tool_attache_carre_sommet(marker,i,t,reel){ //fonction pour independance variable
	google.maps.event.addListener(marker, "drag", function(event) {
				tool.latlg_move=event.latLng;
				 
				if (reel){
					new_tool_leg(i,t);
				} else {
					drag_sommet_inter(t,i,tool.latlg_move);
					reel=true;
				}
				
		});
		google.maps.event.addListener(marker, "click", function() { 
		    if(tool.rubber) { // Retrait un leg ou l'objet total
			  if (tool_line[t].action=="cercle" || tool_line[t].action=="bearings") {
				  efface_objet(t);
			  } else {
				  tool_line[t].legs.removeAt(i); //Retrait un sommet
				  tool_image_au_sommet(t);
			  }
				
			} else {
				tool_click("stop"); // Click sur un sommet pour arreter creation
			}
		});
		google.maps.event.addListener(marker, "dragend", function() {
			tool_image_au_sommet(t);// recre les sommets dans le cas d'un draguage de point intermediare
		});
}
function drag_sommet_inter(t,i,latlg){
	tool_line[t].legs.insertAt(i,latlg);
	tool.idx_courant=tool_line[t].legs.getLength()-1;
}
function efface_objet(t){
						map_big.edit_tool_en_cours=false;
						tool.create_en_cours=false;
						tool_image_au_sommet(t);
						tool_line[t].mark.setMap(null);
						tool_click("stop");
}
function info_tool_line(t) {
	var latc=tool.latlg_move.lat(); //souri courant
	var longc=tool.latlg_move.lng();
	var dist_line="nm";var lat1=0;var lng1=0;var dist_total=0;cap_1_2="deg";var cap_2_1="deg";
	var lat_l=0;var long_l=0;
	var latlg=null;
	var inleg=false;
	var bgcol=' bgcolor="'+tool_line[t].color+'" ';
	var title="";
	var s="<table id='parametre_t'>";
	switch(tool_line[t].action) {								
			case "waypoints":
					var nb=tool_line[t].legs.getLength();
					for (var i=0;i<nb;i++) {
						var latlg=tool_line[t].legs.getAt(i);
						inleg=false;
						lat_l=latlg.lat();
						long_l=latlg.lng();
						if (i>0) {
							if ((lat1-latc)*(lat_l-latc)<=0 && (lng1-longc)*(long_l-longc)<=0) inleg=true;
							dist_line=distance(lat1,lng1,lat_l,long_l);dist_total+=dist_line;dist_line=arrondi(dist_line);
							cap_2_1=(cap_1_2+180)%360;
						}
						lat1=lat_l;lng1=long_l;
						bgcol='';
						if (inleg) bgcol=' bgcolor="'+tool_line[t].color+'" ';
						
						s+="<tr"+bgcol+"><td>"+m_lat60(lat_l)+"</td><td>"+m_lng60(long_l)+"</td><td>"+dist_line +"</td><td>"+cap_1_2 +"</td><td>"+cap_2_1+"</td></tr>";
					}
					s+="<tr><td></td><td>Total:</td><td>"+arrondi(dist_total)+"</td><td>nm&nbsp;&nbsp;</td><td></td></tr>";
					if (nb<3) s+="<tr><td colspan='5' ><div class='message'>Double click to end</div></td></tr>";
					title=pix.map_waypoints;
			break;
			case "bearings":
					s+="<tr><td>Latitude</td><td>Longitude</td><td>nm</td><td>deg</td></tr>";
							var latlg=tool_line[t].legs.getAt(0); //Debut
							var point=tool_line[t].legs.getAt(1); // Fin
							dist_total=arrondi(distance(latlg.lat(),latlg.lng(), point.lat(), point.lng())) ;
							cap_2_1=(cap_1_2+180)%360;
							s+="<tr><td>"+m_lat60( latlg.lat())+"</td><td>"+m_lng60(latlg.lng())+"</td><td></td><td>"+cap_2_1 +"</td></tr>";
							s+="<tr"+bgcol+"><td>"+m_lat60( point.lat())+"</td><td>"+m_lng60( point.lng())+"</td><td>"+dist_total +"</td><td>"+cap_1_2 +"</td></tr>";
							title=pix.map_bearings;
			break;
			case "area":
					var nb=tool_line[t].legs.getLength();
					for (var i=0;i<=nb;i++) {
						var j=i%nb;
						var latlg=tool_line[t].legs.getAt(j);
						inleg=false;
						lat_l=latlg.lat();
						long_l=latlg.lng();
						if (i>0) {
							if ((lat1-latc)*(lat_l-latc)<=0 && (lng1-longc)*(long_l-longc)<=0) inleg=true;
							dist_line=distance(lat1,lng1,lat_l,long_l);dist_total+=dist_line;dist_line=arrondi(dist_line);
							cap_2_1=(cap_1_2+180)%360;
						}
						lat1=lat_l;lng1=long_l;
						bgcol='';
						if (inleg) bgcol=' bgcolor="'+tool_line[t].color+'" ';
						
						s+="<tr"+bgcol+"><td>"+m_lat60(lat_l)+"</td><td>"+m_lng60(long_l)+"</td><td>"+dist_line +"</td><td>"+cap_1_2 +"</td><td>"+cap_2_1+"</td></tr>";
					}
					s+="<tr><td></td><td>Total:</td><td>"+arrondi(dist_total)+"</td><td>nm&nbsp;&nbsp;</td><td></td></tr>";
					if (nb<3) s+="<tr><td colspan='5' ><div class='message'>Double click to end</div></td></tr>";
					title=pix.map_area;
			break;
			case "circle":
					var latlg=tool_line[t].legs.getAt(0); //Centre
				 	var point=tool_line[t].legs.getAt(1); // sur le cercle
					dist_total=distance(latlg.lat(),latlg.lng(), point.lat(), point.lng()) ;
					s+="<tr><td>Latitude center</td><td>"+m_lat60(latlg.lat())+"</td><td>deg</td</tr>";
					s+="<tr><td>Longitude center</td><td>"+m_lng60(latlg.lng())+"</td><td>deg</td</tr>";
					s+="<tr "+bgcol+"><td>Ray /Rayon</td><td>"+arrondi(dist_total)+"</td><td>nm</td</tr>";
					title=pix.map_circle;
			break;
			default:
			break;
	}

	document.getElementById("p_info").innerHTML=s+"</table>";
	document.getElementById("para_titre").innerHTML=title;
	document.getElementById("parametre").style.visibility="visible";
}

function tool_create_obj(legs,action,color) {  //Objet Liste de points relev etc
	this.action=action;
	this.color=color;
	this.legs=legs;
	this.mark="";
}
function cancel_edit_carte() {
		
		
		images_au_sommet(); //Retirer les images
		if (geo_temp.rubber) del_sommet();
	//	var center = new google.maps.LatLng(geo.latitude,geo.longitude);
	//	map_big.obj.panTo(center);
	//	map_big.obj.setZoom(geo.zoom);
		
}

function images_au_sommet(){
	
	if (map_big.poly_sommets!=null) { // Efface ancien sommet
										for (var i=0;i<map_big.poly_sommets.length;i++) {
											map_big.poly_sommets[i].setMap(null);
										}
										map_big.poly_sommets=null;
	}
	
}

/* general */
/***********/
function show(id) {geId(id).style.display='block';} //show element
function hide(id) {geId(id).style.display='none';} //hide element
function geId(id) { return document.getElementById(id); } //get element by ID

function geinH(id) { return document.getElementById(id).innerHTML; } //get element by ID

//Trigo 
function m_lat60(en_deci) {
	return m_deci_deg(en_deci)+(( en_deci<0 ) ? " S" : " N");
	
}
function m_lng60(en_deci) {
	return m_deci_deg(en_deci)+(( en_deci<0 ) ? " W" : " E");
}
function m_deci_deg(en_deci)  {
            var en_abs = Math.abs(en_deci);
			var deci="*"+(100+60*(en_abs-Math.floor(en_abs)))+"0000";
			deci=deci.substr(2,7);
			return Math.floor(en_abs)+"&deg;"+deci;
} 
function deci_deg(en_deci)  {
            var en_abs = Math.abs(en_deci);
			var deci="*"+(100+60*(en_abs-Math.floor(en_abs)));
			if (deci.indexOf(".", 0)<0) deci=deci+".";
			deci=deci+"0000000000";
			deci=deci.substr(2,7);
			var en_60=Math.floor(en_abs)+"&deg;"+deci;
			return en_60;
} 
function latDMS(en_deci) {
	return D_M_S(en_deci)+(( en_deci<0 ) ? " S" : " N");
	
}
function lngDMS(en_deci) {
	return D_M_S(en_deci)+(( en_deci<0 ) ? " W" : " E");
}
function D_M_S(en_deci)  {
            var en_abs = Math.abs(en_deci);
			var deci="*"+(100+60*(en_abs-Math.floor(en_abs)));
			if (deci.indexOf(".", 0)<0) deci=deci+".";
			deci=deci+"0000000000";
			deci=deci.substr(2,11);
			
			var dc_abs = Math.abs(parseFloat(deci));
			var sec="*"+(100+60*(dc_abs-Math.floor(dc_abs)));
			if (sec.indexOf(".", 0)<0) sec=sec+".";
			sec=sec+"0000000000";
			sec=sec.substr(2,5);
			var en_dms=Math.floor(en_abs)+"&deg;"+Math.floor(dc_abs)+"'"+sec+'"';
			return en_dms;
} 
var cap_1_2=0;
function distance(lat1,lng1,lat2,lng2) {
			 lat1=Math.PI*lat1/180;
			 lng1=Math.PI*lng1/180;
			 lat2=Math.PI*lat2/180;
			 lng2=Math.PI*lng2/180;
			 var h=Math.acos(Math.sin(lat1)*Math.sin(lat2) + Math.cos(lat1)*Math.cos(lat2)*Math.cos(lng1-lng2));
			 dist =0+ 60*180*h/Math.PI;
			  if(h!=0) {
			 		var cos_=(Math.sin(lat2)-Math.sin(lat1)*Math.cos(h))  / (Math.cos(lat1)*Math.sin(h));
					cos_=Math.min(cos_,1); //Pb si lng egaux;
					cos_=Math.max(cos_,-1);	
			 	cap_1_2=0+Math.floor(0+ 180*Math.acos(cos_ )/Math.PI);
			} else {
				cap_1_2=0;
			}
			if (lng1>lng2) cap_1_2=360-cap_1_2;
			
	return dist;
}
function arrondi(dist) {
	if (dist >99) {
				dist=Math.floor(dist);
			} else {
				if (dist>10) {
					dist=Math.floor(dist*10)/10;
				} else {
					dist=Math.floor(dist*100)/100;
				}
			}
	return dist;
}
function bt_map_css(id,on_off){ // Changement aspect bouton
	if(on_off){
		geId(id).style.backgroundColor="#668";
		geId(id).style.border="inset";
	} else {
		geId(id).style.backgroundColor="";
		geId(id).style.border="outset";
	}
}
/*Timer*/
timer_bulle=0;
function tool_timer() {
		//bulle
		timer_bulle=Math.max(timer_bulle-1,0); 
		if (timer_bulle==0) document.getElementById("bulle").style.visibility="hidden";
		
 		setTimeout("tool_timer()",1000);
}
/*Bulle info sur carte */
function bulle(s,latlg) {
	
			//var msize=map.getSize() ;
			var msize={height:window.innerHeight,width:window.innerWidth};
			var mlatlng=map_big.obj.getBounds() ;
			var nord=mlatlng.getNorthEast().lat();
			var est=mlatlng.getNorthEast().lng();
			var sud=mlatlng.getSouthWest() .lat();
			var ouest=mlatlng.getSouthWest() .lng();
			document.getElementById("bulle").innerHTML=s;
			var hh=150+msize.height*(nord-latlg.lat())/(nord-sud);
			var ww=150+msize.width*(ouest-latlg.lng())/(ouest-est);
			if (ww+150>msize.width) ww=ww-150;
			document.getElementById("bulle").style.top=hh+"px";
			document.getElementById("bulle").style.left=ww+"px";
			document.getElementById("bulle").style.visibility="visible";
			timer_bulle=4;
}
function open_tools(){
	var myWindow = window.open("/tools/tools.php", "_blank", "fullscreen=yes , menubar=no, scrollbars=no,status=no,toolbar=no ");
}
