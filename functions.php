<?php

/**
 * Class Colors
 */
class Colors
{
    private $foreground_colors = [];
    private $background_colors = [];

    public function __construct()
    {
        // Set up shell colors
        $this->foreground_colors["black"]        = "0;30";
        $this->foreground_colors["dark_gray"]    = "1;30";
        $this->foreground_colors["blue"]         = "0;34";
        $this->foreground_colors["light_blue"]   = "1;34";
        $this->foreground_colors["green"]        = "0;32";
        $this->foreground_colors["light_green"]  = "1;32";
        $this->foreground_colors["cyan"]         = "0;36";
        $this->foreground_colors["light_cyan"]   = "1;36";
        $this->foreground_colors["red"]          = "0;31";
        $this->foreground_colors["light_red"]    = "1;31";
        $this->foreground_colors["purple"]       = "0;35";
        $this->foreground_colors["light_purple"] = "1;35";
        $this->foreground_colors["brown"]        = "0;33";
        $this->foreground_colors["yellow"]       = "1;33";
        $this->foreground_colors["light_gray"]   = "0;37";
        $this->foreground_colors["white"]        = "1;37";

        $this->background_colors["black"]      = "40";
        $this->background_colors["red"]        = "41";
        $this->background_colors["green"]      = "42";
        $this->background_colors["yellow"]     = "43";
        $this->background_colors["blue"]       = "44";
        $this->background_colors["magenta"]    = "45";
        $this->background_colors["cyan"]       = "46";
        $this->background_colors["light_gray"] = "47";
    }

    // Returns colored string

    /**
     * @param $string
     * @param null $foreground_color
     * @param null $background_color
     * @return string
     */
    public function setColoredString($string, $foreground_color = null, $background_color = null)
    {
        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .= $string . "\033[0m";

        return $colored_string;
    }
}

/**
 * Class Main
 */
class Main
{
    /**
     * @param $dir
     * @param string $extension
     * @param array $results
     * @return array
     */
    public static function getDirContents($dir, $extension = "html", &$results = [])
    {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);

            if (!is_dir($path)) {
                if (empty($extension) || preg_match("/\." . $extension . "$/", $path)) {
                    $results[] = $path;
                }
            } elseif ($value != "." && $value != "..") {
                self::getDirContents($path, $extension, $results);
            }
        }

        return $results;
    }

    /**
     * @param $param
     * @return string
     */
    public static function errorMissingParamMessage($param)
    {
        $colors = new Colors();

        return $colors->setColoredString("--{$param} param is empty!", "light_red", "black") . PHP_EOL;
    }

    /**
     * @param $message
     */
    public static function errorMessage($message)
    {
        $colors = new Colors();

        echo $colors->setColoredString($message, "light_red", "black") . PHP_EOL;
    }

    /**
     * @param $message
     */
    public static function notificationMessage($message)
    {
        $colors = new Colors();

        echo $colors->setColoredString($message, "light_green", "black") . PHP_EOL;
    }

    /**
     * @param $filePath
     * @param $destination
     * @param string $search
     * @param array $queryAdd
     */
    public static function copyAndManipulateFile($filePath, $destination, $search = '', $queryAdd = [])
    {
        $path = pathinfo($destination);
        if (!file_exists($path["dirname"])) {
            self::notificationMessage($path['dirname']);
            mkdir($path["dirname"], 0777, true);
        }

        $html  = file_get_contents($filePath);
        $links = self::urlTransformFromHtmlFile($html, $search, $queryAdd);

        $htmlTransformed = $html;
        if ($links) {
            foreach ($links as $link) {
                $quoteOriLink    = "\"{$link['ori']}\"";
                $quoteChangeLink = "\"{$link['change']}\"";

                $htmlTransformed = str_ireplace($quoteOriLink, $quoteChangeLink, $htmlTransformed);
            }
        }

        file_put_contents($destination, $htmlTransformed);
    }

    /**
     * @param $html
     * @param string $search
     * @param array $queryAdd
     * @return array
     */
    public static function urlTransformFromHtmlFile($html, $search = '', $queryAdd = [])
    {
        $dom = new DOMDocument();

        $dom->loadHTML($html);

        $links = $dom->getElementsByTagName("a");

        $linkTransform = [];
        $i             = 0;
        foreach ($links as $link) {
            $href = $link->getAttribute("href");

            if (strpos($href, $search) !== false) {
                $linkTransform[$i]['ori'] = $href;
                self::notificationMessage($href);
                parse_str(parse_url($href, PHP_URL_QUERY), $queryArray);

                $queryArray = array_merge($queryArray, $queryAdd);
                $url        = parse_url($href);
                $scheme     = @$url['scheme'];
                $host       = @$url['host'];
                $path       = @$url['path'];
                $query      = http_build_query($queryArray);

                $linkTransform[$i]['change'] = "{$scheme}://{$host}{$path}?{$query}";
                $i++;
            }
        }

        return $linkTransform;
    }
}
