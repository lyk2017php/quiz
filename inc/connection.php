<?php

//	bu dosyada PDO objemizi oluşturacağız ki gereken yerlerde gerektiği zaman çağırabilelim ve gerekeni yapalım (beyin bedava ya)

$connection = new PDO(
	"mysql:host=localhost;dbname=lyk2017_quiz;charset=utf8",
	"root",
	"root"
	);