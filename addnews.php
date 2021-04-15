<?php

require_once "../../../conf.php"; //kus asuvad serveri info
// echo $server_host;

$news_error = null;
$news_title = null;
$news_content =null;
//var_dump($_POST); //on olemas ka $_GET

	if(isset($_POST["news_submit"]))
		{	
			if(empty($_POST["news_title_input"]))
				{
					$news_error="Puudub uudise pealkiri! ";
				}
			else
			{
				$news_title=$_POST["news_title_input"];
			}	
			if(empty($_POST["news_content_input"]))
				{
					$news_error.="Uudise tekst puudu! ";
				}
			else
			{
				$news_content=$_POST["news_content_input"];
			}
			if(empty($news_error))//salvestame andmebaasi
				{
					store_news($_POST["news_title_input"],$_POST["news_content_input"],$_POST["news_author_input"]);
					$news_title = null;
					$news_content =null;

				}

		}

		function store_news($news_title,$news_content,$news_author)
		{
			//echo $news_title.$news_content.$news_author;
			//echo $GLOBALS["server_host"] // väljaspool functioni olevaid muutujaid ei näe
			//loome andmebaasi ja serveriga ühenduse
			$conn = new mysqli($GLOBALS["server_host"],$GLOBALS["server_user_name"],$GLOBALS["server_password"],$GLOBALS["database"]);
			//m22rame suhtluseks kodeeringu
			$conn->set_charset("utf-8");
			// valmistan SQL käsu
			$stmt = $conn->prepare("INSERT INTO VR2021_news(VR2021_news_title,VR2021_news_content,VR2021_news_author)VALUES(?,?,?)");
			echo $conn->error;
			//i-integer s-string d- decimal
			$stmt->bind_param("sss",$news_title,$news_content,$news_author);
			$stmt->execute();
			$stmt->close();
			$conn->close();
		}
		

		if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$news_title_input = test_input($_POST["news_title_input"]);
			$news_content_input = test_input($_POST["news_content_input"]);	
		}
	



		function test_input($data)
		 {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
         }

?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
	<h1>
	Uudisete lisamine
	</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="news_title_input">Uudise pealkiri</label>
		<br>
		<input type="text" id="news_title_input" name="news_title_input" placeholder="Pealkiri" value="<?php echo $news_title?>">
		<br>
		<label for="news_content_input"> Uudise tekst </lable>
		<br>
		<textarea id="news_content_input" name="news_content_input" placeholder="Uudise tekst" row="6" cols="40"><?php echo $news_content?></textarea>
		<br>
		<label for="news_author_input">Uudise lisaja</label>
		<br>
		<input type="text" id="news_author_input" name="news_author_input" placeholder="Nimi">
		<br>
		<input type="submit" name="news_submit" value="Salvesta uudis">
		<p><?php echo $news_error; ?></p>
</body>
</html>