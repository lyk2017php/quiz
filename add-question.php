<?php
require "inc/init.php";
/**
*	veritabanımıza yeni soru eklemek için bo dusyayı kullanacağız
*	normalde doğrudan form gözükecek
*	aynı sayfaya form post edilirse ekleme işlemi yapılacak
*/

$alertBoxClass = "alert-info";

if($_POST) {
	//	eğer sayfaya post ile veri geldiyse bu veri yeni eklenecek sorunun bilgilerini tanımlar
	//	bu bilgileri kullanarak yeni sorumuzun ekleme işlemini yapalım
	// die(var_dump($_POST));


	//	öncelikle sorumuzu veritabanına ekleyeceğiz, bu ekleme işleminde yalnızca soru metnini göndereceğiz

	//	ardından gelen yanıt seçeneklerini bir döngüye sokarak veritabanına ekleyeceğiz
		//	eğer işlenmekte olan dizi elemanının indisi, POST'tan gelen correct'e eşit ise bu seçeneğin doğru yanıt olduğunu bileceğiz
		//	bu seçenek eklendiği zaman ID'sini alıp daha önceden veritabanımıza eklemiş olduğumuz soru satırına doğru yanıtın ID'si olarak güncelleyeceğiz
	
	try{
		//	birden fazla veritabanı sorgusunu tek bir işlem olarak başlatıyoruz
		$connection->beginTransaction(); 

		$addQuestionQuery = $connection->prepare("INSERT INTO questions (body) VALUES (?)");

		$questionBody = trim(strip_tags($_POST['question_body']));

		$isQuestionAdded = $addQuestionQuery->execute(array($questionBody));

		$questionId = $connection->lastInsertId();

		$rightAnswerId = NULL;

	//	her bir seçeneği eklemek için sorgumuzu hazırlıyoruz
		$addQuestionOptionQuery = $connection->prepare("INSERT INTO question_options (question_id, body) VALUES (?, ?)");

	//	POST'tan gelen yanıtları döngüye sokup her birini ekleyeceğiz
		foreach ($_POST['question_option'] as $key => $value) {

		//	hazırlanan sorguya parametre olarak ilgili seçeneğin değerini gönderiyoruz ve çalıştırıyoruz
			$addQuestionOptionQuery->execute(array($questionId, $value));

		//	eğer bu seçenek, doğru olduğu belirtilen seçenek ise bu seçeneğin id değerini kenara yazıyoruz, çünkü sorunun doğru yanıtının id değeri olarak güncelleyeceğiz
			if($_POST['correct']==$key) {
				$rightAnswerId = $connection->lastInsertId();
			}
		}

	//	tüm yanıtlar eklendi, şimdi doğru yanıtın ID'sini sorunun yanına yazalım

	//	UPDATE questions SET answer_id = $rightAnswerId WHERE id = $questionId

		$update = $connection->exec("UPDATE questions SET answer_id = $rightAnswerId WHERE id = $questionId");

		//	buraya kadar hiçbir sorun çıkmadıysa yapılan veritabanı sorgularının veritabanına işlenmesini sağlıyoruz
		$connection->commit();
		$alertBoxClass = "alert-success";
		$message = "Sorunuz eklendi";

	} catch( PDOException $e ) {
		//	Eğer bir PDO istisnası yaşandıysa (hata çıktıysa) bu hataya değin yapılan veritabanı değişikliklerini geri sarıyoruz
		$connection->rollback();
		$alertBoxClass = "alert-danger";
		$message = "Sorunuz EKLENEMEDİ. Demin girdiğiniz verileri de uçurduk. KB KANK :)()()<br>Hata mesajı da şöyleydi: ". $e->getMessage();
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Yeni Soru Ekle</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<div class="col-md-6 col-md-offset-3">
		<?php if(isset($message)): ?> 
		<br>
			<div class="alert <?=$alertBoxClass?>">
				<?=$message?>
			</div>
		<?php endif; ?>
			<h1>Yeni Soru Ekle</h1>
			<form class="form" method="POST">
				<textarea name="question_body" class="form-control" rows="2" placeholder="Sorunuzu yazın"></textarea>
				<hr>
				<h2>Seçenekler</h2>
				<?php for($i=0; $i<4; $i++): ?>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon">
								<input type="radio" name="correct" value="<?=$i?>">
							</span>
							<input type="text" class="form-control" name="question_option[<?=$i?>]" id="opt<?=$i?>">
							<span class="input-group-addon btn btn-danger clear-text" data-inp="opt<?=$i?>">
								<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							</span>
						</div>
					</div>
				<?php endfor; ?>
				<!-- <a class="btn btn-default">Seçenek Ekle</a> -->
				<button class="btn btn-primary">Soruyu Ekle</button>
			</form>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script>
		// belge hazır olduğunda etkinlikleri tanımlayalım
		$(document).ready(function(){
			//	
			$(".clear-text").click(function(e){
				$("#" + $(this).data('inp')).val("");
			});
		});
	</script>
</body>
</html>