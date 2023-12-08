<?php

declare(strict_types=1);

const POSTS_PATH = 'data/posts.csv';

function read_posts(string $file_path): ?array
{
    $file = fopen($file_path, "r");
    if (!$file) return null;

    try {
        if (flock($file, LOCK_SH)) {
            $rows = [];
            while (true) {
                $data = fgetcsv($file);
                if (!$data) break;
                $rows[] = $data;
            }
            flock($file, LOCK_UN);
            return $rows;
        } else {
            return null;
        }
    } finally {
        fclose($file);
    }
}

function append_posts(string $file_path, string $name, string $content)
{
    $file = fopen($file_path, "a");
    if (!$file) return;

    try {
        if (flock($file, LOCK_EX)) {
            $timestamp = date('c');
            fputcsv($file, [$timestamp, $name, $content]);
            flock($file, LOCK_UN);
        } else {
            return;
        }
    } finally {
        fclose($file);
    }
}

function make_input_error(string $name, string $content): ?string
{
    if (strlen($name) === 0) {
        return '名前が入力されていません';
    } elseif (strlen($content) === 0) {
        return '内容が入力されていません';
    } elseif (strlen($name) > 20) {
        return '名前が長すぎます';
    } elseif (strlen($content) > 200) {
        return '内容が長すぎます';
    } else {
        return null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_name = htmlspecialchars($_POST['name'] ?? '');
    $input_content = htmlspecialchars($_POST['content'] ?? '');
    $input_content = str_replace(["\r\n", "\n", "\r"], '<br />', $input_content);
    $error_message = make_input_error($input_name, $input_content);
    if (is_null($error_message)) {
        append_posts(POSTS_PATH, $input_name, $input_content);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $input_name = htmlspecialchars($_GET['name'] ?? '');
}

$posts = read_posts(POSTS_PATH);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple BBS</title>
    <link rel="stylesheet" href="./css/sanitize.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header class="header">
        <h1 class="header__title">Simple BBS</h1>
        <nav class="header__nav">
            <ul class="header__nav-list">
                <li class="header__nav-item">
                    <a href="#" class="header__nav-link">Top</a>
                </li>
                <li class="header__nav-item">
                    <a href="#form" class="header__nav-link">Form</a>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="posts">
            <?php if (empty($posts)) : ?>
                <p class="posts__empty">投稿がありません。</p>
            <?php else : ?>
                <ol class="posts__list">
                    <?php foreach ($posts as $post) : ?>
                        <li class="posts__item">
                            <div class="posts__item-inner">
                                <div class="posts__item-header">
                                    <p class="posts__item-name"><?= $post[1] ?></p>
                                    <p class="posts__item-timestamp"><time datetime="<?= $post[0] ?>"><?= date_create($post[0])->format('Y/n/j G:i:s') ?></time></p>
                                </div>
                                <p class="posts__item-content"><?= $post[2] ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </div>

        <div class="form" id="form">
            <?php if (!is_null($error_message)) : ?>
                <p class="form__message"><?= $error_message ?></p>
            <?php endif; ?>
            <form action="index.php" method="post" class="form__inner">
                <div class="form__item">
                    <label for="form__name" class="form__label">名前</label>
                    <input type="text" required maxlength="20" value="<?= $input_name ?>" name="name" id="form__name" class="form__text-input">
                </div>
                <div class="form__item">
                    <label for="form__content" class="form__label">内容</label>
                    <textarea required maxlength="200" name="content" id="form__content" class="form__textarea"></textarea>
                </div>
                <div class="form__item">
                    <input type="submit" value="投稿" class="form__submit-button">
                </div>
            </form>
        </div>
    </main>
</body>

</html>