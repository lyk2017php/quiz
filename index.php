<?php

/**
*	Sorularını, seçeneklerini ve doğru seçenek bilgisini sakladığımız
*	ziyaretçilerin sırasıyla yanıtlayıp bitince sonuçlarını görüntüleyebileceği
*	bir quiz sistemi yazma niyeti içindeyiz
*/

/**
*	Her şeyden önce sorularımızı, seçeneklerini ve doğru yanıtlarını saklamak için uygun bir yöntem bulmalıyız
*	Ardından ziyarete gelen her kullanıcı bir karşılama metni ile karşılayıp, bilgilendirme yapıp, BAŞLA düğmesi ile testi başlatabiliriz
*	Her seferinde bir soru görmek suretiyle yanıtlanan soruların ardından da ilgili kullanıcının test sonucunu göstermeliyiz
*	Kullanıcı dilerse TEKRAR düğmesine basarak teste tekrar başlayabilir
*/

require_once "inc/init.php";

$questionsCount = $connection->query("SELECT COUNT(id) as count FROM questions")->fetch();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Quize Hoşgeldiniz</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<div class="col-md-6 col-md-offset-3">
			<h1>Teste hoş geldiniz</h1>
			<p>Az sonra karşınıza <?=$questionsCount['count']?> adet soru çıkacak</p>
			<p>Testi tamamlamanızın ardından sonuçlarınızı görüntüleyebileceksiniz.</p>
			<a href="start.php" class="btn btn-primary btn-lg btn-block">Teste Başla</a>
		</div>
	</div>
</body>
</html>






