<?php
session_start();

require_once 'connection.php';

$id_review = $_GET['id_review'];

// Считываем пост
$sql = "SELECT r.film_title, r.poster, r.trailer, r.text_review, r.date_added_review, u.name FROM reviews r LEFT JOIN users u ON r.id_user = u.id_user WHERE r.id_review = ?";
$statement = $pdo->prepare($sql);
$statement->bindValue(1, $id_review);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);

// Считываем комментарии
$sqlComments = "SELECT c.date_added_comment, c.comment_text, u.name FROM comments c LEFT JOIN users u ON c.id_user = u.id_user WHERE c.id_review = ? ORDER BY c.id_comment DESC";
$statementComment = $pdo->prepare($sqlComments);
$statementComment->bindValue(1, $id_review);
$statementComment->execute();
$resultComments = $statementComment->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style2.css">
    <title>Document</title>
</head>
<body>
    <?php require_once 'header.php'?>
    <section class="review">
        <div class="review-container">
            <div class="review-up">
                <div class="title"><?=$result['film_title']?></div>
                <div class="date"><?=$result['date_added_review']?></div>
            </div>
            <div class="block">
                <div class="trailer">
                    <?php if(empty($result['trailer'])):?>
                    <div class="noTrailer">Трейлер отсутствует</div>
                    <?php else:?>
                        <iframe width="730" height="410" src="https://www.youtube.com/embed/<?=$result['trailer']?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    <?php endif;?>
                    <div class="review_author">Автор: <?=$result['name']?></div>
                </div>
                <div class="poster">
                    <img src="<?=$result['poster']?>" alt="">
                </div>
            </div>
            <div class="review_text"><?=$result['text_review']?></div>

            <?php if(isset($_SESSION['user'])):?>
            <form action="backend/createComment.php" method="POST" class="write_comment">
                <label for="text">Написать комментарий:</label>
                <textarea name="text" id="" rows="5"></textarea>
                <input type="hidden" name="id_review" value="<?=$_GET['id_review']?>">
                <div class="review-btn">
                    <button type="submit" class="submit_comment">Отправить</button>
                </div>
            </form>
            <?php endif;?>

            <p class="commentsTitle">Комментарии:</p>
            <div class="comments">
                <?php foreach($resultComments as $comment):?>
                <div class="comment">
                    <div class="block-up">
                        <div class="comment_author"><?=$comment['name']?></div>
                        <div class="date_comment"><?=$comment['date_added_comment']?></div>
                    </div>
                    <div class="text_comment"><?=$comment['comment_text']?></div>
                </div>
                <?php endforeach;?>
            </div>
        </div>
    </section>
</body>
</html>