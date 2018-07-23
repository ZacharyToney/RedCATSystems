<?php
/**
 * Template Name: Red Cat Exercise
 *
 * @package WordPress
 * @subpackage Divi
 * @since Divi 1.0
 */

?>

<?php
if (!empty($_POST)) {
	$csvFile = $_FILES['customFile']['tmp_name'];
	$csvArray = array_map('str_getcsv', file($csvFile));
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.18/af-2.3.0/b-1.5.2/cr-1.5.0/kt-2.4.0/r-2.2.2/sl-1.2.6/datatables.min.css"/>

    <title>Exercise</title>
  </head>

  <form method="post" enctype="multipart/form-data" class="col-md-6 p-3">
		
		<div class="custom-file">
		  <input type="file" class="custom-file-input" id="customFile" name="customFile">
		  <label class="custom-file-label" for="customFile">Choose file</label>
		  <div class="pt-1">
		  	<input type="submit" name="submit" value="Submit" class="btn btn-primary">
			</div>
		</div>


  </form>

  <body class="container">
  	<div class="col-md-8">
  		<br />

  		<?php 
  			if (!empty($_POST)) {
  				?>
			  		<div class="col-md-12" id="addColumnButtonDiv">
			  			<button class="col-md-3 btn btn-primary" id="addColumnButton">Add Column</button>
			  		</div>

			  		<div class="col-md-12 add-column-section" style="display:none;">
				  		<label>Column Name:</label>
				  		<input type="text" class="form-control col-md-4" name="columnNameTextbox" id="columnNameTextbox">
				  		<label>Select Column You Want in The Formula:</label>
			  		  <select class="custom-select" id="columnHeaders">	    					
	    					<?php 
	    					for ($i=0; $i < count($csvArray[0]); $i++) 
	    						{ 
	    							echo '<option value="'.$i.'"">'.$csvArray[0][$i].'</option>';
	    						}	
	    					?>
	  					</select>
	  					<br><br>
	  					<button id="addColumnToFormula" class="btn btn-primary">Add Column to Formula</button>
				  		<br><br>
				  		<button id="plus" class="btn operators">+</button>
	    				<button id="minus" class="btn operators">-</button>
	    				<button id="divide" class="btn operators">/</button>
	    				<button id="multiply" class="btn operators">*</button>
	    				<br><br>
	    				<div id="formula"></div><br>
				  		<button type="button" class="btn btn-primary" id="insertNewColumn">Insert New Column</button>
			  		</div>
			  		<br />


  				<?php
  			}
  		?>

			<table id="table_id" class="display">
			    <thead>
			        <tr>
			        	<?php 
			        	for ($i=0; $i < count($csvArray[0]); $i++) { 
			        		echo '<th class="dataTableHeader">'.$csvArray[0][$i].'</th>';
			        	}
			        	?>
			        </tr>
			    </thead>
			    <tbody>
			    		<?php
			    		for ($i=1; $i < count($csvArray); $i++) { 
			    			echo '<tr>';
			    			for ($x=0; $x < count($csvArray[$i]); $x++) { 
			    				echo '<td class="dataForTable">'.$csvArray[$i][$x].'</td>';
			    			}
			    			echo '</tr>';
			    		}
			    		?>
			    </tbody>
			</table>
		</div>

		<div class="javascript resources">
	    <!-- Optional JavaScript -->
	    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
	    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
	    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.18/af-2.3.0/b-1.5.2/cr-1.5.0/kt-2.4.0/r-2.2.2/sl-1.2.6/datatables.min.js"></script>
    </div>



    <script type="text/javascript" class="tableAndPageFunctionality">
    	
			//Initilize datatable framework to table
			$(document).ready( function () {
			    $('#table_id').DataTable();
			} );

			//Change text when file is uploaded
			$('.custom-file-input').on('change', function() { 
			   let fileName = $(this).val().split('\\').pop(); 
			   $(this).next('.custom-file-label').addClass("selected").html(fileName); 
			});

			//Add new Column edit information when add column button is clicked
			$( "#addColumnButton" ).click(function() {
			  $(".add-column-section").css("display","block");
			  $("#addColumnButtonDiv").css("display","none");
				});

			var formulatext='';
			//So I'm thinking add the id of the element for the header then add the symbol to the array too
			var formulaItemTextOrSymbolArray = [];
			//This array is just for display purposes for the user
			var formulaItemIdOrSymbolArray = [];

			var lastElement = '';

			$("#addColumnToFormula").click(function(){

				if ((lastElement.localeCompare('+') == 0) || (lastElement.localeCompare('-') == 0) || (lastElement.localeCompare('/') == 0) || (lastElement.localeCompare('*') == 0) || (formulaItemTextOrSymbolArray.length == 0))
				{
					var headerId = $( "#columnHeaders :selected" ).val();
					var headerText = $( "#columnHeaders :selected" ).text();
					formulaItemIdOrSymbolArray.push(headerId);
					formulaItemTextOrSymbolArray.push(headerText);
					lastElement = headerText;
					formulaText = $("#formula").text();
					$("#formula").empty();
					$("#formula").append('<p>'+formulaText+headerText+'</p>');
					console.log(formulaItemIdOrSymbolArray);
				}
				else
				{
					window.alert("Please add a symbol to your formula.");
				}
			});

			$("#plus").click(function(){
				if ((lastElement.localeCompare('+') != 0) && (lastElement.localeCompare('-') != 0) && (lastElement.localeCompare('/') != 0) && (lastElement.localeCompare('*') != 0) && (formulaItemTextOrSymbolArray.length != 0)) 
				{
					formulaItemIdOrSymbolArray.push("+");
					formulaItemTextOrSymbolArray.push("+");
					lastElement = '+';
					formulaText = $("#formula").text();
					$("#formula").empty();
					$("#formula").append('<p>'+formulaText+"+"+'</p>');
					console.log(formulaItemIdOrSymbolArray);
				}
				else
				{
					window.alert("Please add a column first.");
				}
			});

			$("#minus").click(function(){
				if ((lastElement.localeCompare('+') != 0) && (lastElement.localeCompare('-') != 0) && (lastElement.localeCompare('/') != 0) && (lastElement.localeCompare('*') != 0) && (formulaItemTextOrSymbolArray.length != 0))  
				{
					formulaItemIdOrSymbolArray.push("-");
					formulaItemTextOrSymbolArray.push("-");
					formulaText = $("#formula").text();
					lastElement = '-';
					$("#formula").empty();
					$("#formula").append('<p>'+formulaText+"-"+'</p>');
					console.log(formulaItemIdOrSymbolArray);
				}
				else
				{
					window.alert("Please add a column first.");
				}
			});

			$("#multiply").click(function(){
				if ((lastElement.localeCompare('+') != 0) && (lastElement.localeCompare('-') != 0) && (lastElement.localeCompare('/') != 0) && (lastElement.localeCompare('*') != 0) && (formulaItemTextOrSymbolArray.length != 0))  
				{
					formulaItemIdOrSymbolArray.push("*");
					formulaItemTextOrSymbolArray.push("*");
					lastElement = '*';
					formulaText = $("#formula").text();
					$("#formula").empty();
					$("#formula").append('<p>'+formulaText+"*"+'</p>');
					console.log(formulaItemIdOrSymbolArray);
				}
				else
				{
					window.alert("Please add a column first.");
				}
			});

			$("#divide").click(function(){
				if ((lastElement.localeCompare('+') != 0) && (lastElement.localeCompare('-') != 0) && (lastElement.localeCompare('/') != 0) && (lastElement.localeCompare('*') != 0) && (formulaItemTextOrSymbolArray.length != 0)) 
				{
					formulaItemIdOrSymbolArray.push("/");
					formulaItemTextOrSymbolArray.push("/");
					lastElement = '/';
					formulaText = $("#formula").text();
					$("#formula").empty();
					$("#formula").append('<p>'+formulaText+"/"+'</p>');
					console.log(formulaItemIdOrSymbolArray);
					
				}
				else
				{
					window.alert("Please add a column first.");
				}
			});

			$("#insertNewColumn").click(function(){
				var columnNameTextbox = $("#columnNameTextbox").val();
				console.log(columnNameTextbox);
				columnNameTextbox = columnNameTextbox.replace(/\s/g, '');
				console.log(columnNameTextbox);
				if (columnNameTextbox === '') 
				{
					window.alert('Please fill out a column name');
				}
				else if (formulaItemTextOrSymbolArray.length < 1) 
				{
					window.alert('Please add elements to the formula');
				}
				else if((lastElement.localeCompare('+') === 0) || (lastElement.localeCompare('-') === 0) || (lastElement.localeCompare('/') === 0) || (lastElement.localeCompare('*') === 0))
				{
					window.alert('The last element in the formula can not be a symbol');
				}
				else if ((lastElement.localeCompare('+') != 0) && (lastElement.localeCompare('-') != 0) && (lastElement.localeCompare('/') != 0) && (lastElement.localeCompare('*') != 0) && (formulaItemTextOrSymbolArray.length > 2)) 
				{
					//success
					window.alert('This is a successful string');
				}
			});
    </script>

  </body>
</html>