<?php


namespace App\Services;


class HtmlBuilder
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): string
    {
        $template = "<ul>";
        $template .= "<li>";
        $template .= $this->generate($this->data);
        $template .= "</li>";
        $template .= "</ul>";
        return $template;
    }

    private function generate($group): string
    {

        $template = "";

        $template .= $this->getGroupTemplate($group["name"], $group["depth"]);
        $template .= $this->addTabs($group["depth"]) ."<ul>";

        if (is_array($group["products"])) {
            foreach ($group["products"] as $product) {
                $template .= $this->getProductTemplate($product["description"], $group["depth"]);
            }
        }

        if (isset($group["child"])) {


            foreach ($group["child"] as $groupChild){
                $template .= $this->addTabs($group["depth"]) . "<li>";
                $template .= $this->generate($groupChild);
                $template .= $this->addTabs($group["depth"]) . "</li>";
            }

        }

        $template .= $this->addTabs($group["depth"]) . "</ul>";


        return $template;

    }

    private function getGroupTemplate($name, $index): string
    {
        return $this->addTabs($index) . "<h" . $index . ">" . $name . "</h" . $index . ">";
    }

    private function getProductTemplate($description, $index): string
    {
        return $this->addTabs($index) . "<li><b>" . $description . "</b></li>";
    }

    private function addTabs($index): string
    {
        return str_repeat("	", $index);
    }

}
