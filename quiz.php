<?php
require_once "inc/init.php";
// Bu dosya quiz sistemimizin tüm işleyişinin barındırıldığı dosya olacak

redirectIfNotStarted();

checkIfAnswerSubmitted($connection);

//	sıradaki soruyu getirip ekranda göstermemiz lazım
$question = getRandomQuestion($connection);

//	yeni soru alamadığımıza göre tamamı yanıtlanmış, testi sonlandıralım
if( ! $question ) endQuiz();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Soru</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<div class="col-md-6 col-md-offset-3">
			<form method="post" class="form">
				<h1><?=$question->body?></h1>
				<p><small><?=count($_SESSION['answered_questions'])?> / <?=$_SESSION['total_questions_count']?></small></p>
				<?php foreach($connection->query("SELECT * FROM question_options WHERE question_id = $question->id ORDER BY RAND()") as $option ): ?>
					<input type="radio" name="answer" value="<?=$option['id']?>" id="opt<?=$option['id']?>">
					<label for="opt<?=$option['id']?>"><?=$option['body']?></label><hr>
				<?php endforeach; ?>
				<input type="hidden" name="question_id" value="<?=$question->id?>">
				<button type="submit" class="btn btn-primary btn-lg btn-block">Yanıtla</button>
			</form>
		</div>
	</div>
</body>
</html>







