<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model;

use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\Cache\Frontend\Decorator\TagScope;

class Cache extends TagScope
{
    const TYPE_IDENTIFIER = 'reactedge';
    const CACHE_TAG = 'REACTEDGE_CACHE_TAG';

    public function __construct(FrontendPool $cacheFrontendPool)
    {
        parent::__construct($cacheFrontendPool->get(self::TYPE_IDENTIFIER), self::CACHE_TAG);
    }
}
