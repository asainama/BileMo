<?php

namespace App\Representation;

use JMS\Serializer\Annotation as Serializer;
use Pagerfanta\Pagerfanta;

class PhonesRepresentation extends AbstractRepresentation
{
    /**
     * @Serializer\Type("array<App\Entity\Phone>")
     * @Serializer\Groups("list")
     */
    public $data;
    /**
     * @Serializer\Groups("list")
     */
    public $meta;

    public function __construct(PagerFanta $data)
    {
        $this->data = $data->getCurrentPageResults();
        $this->addMeta('limit', $data->getMaxPerPage());
        $this->addMeta('current_items', count($data->getCurrentPageResults()));
        $this->addMeta('total_items', $data->getNbResults());
        $this->addMeta('offset', $data->getCurrentPageOffsetStart());
    }
}
