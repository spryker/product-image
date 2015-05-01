<?php

namespace SprykerFeature\Zed\FrontendExporter\Business\Exporter\KeyBuilder;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderTrait;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class KvMarkerKeyBuilder implements KeyBuilderInterface
{
    use KeyBuilderTrait;
    /**
     * @param string $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return $data . $this->keySeparator . 'timestamp';
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'kv-export';
    }
}
 