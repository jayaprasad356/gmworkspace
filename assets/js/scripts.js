

function all() 
{
	// Ajax config
	$.ajax({
        type: "GET", //we are using GET method to get all record from the server
        url: 'all.php', // get the route value
        success: function (response) {//once the request successfully process to the server side it will return result here
            
            // Parse the json result
        	response = JSON.parse(response);

            var html = "";
            // Check if there is available records
            if(response.length) {
            	html += '<div class="list-group">';
	            // Loop the parsed JSON
	            $.each(response, function(key,value) {
	            	// Our employee list template
					html += '<a href="#" class="list-group-item list-group-item-action">';
					html += "<p>" + value.first_name +' '+ value.last_name + " <span class='list-email'>(" + value.email + ")</span>" + "</p>";
					html += "<p class='list-address'>" + value.address + "</p>";
					html += '</a>';
	            });
	            html += '</div>';
            } else {
            	html += '<div class="alert alert-warning">';
				  html += 'No records found!';
				html += '</div>';
            }

            

            // Insert the HTML Template and display all employee records
			$("#employees-list").html(html);
        }
    });
}

function submitForm() 
{
	$("#btnSubmit").on("click", function() {
		var $this 		    = $("#btnSubmit"); //submit button selector using ID
        var $caption        = $this.html();// We store the html content of the submit button
        var form 			= "#form"; //defined the #form ID
        var formData        = $(form).serializeArray(); //serialize the form into array
        var route 			= $(form).attr('action'); //get the route using attribute action

        // Ajax config
    	$.ajax({
	        type: "POST", //we are using POST method to submit the data to the server side
	        url: route, // get the route value
	        data: formData, // our serialized array data for server side
	        beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
	            $this.attr('disabled', true).html("Processing...");
	        },
	        success: function (response) {//once the request successfully process to the server side it will return result here
	            $this.attr('disabled', false).html($caption);

	            // Reload lists of employees
	            all();

	            // We will display the result using alert
	            alert(response);

	            // Reset form
	            resetForm();
	        },
	        error: function (XMLHttpRequest, textStatus, errorThrown) {
	        	// You can put something here if there is an error from submitted request
	        }
	    });
	});
}

function resetForm() 
{
	$('#form')[0].reset();
}


$(document).ready(function() {

	// Get all employee records
	all();

	// Submit form using AJAX
	submitForm();
	 
});