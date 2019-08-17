<?php


class Breadcrumb {
    /**
     * @var array
     */
    protected $breadcrumbList = [];

    protected $breadcrumbTemplate = "<a class=\"breadcrumb-item\" href=\"{LINK}\">{NAME}</a>";

    public function __construct()
    {
        $this->add("Home", "/");
    }

    public function add($name, $link = "/") {
        $this->breadcrumbList[] = (object)["name" => $name, "link" => $link];
    }

    public function generate() {
        $html = "";
        foreach ($this->breadcrumbList as $list) {
            $html .= str_replace(["{NAME}", "{LINK}"],[$list->name, $list->link], $this->breadcrumbTemplate);
        }
        return $html;
    }
}

$GLOBALS['breadcrumb'] = new Breadcrumb();
function Breadcrumb() {
    return $GLOBALS['breadcrumb'];
}
$GLOBALS['last_exec_time'] = microtime(true);
$GLOBALS['last_exec_count'] = 0;
function execTime() {
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    $GLOBALS['last_exec_count']++;
    $time = ((microtime(true)-$GLOBALS['last_exec_time']));
    echo "#".$GLOBALS['last_exec_count']." : ".number_format($time,7,".", "")." - ".str_replace("C:\\WebServer\\home\\kulersml\\public_html", '',$caller['file'])." : ".$caller['line']."<br>";
    $GLOBALS['last_exec_time'] = microtime(true);
}
