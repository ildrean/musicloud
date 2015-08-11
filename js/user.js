//(function(){	//protect function from outside access
var index = 0;		//current song index
var curSongTotal = 0;	//current amount of songs shown
var repeatType = 0;		//0 -> repeat-all, 1 -> repeat-one, 2 -> repeat-none
var shuffleType = 0;	//0 -> normal, 1 -> shuffle
var songs = [];
var songOrder = [];
var playlists = [];
$(document).ready(function(){
	$('#songtable').selectable({	//fix this, currently able to select non-id rows after playlist change, even though they no longer have any selectable attributes
		filter:'.song'
	});
	
	$("#uploadlink").fancybox({
		'hideOnContentClick': false,
		'hideOnOverlayClick': false,
		'type': "inline",
		'href': "#upload"
	});
	
	$("#uploadify").uploadify({
		'uploader'       : '../js/uploadify/uploadify.swf',
		'script'         : '../js/uploadify/uploadify.php',
		'cancelImg'      : '../js/uploadify/cancel.png',
		'scriptData'	 : {user_cookie : getCookie('user')},
		'queueID'        : 'fileQueue',
		'auto'           : false,
		'multi'          : true,
		'sizeLimit'		 : 26214400,
		'queueSizeLimit' : 20,
		//'fileDesc'	 : '*.mp3;*.wav;*.wma',
		//'fileExt'		 : '*.mp3;*.wav;*.wma',
		'buttonImg'		 : '../img/browse.png',
		'width'			 : 100,
		'height'		 : 100,
		'wmode'			 : 'transparent'
	});

	$.ajaxSetup ({cache: false});
	
	$("#jquery_jplayer_1").jPlayer({
		ready: function(){},
		swfPath: "/js/",
		supplied: "mp3"
	});
	
	$("#jquery_jplayer_1").bind($.jPlayer.event.ended, function(event) { playNext(); });//set behavior for song end event, based on repeat and shuffle Types
	$(".jp-next").click(function(){ playNext(); });//skip one song
	$(".jp-previous").click(function(){ playPrev(); });	//back one song
	
	$(".repeat").click(function(event){
		event.preventDefault();
		if(repeatType != 2){ 
			repeatType++;
		}else{ 
			repeatType = 0;
		}
		$("#repeat").append(repeatType);//debug
		//change symbol
	});
	
	$(".shuffle").click(function(event){
		event.preventDefault();
		if(shuffleType == 0){ 
			shuffleType = 1;
			shuffleInit(true);
		}else{ 
			shuffleType = 0;
			shuffleInit(false);
		}
		$("#shuffle").append(shuffleType);//debug
		//change symbol
	});
	

	
	playlistInit();
	loadPlaylist(0);
	update();
});

function playFile(ID){
	$.getJSON("/md5tofileinfo.php", {'ID': ID},
		function(data){
			$("#jquery_jplayer_1").jPlayer("setMedia", {
				mp3: data.filepath 			//modify to work with non mp3 files
			});
			$("#jquery_jplayer_1").jPlayer("play");
			$("#currenttitle").text(data.realname);
			$("#currentartist").text(data.artist);
			
			$('tbody:eq(1) tr:eq('+ index +')').addClass("ui-selected");	//select playing file
		});
}

function loadFirst(){
	index = 0;
	var ID = songs[index]["ID"];	//$('tbody:eq(1) tr:eq('+ index +')').attr("id");
	$.getJSON("/md5tofileinfo.php", {'ID': ID},
	function(data){
		$("#jquery_jplayer_1").jPlayer("setMedia", {
			mp3: data.filepath 			//modify to work with non mp3 files
		});
		$("#currenttitle").text(data.realname);
		$("#currentartist").text(data.artist);
	});
}

function update(){	//redraw songs table, update element bindings, run whenever songs change	
	$("#songtable tr").removeClass("ui-selected ui-selectee song");	//clear selection
	$("#songtable tr td").empty();	//clear existing data
	
	//set all table rows to new song info
	for(i=0;i<songs.length;i++){
		$("#songtable tr:eq("+(i+1)+")").addClass("song");
		$("#songtable tr:eq("+(i+1)+") td:eq(0)").html("<div class='playarrow'></div>" + songs[songOrder[i]]["realname"]);
		$("#songtable tr:eq("+(i+1)+") td:eq(1)").text(songs[songOrder[i]]["playtime"]);
		$("#songtable tr:eq("+(i+1)+") td:eq(2)").text(songs[songOrder[i]]["artist"]);
		$("#songtable tr:eq("+(i+1)+") td:eq(3)").text(songs[songOrder[i]]["album"]);
		$("#songtable tr:eq("+(i+1)+") td:eq(4)").text(songs[songOrder[i]]["genre"]);
	}

	$(".playarrow").click(function(){
		index = $("#songtable tbody").children().index($(this).parents("tr"));	//finds index of row in table
		playFile(songs[index]["ID"]);
		//alert(index);
	});	
}

function playlistInit(){
	//var uID = user cookie
	var uID = 1;	//debug
	$("#playlisttable tr").removeClass("playlist");	//clear selection
	$("#playlisttable tr td").empty();	//clear existing data
	$("#playlisttable tr:eq(1) td").html("+ Add New Playlist");
	$("#playlisttable tr:eq(1)").addClass("playlist");
	$("#playlisttable tr:eq(2) td").html("All Songs");
	$("#playlisttable tr:eq(2)").addClass("playlist");
	$.getJSON("/getPlaylists.php", {'uID': uID},
		function(data){
			playlists = data;
			for(i=0;i<data.length;i++){
				$("#playlisttable tr:eq("+(i+3)+")").addClass("playlist");
				$("#playlisttable tr:eq("+(i+3)+") td").html(playlists[i]["name"]);
			}
			
			$(".playlist td").click(function(){
				$(".playlistname").text($(this).text());
				var pIndex = $("#playlisttable tbody").children().index($(this).parent());	//finds index of row in table
				var pID;
				if(pIndex == 0 || pIndex == 1){ 
					pID = pIndex - 1;
				}else{ 
					pID = playlists[pIndex-2]["pID"];		//offset by the two dummy playlists
				}
				
				loadPlaylist(pID);	
			});
		});
}

function shuffleInit(shuffleOrUndo){		//call every time we need a new shuffle order
	for(var i=0;i<curSongTotal;i++){		//constructs new consecutive array
		songOrder[i] = i;
	}
	
	if(shuffleOrUndo){						//randomizes with current index as first element
		songOrder.slice(index,index+1);
		songOrder.shuffle();
		songOrder.unshift(index);
	}
	update();
}

function playNext(){		//repeatType 0 -> repeat all, 1 -> repeat one, 2 -> repeat none
	$('tbody:eq(1) tr:eq('+ index +')').removeClass("ui-selected");
	if(repeatType != 1){
		if(index + 1 >= curSongTotal){
			if(repeatType == 0){
				index = 0;
			}else if(repeatType == 2){
				loadFirst();
				return;
			}
		}else{
			index++;
		}
	}

	playFile(songs[songOrder[index]]["ID"]);
}

function playPrev(){
	$('tbody:eq(1) tr:eq('+ index +')').removeClass("ui-selected");
	if(repeatType != 1){
		if(index - 1 < 0){
			if(repeatType == 0){
				index = curSongTotal - 1;
			}else if(repeatType == 2){
				loadFirst();
				return;
			}
		}else{
			index--;
		}
	}

	playFile(songs[songOrder[index]]["ID"]);
}

function loadPlaylist(pID){
	if(pID != -1){	//all songs (0) or playlist (1+)
		$.getJSON("/pIDtosongID.php", {'pID': pID},
			function(data){
				songs = data;
				curSongTotal = songs.length;
				(shuffleType == 1) ? shuffleInit(true) : shuffleInit(false);
				loadFirst();
			});
	}else{	//pID = -1, Add New Playlist
		newPlaylist();
	}
}

function newPlaylist(){		//Turn playlist box into list of songs with button for adding all selected songs
	var newplaylist = [];
	$('#playlist').slideUp('slow', function() {
		$('#newplaylist').slideDown('slow');
		
		$("#playlistback").click(function(){
			$('#newplaylist').slideUp('slow', function() {
				$('#playlist').slideDown('slow');
			});
		});
													
		$("#addselected").click(function(){//get names of selected rows and show them in new playlist dialog, also push ids to array
			if(!($(".ui-selected").attr("id"))){
				alert("No Songs Selected.");
				return 0;
			}
			$(".ui-selected").each(function(index){
				newplaylist.push($(this).attr("id"));
				$("#selectedlist").append("<li>"+$(this).find("td:eq(0)").text()+"</li>");
			});
		});
	
		$("#saveselected").click(function(){
			if(!($("#playlistname").val())){ //if no name, error, ask for name
				alert("Please Enter a Playlist Name.");
				return 0;
			}
			
			if(newplaylist.length < 1){
				alert("No Songs Selected.");
				return 0;
			}
			
			var name = $("#playlistname").val();
			JSON.stringify(newplaylist);
			$.ajax({
				type: "POST",
				url: "/newplaylist.php",
				datatype: "json",						   
				data: {'newplaylist': newplaylist, 'name': name},
				success: function(msg){	//reinstate playlist area after giving success message
					$('#newplaylist').fadeTo('slow', 0.3).delay(500).slideUp('slow', function(){
						$('#playlist').slideDown('slow');
					});
				}
			});
		});
	});
}

Array.prototype.shuffle = function (){
    var i = this.length, j, temp;
    if ( i == 0 ) return;
    while ( --i ) {
        j = Math.floor( Math.random() * ( i + 1 ) );
        temp = this[i];
        this[i] = this[j];
        this[j] = temp;
    }
};

function getCookie(c_name){
	if (document.cookie.length>0){
		c_start=document.cookie.indexOf(c_name + "=");
		if (c_start!=-1){
			c_start=c_start + c_name.length+1;
			c_end=document.cookie.indexOf(";",c_start);
			if (c_end==-1) c_end=document.cookie.length;
			return unescape(document.cookie.substring(c_start,c_end));
		}
	}
	return "";
}
//})();