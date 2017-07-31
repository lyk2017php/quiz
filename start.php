<?php
require_once "inc/init.php";
/**
*	Yeni bir kullanıcı QUİZ'e başladığında tüm tanımlamaları ve oturum başlangıcını burada yapacağız
*/

// started_at
// total_questions
// correct_answers_count
// answered_questions

//	yeni oturum başlatıyoruz


if(! isset($_SESSION['started_at'])) {
	$_SESSION['started_at'] = time();
	$_SESSION['total_questions_count'] = $connection->query("SELECT id FROM questions")->rowCount();
	$_SESSION['correct_answers_count'] = 0;
	$_SESSION['answered_questions'] = [0];
}

header("Location: quiz.php");