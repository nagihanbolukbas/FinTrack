<?php
session_start();

date_default_timezone_set("Europe/Istanbul");

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}



require_once "config/database.php";
require_once "fpdf/fpdf.php";

$user_id = $_SESSION["id"];
/* Kullanıcı bilgileri */

$stmt = $pdo->prepare("
SELECT first_name,last_name,email
FROM users
WHERE id=?
");

$stmt->execute([$user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);



/* Toplam gelir */

$stmt = $pdo->prepare("
SELECT IFNULL(SUM(amount),0) AS total
FROM incomes
WHERE user_id=?
");

$stmt->execute([$user_id]);

$totalIncome = $stmt->fetch()["total"];



/* Toplam gider */

$stmt = $pdo->prepare("
SELECT IFNULL(SUM(amount),0) AS total
FROM expenses
WHERE user_id=?
");

$stmt->execute([$user_id]);

$totalExpense = $stmt->fetch()["total"];



/* Bakiye */

$balance = $totalIncome - $totalExpense;



/* Hedef sayısı */

$stmt = $pdo->prepare("
SELECT COUNT(*) AS total
FROM goals
WHERE user_id=?
");

$stmt->execute([$user_id]);

$goalCount = $stmt->fetch()["total"];



/* Son 10 işlem */

$stmt = $pdo->prepare("
SELECT
title,
amount,
income_date AS tdate,
'Gelir' AS type
FROM incomes
WHERE user_id=?

UNION ALL

SELECT
title,
amount,
expense_date AS tdate,
'Gider' AS type
FROM expenses
WHERE user_id=?

ORDER BY tdate DESC
LIMIT 10
");

$stmt->execute([
    $user_id,
    $user_id
]);

$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetTitle("FinTrack Rapor");

$pdf->SetFont('Arial','B',20);
$pdf->Cell(0,15,iconv('UTF-8','windows-1254','FinTrack Finans Raporu'),0,1,'C');

$pdf->SetFont('Arial','',11);
$pdf->Cell(
    0,
    8,
    iconv('UTF-8','windows-1254','Olusturulma Tarihi : '.date("d.m.Y H:i")),
    0,
    1,
    'R'
);

$pdf->Ln(5);

$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,iconv('UTF-8','windows-1254','Kullanici Bilgileri'),0,1);

$pdf->SetFont('Arial','',12);

$pdf->Cell(45,8,iconv('UTF-8','windows-1254','Ad Soyad'));
$pdf->Cell(
    0,
    8,
    iconv('UTF-8','windows-1254',$user["first_name"]." ".$user["last_name"]),
    0,
    1
);

$pdf->Cell(45,8,iconv('UTF-8','windows-1254','E-Posta'));
$pdf->Cell(
    0,
    8,
    iconv('UTF-8','windows-1254',$user["email"]),
    0,
    1
);

$pdf->Ln(8);

$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,iconv('UTF-8','windows-1254','Finans Ozeti'),0,1);

$pdf->SetFont('Arial','',12);

$pdf->Cell(70,10,'Toplam Gelir');
$pdf->Cell(0,10,'TL '.number_format($totalIncome,2,",","."),0,1);

$pdf->Cell(70,10,'Toplam Gider');
$pdf->Cell(0,10,'TL '.number_format($totalExpense,2,",","."),0,1);

$pdf->Cell(70,10,'Guncel Bakiye');
$pdf->Cell(0,10,'TL '.number_format($balance,2,",","."),0,1);

$pdf->Cell(70,10,'Hedef Sayisi');
$pdf->Cell(0,10,$goalCount,0,1);

$pdf->Ln(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Son Islemler',0,1);

$pdf->SetFont('Arial','B',11);

$pdf->Cell(55,10,'Baslik',1);
$pdf->Cell(35,10,'Tur',1);
$pdf->Cell(45,10,'Tarih',1);
$pdf->Cell(55,10,'Tutar',1);
$pdf->Ln();
$pdf->SetFont('Arial','',10);

foreach($transactions as $item){

    $pdf->Cell(
        55,
        10,
        utf8_decode($item["title"]),
        1
    );

    $pdf->Cell(
        35,
        10,
        utf8_decode($item["type"]),
        1
    );

    $pdf->Cell(
        45,
        10,
        date("d.m.Y",strtotime($item["tdate"])),
        1
    );

    $pdf->Cell(
        55,
        10,
        number_format($item["amount"],2,",",".")." TL",
        1
    );

    $pdf->Ln();
}
$pdf->Ln(10);

$pdf->SetFont('Arial','I',10);

$pdf->Cell(
    0,
    8,
    utf8_decode("Bu rapor FinTrack tarafindan otomatik olusturulmustur."),
    0,
    1,
    'C'
);

$pdf->Output("I","FinTrack-Rapor.pdf");
exit;