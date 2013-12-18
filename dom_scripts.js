
var login = false;
var statsShown = false;

j$(document).ready(function() {
        j$('.msg').stop().animate({backgroundColor:'#00AB66'}, 400).delay(3000).animate({backgroundColor:'#999'}, 400);
        j$('.err').stop().animate({backgroundColor:'#B00000'}, 400).delay(3000).animate({backgroundColor:'#999'}, 400);
});

// Loads scores when page is ready
// No parameters
// Returns void
j$(document).ready(function() {
	
	j$('#slideToStats').on('click',function() {
		showHideStats();
	});
	
	j$('#showhideregister').on('click',function() {
		showHideRegisterLogin();
	});
	
	refreshScores(false);
	focusButtonNewGame();
	
	j$.post("index.php",{getUserName:''}).done(function(data) {
		if (data != '') j$('#right').html(data);
	    });
	


	j$('#registerform').hide();
	j$('#stats').hide();
});
function showHideRegisterLogin() {
	if (!login) {
		j$('#loginform').slideUp(400, function() {j$('#registerform').slideDown(); });
		j$('#showhideregister').html('<i class=\"icon-lock\"></i>Login with your account');
	}
	else
	{
		j$('#registerform').slideUp(400, function() {j$('#loginform').slideDown(); });
		j$('#showhideregister').html('<i class=\"icon-edit\"></i> Don\'t have an account?');
	}
	login = !login;
}
function showHideStats() {
	if (!statsShown) {
		j$('#game').slideUp(400, function() {j$('#stats').slideDown(); });
		j$('#slideToStats').html('<i class=\"icon-chevron-up\"></i> Show game');
		updateStats();
	}
	else
	{
		j$('#stats').slideUp(400, function() {j$('#game').slideDown(); });
		j$('#slideToStats').html('<i class=\"icon-chevron-down\"></i> Show stats');
		//j$('#stats').html("");
	}
	statsShown = !statsShown;
}
// Focuses on "new game" button on DOM
// No parameters
// Returns void
function focusButtonNewGame() {
		j$(function() {
			j$("#newgame").focus(); 
		});
}

// Loads highscores into the page
// Parameters :
// r : message to display below the high scores table, if not specified, no message.
// Returns void
function refreshScores(r)
{
	j$.getJSON('index.php?refreshHighScores=', function(data) {
		if (data.length === 0) {
			j$('#scoresTable tbody').html("<tr><td colspan=2>no scores</td></tr>");
		}
		else {
			var rows = '';
			j$.each(data,function(i,record) {
				rows += '<tr>';
				j$.each(record, function(row,value)
					{
					if (row != "id") rows += '<td>' + value + '</td>';
					});
				rows+= '</tr>'
	});
	j$('#scoresTable tbody').html(rows);
	j$('#scoresTable tbody tr:first').css("background-color","#49E20E");
	}
	});
	if ( r ) {
		j$('p#refreshed').html("<strong>" + r + "</strong>");
		j$("#refreshed").show().delay(3000).fadeOut();
	}
}

function updateStats() {
j$(document).ready(function() {
	j$.get("index.php?getPieChart", function(data) {
			j$('#charts').html(data);
		});
		
	j$.get("index.php?getLineChart", function(data) { 
			j$('#charts').append(data);
	});
		
	j$.get("index.php?getNumberOfGamesPlayed", function(data) { 
			j$('span#numberOfGamesSpan').html(data);
	});
	
	j$.getJSON("index.php?getPlayerHighscoresJSON" , function(data) {
		var tbl_body = "";
		j$.each(data, function() {
			var tbl_row = "";
			j$.each(this, function(k , v) {
				tbl_row += "<td>"+v+"</td>";
			})
			tbl_body += "<tr>"+tbl_row+"</tr>";                 
		})
		j$("#tableHighScoresPlayers tbody").html(tbl_body);
	});
});
}

function displayHelp() {
	j$("#dialog-help").dialog(
	{
	modal: true,
	buttons: {
		Ok: function() {
			j$(this).dialog("close");
			}
		}
	});
}