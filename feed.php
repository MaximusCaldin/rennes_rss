<?php
header("Content-type: text/xml; charset=utf-8");
$API_BASE_URL = "https://metropole.rennes.fr/";
$ITEMS_LIMIT = 20;

$content = file_get_contents(
    $API_BASE_URL . "articles?maxResultsByPage=" . $ITEMS_LIMIT,
    false,
    stream_context_create(array("ssl" => array('cafile' => './cert.pem'))),
);


if (!$content) {
    http_response_code(404);
    print("Error");
    return;
}
$data = json_decode($content, $assoc = true);

print('<?xml version="1.0" encoding="UTF-8"?>');
?>
<rss version="2.0">
    <channel>
        <title>Ici Rennes Off</title>
        <description>Flux non officiel d'Ici Rennes</description>
        <link>https://metropole.rennes.fr/</link>
        <lastBuildDate><?php echo (new DateTime())->format("r") ?></lastBuildDate>

        <?php foreach ($data["articles"] as $article) { ?>
            <item>
                <title><?php echo $article["title"] ?></title>
                <link><?php echo $article["url"] ?></link>
                <pubDate><?php echo (new DateTime())->setTimestamp($article["date"])->format("r") ?></pubDate>
                <description></description>
            </item>
        <?php } ?>
    </channel>
</rss>