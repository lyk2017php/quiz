<?php


function redirectIfNotStarted()
{
	if( ! isset($_SESSION['started_at']) ) {
		header("Location: index.php");
		die();
	}
}

function checkIfAnswerSubmitted($con)
{
	if( isset($_POST['question_id']) AND isset($_POST['answer']) ) {
		$answeredQuestionId = $_POST['question_id'];
		$answerId = $_POST['answer'];
	// bu sayfaya bir yanıt gelmiş
		//	yanıtı gönderilen sorunun id'sini yanıtlananlar listesine ekleyelim
		$_SESSION['answered_questions'][] = $answeredQuestionId;
	//yanıtı kontrol edelim
		// yanıt doğruysa
			// SESSION'daki doğru yanıt sayısını 1 arttıralım

		$isCorrect = $con->query("SELECT id FROM questions WHERE id = $answeredQuestionId AND answer_id = $answerId")->rowCount();

		if($isCorrect == 1) {
			$_SESSION['correct_answers_count']++;
		}

	}
}

function getRandomQuestion($con)
{
	$answeredQuestionsListString = implode(",", $_SESSION['answered_questions']);

	$getRandomQuestion = $con->query("SELECT * FROM questions WHERE id NOT IN ($answeredQuestionsListString) ORDER BY RAND() LIMIT 1");

	$question = $getRandomQuestion->fetchObject();

	return $question;
}


function endQuiz()
{
	$_SESSION['completed_at'] = time();
	header("Location: result.php");
	die();
}




