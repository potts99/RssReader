<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rss Reader</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style>
            body { font-size: 170%; }
            a:visited { color: #a3bcd1; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                <?php
                    function getContent() {
                        $file = "./feed-cache.txt";
                        $current_time = time();
                        $expire_time = 5 * 60;
                        $file_time = filemtime($file);
                        if(file_exists($file) && ($current_time - $expire_time < $file_time)) {
                            return file_get_contents($file);
                        }
                        else {
                            $content = getFreshContent();
                            file_put_contents($file, $content);
                            return $content;
                        }
                    }
                    function getFreshContent() {
                        $html = "";
                        $newsSource = array(
                            array(
                                "title" => "BBC",
                                "url" => "http://feeds.bbci.co.uk/news/world/rss.xml"
                            ),
                            array(
                                "title" => "BBC Cricket",
                                "url" => "http://newsrss.bbc.co.uk/rss/sportonline_uk_edition/cricket/rss.xml"
                            ),
                            array(
                                "title" => "SideBar.io",
                                "url" => "https://sidebar.io/feed.xml"
                            ),
                        );
                        function getFeed($url){
                            $html = "";
                            $rss = simplexml_load_file($url);
                            $count = 0;
                            $html .= '<ul>';
                            foreach($rss->channel->item as$item) {
                                $count++;
                                if($count > 7){
                                    break;
                                }
                                $html .= '<li><a href="'.htmlspecialchars($item->link).'">'.htmlspecialchars($item->title).'</a></li>';
                            }
                            $html .= '</ul>';
                            return $html;
                        }
                        foreach($newsSource as $source) {
                            $html .= '<h2>'.$source["title"].'</h2>';
                            $html .= getFeed($source["url"]);
                        }
                        return $html;
                    }
                    print getContent();
                ?>
                </div>
            </div>
        </div>
    </body>
</html>
