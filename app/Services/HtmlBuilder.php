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
        return $this->generate([$this->data]);
    }

    private function generate($groups): string
    {

        $template = "";
        $template .= "<ul>";
        $template .= "<li>";

        foreach ($groups as $group) {

            $template .= $this->getGroupTemplate($group["name"], $group["depth"]);
            $template .= $this->addTabs($group["depth"]) ."<ul>";

            if (is_array($group["products"])) {
                foreach ($group["products"] as $product) {
                    $template .= $this->getProductTemplate($product["description"], $group["depth"]);
                }
            }

            if (isset($group["child"])) {
                $template .= $this->addTabs($group["depth"]) . "<li>";
                $template .= $this->generate($group["child"]);
                $template .= $this->addTabs($group["depth"]) . "</li>";
            }

            $template .= $this->addTabs($group["depth"]) . "</ul>";

        }

        $template .= "</li>";
        $template .= "</ul>";
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
