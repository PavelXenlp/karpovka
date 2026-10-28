<?php

$source = file_get_contents(__DIR__ . '/../inc/article_related.php');
foreach (array('type="search"', 'type="checkbox"', 'data-kp-related-item', 'article_related_ids[]') as $required) {
    if (false === strpos($source, $required)) throw new RuntimeException('Related article picker is incomplete: ' . $required);
}
if (false !== strpos($source, '<select name="article_related_ids[]"')) {
    throw new RuntimeException('Legacy related article multiselect is still present.');
}

echo "Article related picker: OK\n";
