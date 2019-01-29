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
