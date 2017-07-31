<?php
require_once "inc/init.php";
//	test tamamlanmış, sonuçları bu sayfada göstereceğiz

if( ! isset($_SESSION['completed_at']) ) {
	header("Location: quiz.php");
	die();
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sonucunuz</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<div class="col-md-6 col-md-offset-3">
			<h1>Sonucunuz</h1>
			<p><?=$_SESSION['total_questions_count']?> sorudan <strong><?=$_SESSION['correct_answers_count']?></strong> tanesini doğru yanıtladınız</p>
			<p>Testi toplamda <?=($_SESSION['completed_at']-$_SESSION['started_at'])?> saniyede tamamladınız</p>
			<a href="destroy.php" class="btn btn-primary btn-lg btn-block">Başa Dön</a>
		</div>
	</div>
</body>
</html>