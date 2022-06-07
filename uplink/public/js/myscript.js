// Global variable
var current_user_id = null;
var current_username = "";
var current_firstname = "";
var current_lastname = "";
var current_unlocked = null;
var current_right1 = null;
var current_right2 = null;

var current_list_user = new Array();

var add_right = 0; // Not open the box

function login_check()
{
	var frm = document.form_login;
	if ( frm.usn.value == '' || 
         frm.pwd.value == '' )
	{
		alert("Please input username and password!");
        return;
    }
    else {
        frm.submit();
    }
}

function viewUser(id)
{
	// Hide edit form
	$("#edit_information_form").hide();
	// cancel current edit
	onCancelEdit();
	
	$.post('/uplink/public/application/list/get-user-info',{user_id: id}, 
	function(data)
	{
		data = msgToArray(data);
		
		// Set current user details
		current_user_id = data["id"];
		current_username = data["username"];
		current_firstname = data["first_name"];
		current_lastname = data["last_name"];
		current_unlocked = data["unlocked"];
		current_right1 = data["right1"];
		current_right2 = data["right2"];

		
		// Display data
		$("#user_information_form").show();
		
		// Show option
		//$("#div_edit_info").show();
		document.getElementById('display_info_username').innerHTML=data['username'];
		document.getElementById('display_info_first_name').innerHTML=data['first_name'];
		document.getElementById('display_info_last_name').innerHTML=data['last_name'];
		
		// Right Unlocked
		if(data['unlocked'] == 1)
			txt = 'true'
		else 
			txt = 'false';
		document.getElementById('display_info_unlocked').innerHTML=txt;
		
		// Right Right1
		if(data['right1'] == 1)
			txt = 'true'
		else 
			txt = 'false';
		document.getElementById('display_info_right1').innerHTML=txt;
		
		// Right Right2
		if(data['right2'] == 1)
			txt = 'true'
		else 
			txt = 'false';
		document.getElementById('display_info_right2').innerHTML=txt;
	}
	);
}
//1 dimensional array
function msgToArray(msg)
{
    var json = new Array();
    var strErrList = msg.split("~012345678901234567890123456789~"); 
    for(var i = 0; i < strErrList.length; i++ )
    {
        var item = strErrList[i].split(":012345678901234567890123456789:");     
        var key = item[0];      
        var value = item[1];        
        json[key] = value;
    }   
    return json;
}
// 2 dimensional array converting
function msgToArray2( strData )
{
    var retArray = new Array();
    var splitStr = strData.split("~ABCDEFGHIJKLMNOPQRSTUVWXYZ~");

    for( var i=0; i < splitStr.length; i++ )
    {
        var element = splitStr[i].split(":ABCDEFGHIJKLMNOPQRSTUVWXYZ:");
        var key = element[0];
        var value = element[1];
		if(value != null)
			value = msgToArray( value );
        retArray[key] = value;
    }
    return retArray;
}

function onCancel()
{
	// Hide user information part
	$("#user_information_form").hide();
	//$("#div_edit_info").hide() ;
	
	// Reset current_user_id
	current_user_id = null;
	current_username = "";
	current_firstname = "";
	current_lastname = "";
	current_unlocked = null;
	current_right1 = null;
	current_right2 = null;

}

function onOpenAddForm()
{
	$("#add_new_user_form").show();
}
function onCancelAddNew()
{
	$("#add_new_user_form").hide();
	// reset add_right=0, close Add Rights part
	add_right = 0;
	$("#add_new_user_rights").hide();
	
	// reset error message
	document.getElementById("error_username").innerHTML         = "";
	document.getElementById("error_password").innerHTML         = "";
	document.getElementById("error_password_confirm").innerHTML = "";
	document.getElementById("error_firstname").innerHTML        = "";
	document.getElementById("error_lastname").innerHTML         = "";
	
	// reset form
	$("#new_username").val("");
	$("#new_password").val("");
	$("#new_password_confirm").val("");
	$("#new_firstname").val("");
	$("#new_lastname").val("");
	
	// reset rights
	$("#new_unlocked").removeAttr('checked');
	$("#new_right1").removeAttr('checked');
	$("#new_right2").removeAttr('checked');
}
function onAddRights()
{
	if(add_right == 0)
	{
		$("#add_new_user_rights").show();
		// Set status open add_right
		add_right = 1;
	}
	else
	{
		// add_right is already open, close it
		$("#add_new_user_rights").hide();
		// reset add_right
		add_right = 0;
	}
}
function onAddUser()
{
	var new_username         = $("#new_username").val();
	var new_firstname        = $("#new_firstname").val();
	var new_lastname         = $("#new_lastname").val();
	var new_unlocked         = 0;
	var new_right1           = 0;
	var new_right2           = 0;
	var new_password         = $("#new_password").val();
	var new_password_confirm = $("#new_password_confirm").val();
	
	if($('#new_unlocked').is(':checked'))
	{
		new_unlocked = 1;
	}
	if($('#new_right1').is(':checked'))
	{
		new_right1 = 1;
	}
	if($('#new_right2').is(':checked'))
	{
		new_right2 = 1;
	}

	//Check confirm password
	if(!checkConfirmPassword(new_password, new_password_confirm))
	{
		// Display error
		document.getElementById("error_password_confirm").innerHTML = "Password not match";
		return;
	}
	else
	{
		// Call addAction
		$.post('/uplink/public/application/list/add',
			{new_username: new_username,
			 new_firstname: new_firstname,
			 new_lastname: new_lastname,
			 new_unlocked: new_unlocked,
			 new_right1: new_right1,
			 new_right2: new_right2,
			 new_password: new_password}, 
			function(data)
			{
				if(data == 'success')
				{
					// Add successfully
					alert("User added successfully!");
					clearAddForm();
					// Reload list
					showList();
					return;
				}
				else
				{
					// Error occurs
					var error_msg = msgToArray2(data);
					// Display error, if any
					// Username
					if(error_msg['error']['user.username'])
					{
						document.getElementById("error_username").innerHTML = error_msg['error']['user.username'];
					}
					// Password
					if(error_msg['error']['user.password'])
					{
						document.getElementById("error_password").innerHTML = error_msg['error']['user.password'];
					}
					// First name
					if(error_msg['error']['user.first_name'])
					{
						document.getElementById("error_firstname").innerHTML = error_msg['error']['user.first_name'];
					}
					// Last name
					if(error_msg['error']['user.last_name'])
					{
						document.getElementById("error_lastname").innerHTML = error_msg['error']['user.last_name'];
					}			
				}
			}
		);
	}
}
function checkConfirmPassword(pwd1, pwd2)
{
	if(pwd1 == pwd2)
	{
		return true;
	}
	return false;
}
function clearAddForm()
{
	// reset form
	$("#new_username").val("");
	$("#new_password").val("");
	$("#new_password_confirm").val("");
	$("#new_firstname").val("");
	$("#new_lastname").val("");
	
	// reset rights
	$("#new_unlocked").removeAttr('checked');
	$("#new_right1").removeAttr('checked');
	$("#new_right2").removeAttr('checked');
	
	// reset error message
	document.getElementById("error_username").innerHTML         = "";
	document.getElementById("error_password").innerHTML         = "";
	document.getElementById("error_password_confirm").innerHTML = "";
	document.getElementById("error_firstname").innerHTML        = "";
	document.getElementById("error_lastname").innerHTML         = "";
}
//****************************************************//
// EDIT USER
//****************************************************//
function onEdit()
{
	// Show edit fields
	$("#edit_information_form").show();
	// Load data
	$("#input_edit_username").val(current_username);
	$("#input_edit_firstname").val(current_firstname);
	$("#input_edit_lastname").val(current_lastname);
	// Checkbox
	if(current_unlocked == 1)
		$("#input_edit_unlocked").click();
	if(current_right1 == 1)
		$("#input_edit_right1").click();
	if(current_right2 == 1)
		$("#input_edit_right2").click();
	// Hide form display information
	$("#div_edit_info").hide();
	$("#user_information_form").hide();
}

function onCancelEdit()
{
	// Hide user information part
	$("#edit_information_form").hide();
	
	// Reset current_user_id
	current_user_id = null;
	current_username = "";
	current_firstname = "";
	current_lastname = "";
	current_unlocked = null;
	current_right1 = null;
	current_right2 = null;
	
	//clear checkbox
	if($('#input_edit_unlocked').is(':checked'))
	{
		//uncheck it
		$('#input_edit_unlocked').prop('checked', false);
	}
	if($('#input_edit_right1').is(':checked'))
	{
		//uncheck it
		$('#input_edit_right1').prop('checked', false);
	}
	if($('#input_edit_right2').is(':checked'))
	{
		//uncheck it
		$('#input_edit_right2').prop('checked', false);
	}
	
	// Clear error
	document.getElementById("error_edit_username").innerHTML = "";
	document.getElementById("error_edit_firstname").innerHTML = "";
	document.getElementById("error_edit_lastname").innerHTML = "";
}
function onEditSave()
{
	// Get data
	var edit_userid			  = current_user_id;
	var edit_username         = $("#input_edit_username").val();
	var edit_firstname        = $("#input_edit_firstname").val();
	var edit_lastname         = $("#input_edit_lastname").val();
	var edit_unlocked         = 0;
	var edit_right1           = 0;
	var edit_right2           = 0;
	
	if($('#input_edit_unlocked').is(':checked'))
	{
		edit_unlocked = 1;
	}
	if($('#input_edit_right1').is(':checked'))
	{
		edit_right1 = 1;
	}
	if($('#input_edit_right2').is(':checked'))
	{
		edit_right2 = 1;
	}
	// Call Action
	$.post('/uplink/public/application/list/edit',
			{edit_userid: edit_userid,
			 edit_username: edit_username,
			 edit_firstname: edit_firstname,
			 edit_lastname: edit_lastname,
			 edit_unlocked: edit_unlocked,
			 edit_right1: edit_right1,
			 edit_right2: edit_right2}, 
		function(data)
		{
			if(data == 'success')
			{
				// Add successfully
				alert("User updated successfully!");
				// Show update information
				viewUser(current_user_id);
				// Clear edit form
				clearEditForm();
				// Reload list
				showList();
				return;
			}
			else
			{
				// Error occurs
				var error_msg = msgToArray2(data);
				// Display error, if any
				// Username
				if(error_msg['error']['user.username'])
				{
					document.getElementById("error_edit_username").innerHTML = error_msg['error']['user.username'];
				}
				// First name
				if(error_msg['error']['user.first_name'])
				{
					document.getElementById("error_edit_firstname").innerHTML = error_msg['error']['user.first_name'];
				}
				// Last name
				if(error_msg['error']['user.last_name'])
				{
					document.getElementById("error_edit_lastname").innerHTML = error_msg['error']['user.last_name'];
				}			
			}
		}
	);
}
function clearEditForm()
{
	document.getElementById("error_edit_username").innerHTML = "";
	document.getElementById("error_edit_firstname").innerHTML = "";
	document.getElementById("error_edit_lastname").innerHTML = "";
	//clear checkbox
	if($('#input_edit_unlocked').is(':checked'))
	{
		//uncheck it
		$('#input_edit_unlocked').prop('checked', false);
	}
	if($('#input_edit_right1').is(':checked'))
	{
		//uncheck it
		$('#input_edit_right1').prop('checked', false);
	}
	if($('#input_edit_right2').is(':checked'))
	{
		//uncheck it
		$('#input_edit_right2').prop('checked', false);
	}
}
function onDelete()
{
	// delete current_user_id
	if(confirm("Are you sure you want to delete "+current_firstname+" "+ current_lastname))
	{
		// Delete item
		$.post('/uplink/public/application/list/delete',{user_id: current_user_id}, 
			function(data)
			{
				var msg = msgToArray(data);
				if(msg['success_delete'].length > 0)
					alert(msg['success_delete'])
				else
					alert(msg['error_delete']);
				
				// Hide form
				onCancel();
				// Reload list
				showList();
				return;
			}
		);
	}
	return;
}

function showList()
{
	// Get json list
	$.post('/uplink/public/application/list/get-json-list',{},
		function(data)
		{
			var strHTML = "";
			// convert to array
			listUser = msgToArray2(data);
			// Set current list user
			current_list_user = listUser;
			for(i=0; i<listUser.length; i++)
			{
				// Tag li
				strHTML += "<li class='ui-state-default'>";
				// User info
				var user = listUser[i];
				// name
				strHTML += "<font color='#228B22'><b>";
				strHTML += user['first_name'];
				strHTML += "&nbsp;";
				strHTML += user['last_name'];
				strHTML += "</b></font><br>";
				// username
				strHTML += "<font color='#006699'><i>";
				strHTML += user['username'];
				strHTML += "</i></font><br>";
				// right unlocked
				strHTML += "<font color='#1c1c1c'><b>Unlocked</b>=";
				if(user['unlocked'] == 1)
					strHTML += 'true'
				else strHTML += 'false';
				strHTML += "</font><br>";
				// right right1
				strHTML += "<font color='#1c1c1c'><b>Right1</b>=";
				if(user['right1'] == 1)
					strHTML += 'true'
				else strHTML += 'false';
				strHTML += "</font><br>";
				// right right2
				strHTML += "<font color='#1c1c1c'><b>Right2</b>=";
				if(user['right2'] == 1)
					strHTML += 'true'
				else strHTML += 'false';
				strHTML += "</font><br>";
				
				// End tag li
				strHTML += "</li>";
			}
			
			// Display data
			$("#list_user_part").show();
			document.getElementById('selectable').innerHTML = strHTML;
		}
	);
}

function onLogOut()
{
	// Reset data
	current_user_id = null;
	current_username = "";
	current_firstname = "";
	current_lastname = "";
	current_unlocked = null;
	current_right1 = null;
	current_right2 = null;
	add_right = 0;
	// Call function logOut
	$.post('/uplink/public/application/list/logout',{},
		function(data)
		{
			window.location.href="/uplink/public";
		}
	);
}

function splitResult(msg)
{
	var ret = msg.split("##");
	return ret;
}