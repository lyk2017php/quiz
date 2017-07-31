<?php
require_once "inc/init.php";
/**
*	Bu dosya quiz sistemimizin tüm işleyişinin barındırıldığı dosya olacak
*	Öncelikle gelen talepten quiz'e yeni mi başlanmış yoksa 
*
*
*/

// talep geldiği zaman öncelikle bir oturum başlatılıp başlatılmadığını kontrol edeceğiz

if( ! isset($_SESSION['started_at']) ) {
	header("Location: index.php");
	die();
}

if( isset($_POST['question_id']) AND isset($_POST['answer']) ) {
	$answeredQuestionId = $_POST['question_id'];
	$answerId = $_POST['answer'];
	// bu sayfaya bir yanıt gelmiş
		//	yanıtı gönderilen sorunun id'sini yanıtlananlar listesine ekleyelim
	$_SESSION['answered_questions'][] = $answeredQuestionId;
	//yanıtı kontrol edelim
		// yanıt doğruysa
			// SESSION'daki doğru yanıt sayısını 1 arttıralım

	$isCorrect = $connection->query("SELECT id FROM questions WHERE id = $answeredQuestionId AND answer_id = $answerId")->rowCount();

	if($isCorrect == 1) {
		$_SESSION['correct_answers_count']++;
	}

}

//	sıradaki soruyu getirip ekranda göstermemiz lazım

//	SELECT * FROM questions WHERE id NOT IN (0) ORDER BY RAND() LIMIT 1

$answeredQuestionsListString = implode(",", $_SESSION['answered_questions']);

$getRandomQuestion = $connection->query("SELECT * FROM questions WHERE id NOT IN ($answeredQuestionsListString) ORDER BY RAND() LIMIT 1");


$question = $getRandomQuestion->fetch();

if( ! $question ) {
	//	soruların tamamı yanıtlanmış, testi sonlandıralım
	$_SESSION['completed_at'] = time();
	header("Location: result.php");
	die();
}


$questionId = (int)$question['id'];



// var_dump($al);

// die(var_dump($getRandomQuestion));
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
				<h1><?=$question['body']?></h1>
				<p><small><?=count($_SESSION['answered_questions'])?> / <?=$_SESSION['total_questions_count']?></small></p>
				<?php foreach($connection->query("SELECT * FROM question_options WHERE question_id = $questionId ORDER BY RAND()") as $option ): ?>
					<input type="radio" name="answer" value="<?=$option['id']?>" id="opt<?=$option['id']?>">
					<label for="opt<?=$option['id']?>"><?=$option['body']?></label><hr>
				<?php endforeach; ?>
				<input type="hidden" name="question_id" value="<?=$questionId?>">
				<button type="submit" class="btn btn-primary btn-lg btn-block">Yanıtla</button>
			</form>
		</div>
	</div>
</body>
</html>







