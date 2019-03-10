<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="search.css">
</head>

<header id="header">
    検索結果
</header>

<body id="body">

<a href="search_entry.html">戻る</a>

<br>
<br>
<?php
$booksArray = getArrayBookInfo();
$currentPage = $_GET['IndexSequence'];
$totalItem = $booksArray['totalItems'];
?>
ページ <?php echo $currentPage.' / '.allPage($totalItem) ?>
<br>
<br>

<?php
if ($totalItem == 0){
    echo '該当なし';
    return;
}
?>

<?php foreach ($booksArray['items'] as $item) : ?>
    <?php
    $info = $item['volumeInfo'];
    $title = $info['title'];
    $author = '';
    ?>

    <div class="book_lists">

        <details class="details">
            <summary class="summary"><?php echo $title ?></summary>

            著者
            <br>
            <?php if(array_key_exists('authors', $info)) : ?>
                <?php foreach ($info['authors'] as $authorValue) : ?>
                    <a href='search.php?Keyword=inauthor:<?php echo $authorValue.'&Index='.getIndex(true) ?>' name='keyword'> <?php echo $authorValue?> </a>
                    <br>
                <?php endforeach; ?>
            <?php else: ?>
                該当なし
                <br>
            <?php endif; ?>

            <br>

            <?php if(array_key_exists('categories', $info)) : ?>
                <?php foreach ($info['categories'] as $categoriesValue) : ?>
                    カテゴリー
                    <br>
                    <a href='search.php?Keyword=subject:<?php echo $categoriesValue.'&Index='.getIndex(true) ?>' name='keyword'> <?php echo $categoriesValue?> </a>
                <?php endforeach; ?>
            <?php else: ?>
                カテゴリー
                <br>
                該当なし
            <?php endif; ?>

            <br>
            <br>

            <?php if(array_key_exists('publishedDate', $info)) : ?>
                出版日
                <br>
                <?php echo $info['publishedDate']?>
            <?php else: ?>
                出版日
                <br>
                該当なし
            <?php endif; ?>

            <br>
            <br>

            <?php if(array_key_exists('description', $info)) : ?>
                詳細
                <br>
                <?php echo $info['description']?>
            <?php else: ?>
                詳細
                <br>
                該当なし
            <?php endif; ?>

            <br>
            <br>

            <?php if(array_key_exists('infoLink', $info)) : ?>
                Google link
                <br>
                <a href= '<?php echo $info['infoLink']?>' target="_blank"><?php echo $info['infoLink']?></a>
            <?php else: ?>
                GoogleStoreUrl
                <br>
                該当なし
            <?php endif; ?>

        </details>

    </div>

<?php endforeach; ?>

</body>

<br>

<?php if (validateNextPage($currentPage, $totalItem)) return; ?>

<footer id="footer">
    <?php $next = 'Keyword='.$_GET['Keyword'].'&Index='.getIndex(false) ?>
    <a href='search.php?<?php echo $next ?>'>次へ</a>
</footer>

<?php

function getArrayBookInfo(){

    // なんらか別のAPIと連携させてみたい

    $base_url = 'https://www.googleapis.com/books/v1/volumes?q='.$_GET['Keyword'].'&langRestrict=ja&startIndex='.$_GET['Index'].'&maxResults=20';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $base_url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    $decoded = json_decode($response, true);

    return $decoded;
}

function getIndex($reset){

    if($reset){
        $IndexSequence = 1;
        $vale = 0;
    }else{
        $IndexSequence = $_GET['IndexSequence'] + 1;
        $vale = $IndexSequence * 20;
    }

    return $vale.'&IndexSequence='.$IndexSequence;
}

function validateNextPage($currentPage, $totalItems){
    $nextItem = ($currentPage + 1) * 20;
    return ($totalItems - $nextItem) <= 0;
}

function allPage($totalItems){

    $value = floor($totalItems / 20);
    $value = $value == 0 ? 1 : $value;
    return $value;
}

?>

</html>