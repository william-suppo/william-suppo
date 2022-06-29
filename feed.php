<?php

$posts = fetchPosts('https://laravel-france.com/rss?author=William&limit=5');

$updatedReadme = feedReadme($posts);

writeReadme($updatedReadme);

function fetchPosts(string $url)
{
    $xml = file_get_contents($url);

    $dom = new DOMDocument();

    $dom->loadXML($xml);

    $items = $dom->getElementsByTagName('item');

    $posts = [];

    foreach ($items as $item) {
        $posts[] = [
            'title' => $item->getElementsByTagName('title')->item(0)->nodeValue,
            'link' => $item->getElementsByTagName('link')->item(0)->nodeValue,
        ];
    }

    return $posts;
}

function feedReadme(array $posts)
{
    $stub = file_get_contents('README.stub');

    $replace = array_map(function ($post) {
        return sprintf('+ [%s](%s)', $post['title'], $post['link']);
    }, $posts);

    return str_replace('{posts}', implode("\n", $replace), $stub);
}

function writeReadme(string $content)
{
    file_put_contents('README.md', $content);
}
