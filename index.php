<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>File List</title>
<link rel="stylesheet" href="stylesheets/styles.css">
</head>
<body>

<p class="heading">File List:</p>

<?PHP
    
    // output file list in HTML TABLE format
    function getFileList($dir) {
        // array to hold return value
        $retval = array();
        // add trailing slash if missing
        if (substr($dir, -1) != "/") $dir .= "/";
        
        // open pointer to directory and read list of files
        $d = @dir($dir) or die("getFileList: Failed opening directory $dir for reading");
        
        while(false !== ($entry = $d->read())) {
            // skip hidden files
            if($entry[0] == ".") continue;
            if(is_dir("$dir$entry")) {
                $retval[] = array(
                                  "name" => "$dir$entry/",
                                  "type" => filetype("$dir$entry"),
                                  "size" => 0,
                                  "lastmod" => filemtime("$dir$entry")
                                  );
            } elseif(is_readable("$dir$entry")) {
                $retval[] = array(
                                  "name" => "$dir$entry",
                                  "type" => mime_content_type("$dir$entry"),
                                  "size" => filesize("$dir$entry"),
                                  "lastmod" => filemtime("$dir$entry")
                                  );
            }
        }
        
        $d->close();
        return $retval;
    }

    $dirlist = getFileList(".");
    
    foreach ($dirlist as $file) {
        if ($file['type'] != 'application/jar') continue;
        
        echo "<div class=\"card\">\n";
        echo "<p class=\"text\"><a href=\"{$file['name']}\">", basename($file['name']),"</a></p>\n";
        echo "<p class=\"text\">File Type: {$file['type']}</p>\n";
        echo "<p class=\"text\">File Size: {$file['size']}</p>\n";
        echo "<p class=\"text\">File Modify Date: ", date('r', $file['lastmod']),"</p>\n";
        echo "</div>";
    }

    ?>

</body>
</html>
