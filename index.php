<?php
	if(isset($_POST["address"]) && !is_null($_POST["address"])){
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => "https://viacep.com.br/ws/".addslashes($_POST["address"])."/xml/"
		]);

		$response = (String) mb_convert_encoding(htmlentities(curl_exec($curl)),"UTF-8");
		curl_close($curl);
	}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Test!</title>
  </head>
  <body>
  	<h1>Testing the Php 8</h1>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

	<?php
		error_reporting(0);
		$host = "Localhost";
		$user = "dev";
		$pass = "";
		$database = "";

		$conexao = mysqli_connect($host, $user, $pass, $database) or die("<p>Cann't connect with database.</p>");
		mysqli_set_charset($conexao, "utf8")  == true ? : die("Charset canno't be defined.");
		$sql = "SELECT * FROM users";
		$resultados = mysqli_query($conexao,$sql);
		echo "<table class=\"table table-dark table-hover\">";
		echo "<tr>";
		echo "<th>#</th>";
		echo "<th>EMAIL</th>";
		echo "<th>PASSWORD</th>";
		echo "<th>NAME</th>";
		echo "<th>SURNAME</th>";
		echo "<th>ADDRESS</th>";
		echo "<th>TELEPHONE</th>";
		echo "</tr>";
		if(mysqli_num_rows($resultados) > 0):
			foreach ($resultados as $key => $resultado):
				
				echo "<tr>";
					echo "<td>".$resultado["id"]."</td>";
					echo "<td>".$resultado["email"]."</td>";
					echo "<td>".$resultado["password"]."</td>";
					echo "<td colspan=\"2\">".$resultado["name"]. " ". $resultado["surname"]."</td>";
					echo "<td id=\"".$resultado["id"]."\" onclick=\"javascript:setAddress(this.id)\">".$resultado["address"]."</td>";
					echo "<td>".$resultado["telephone"]."</td>";
				echo "</tr>";
			endforeach;
		endif;
		echo "</table>";

		mysqli_free_result($resultados);
		mysqli_close($conexao);
	?>
	<script type="text/javascript">
		function capitalizeFirstLetter(string) {
		  return string.charAt(0).toUpperCase() + string.slice(1);
		}
		function setAddress(table){
			var table = $("#"+table);
			var address = table[0].innerText;
			document.getElementById("div-address").innerHTML = "<pre>" + address + "</pre>";
			var address = JSON.parse(String(table[0].innerText));
			document.getElementsByName("address")[0].value = address["cep"];
		}
		function getAddress(){
			var table = $("#div-address");
			if(table[0].innerHTML == "<br>"){
				window.confirm("Clique em um endereço listado na tabela primeiro, por favor.");
				return 0;
			}
			var address = JSON.parse(String(table[0].innerText));
			$.ajax({
				url: `https://viacep.com.br/ws/${address['cep']}/json/`,
				method: "GET",
				success: function(res){
					window.confirm(`CEP: ${res["cep"]} \nUF: ${res["uf"]}\nCidade: ${res["localidade"]}\nEndereço: ${capitalizeFirstLetter(address["rua"]) + " " + address["número"]}`);
				}
			})
		}
		
	</script>
	<hr></hr>
	<div id="div-address" style="background-color: #eeeeee; width: 30em;"><br/></div>
	<div id="buttons">
		<button class="btn btn-success" onclick="getAddress()">Get Address Info by Ajax.</button>
		<hr>
		<form action="#" method="POST" id="form-php">
			<input type="text" name="address" id="input-name" readonly="readonly"/>
		</form>
		<button type="submit" form="form-php" class="btn btn-success">Get Address Info by Php.</button><br>	
		<?php
		echo "<pre>";
		print_r($response);
		echo "</pre>"
		?>
	  
	</div>
	</body>
</html>
