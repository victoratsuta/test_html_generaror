<?php


namespace App\Services;


class IerarhieBuilder
{
    /**
     * @var array
     */
    private $products;
    /**
     * @var array
     */
    private $groups;

    public function __construct(array $products, array $groups)
    {
        $this->products = $products;
        $this->groups = $this->setIdAsKey($groups);
    }

    public function build()
    {
        $rootGroup = $this->getRootGroup();
        return $this->setGroupParents($rootGroup);

    }

    private function setGroupParents($rootGroup, $format = null, $depth = 1)
    {

        $rootGroup['depth'] = $depth;

        foreach ($this->groups as &$group) {
            if ($group['parent'] === $rootGroup['id']) {

                $inheritFormat = $rootGroup['inherit'] ? $rootGroup['format'] : null;
                $rootGroup['child'][] = $this->setGroupParents($group, $inheritFormat, $depth + 1   );

            }
        }

        if ($products = $this->getProductsOfGroup($rootGroup['id'])) {

            $format = $rootGroup['format'] ?: $format;
            $rootGroup['products'] = $this->addDescriptionToProduct($products, $format);
        }

        return $rootGroup;

    }

    private function getProductsOfGroup(string $groupId)
    {

        $products = [];
        foreach ($this->products as $product) {
            if ($product['category'] == $groupId) {
                $products[] = $product;
            }
        }

        return $products;
    }

    private function getRootGroup()
    {
        foreach ($this->groups as $group) {
            if (!$group['parent']) {
                return $group;
            }
        }
        throw new \Exception('No root group was found');
    }

    private function addDescriptionToProduct(array $products, $format): array
    {
        foreach ($products as &$product) {
            $description = $format;
            foreach ($product as $key => $value) {
                $description = str_replace("%" . $key . "%", $value, $description);
            }

            $description = preg_replace('/' . preg_quote('%') . '.*?' . preg_quote('%') . '/', 'UNDEFINED', $description);

            $product['description'] = $description;
        }

        return $products;
    }

    private function setIdAsKey(array $array)
    {
        foreach ($array as $item) {
            $array[$item['id']] = $item;
        }
        return $array;
    }

}
