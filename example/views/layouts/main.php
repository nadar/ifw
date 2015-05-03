<?php
    $menu = ['test/index/index', 'test/index/sub', 'test/sub/index', 'test/sub/sub', 'news/index/index'];
    
    $asset = \app\assets\ExampleFiles::register($this);

    //var_dump($asset->getBaseDir());
    ?>
<html>
<head>
    <title>ifw test</title>
    <?= $this->head(); ?>
</head>
<body>
<ul>
    <?php foreach($menu as $item): ?>
        <li><a href="index.php?route=<?php echo $item; ?>"><?php echo $item; ?></a>
    <?php endforeach; ?>
</ul>
<hr />
<?php echo $content; ?>
<hr />
FOOTER
<?= $this->endBody(); ?>
</body>
</html>