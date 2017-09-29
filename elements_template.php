<li>
    <a href="?category=<?=$category['id']?>"><?=$category['name']?></a>
    <?php if($category['childs']): ?>
        <ul>
            <?php echo categoriesToString($category['childs']); ?>
        </ul>
    <?php endif; ?>
</li>