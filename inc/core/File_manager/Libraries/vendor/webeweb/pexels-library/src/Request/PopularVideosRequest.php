<?php

/*
 * This file is part of the pexels-library package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Library\Pexels\Request;

use WBW\Library\Pexels\Response\AbstractResponse;
use WBW\Library\Pexels\Serializer\RequestSerializer;
use WBW\Library\Pexels\Serializer\ResponseDeserializer;
use WBW\Library\Traits\Integers\IntegerMaxDurationTrait;
use WBW\Library\Traits\Integers\IntegerMinDurationTrait;
use WBW\Library\Traits\Integers\IntegerMinHeightTrait;
use WBW\Library\Traits\Integers\IntegerMinWidthTrait;
use WBW\Library\Traits\Integers\IntegerPageTrait;
use WBW\Library\Traits\Integers\IntegerPerPageTrait;

/**
 * Popular videos request.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Library\Pexels\Request
 */
class PopularVideosRequest extends AbstractRequest {

    use IntegerMinDurationTrait;
    use IntegerMinHeightTrait;
    use IntegerMinWidthTrait;
    use IntegerMaxDurationTrait;
    use IntegerPageTrait;
    use IntegerPerPageTrait;

    /**
     * Popular videos resource path.
     *
     * @var string
     */
    const POPULAR_VIDEOS_RESOURCE_PATH = "/videos/popular";

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();

        $this->setPage(1);
        $this->setPerPage(self::PER_PAGE_DEFAULT);
    }

    /**
     * {@inheritdoc}
     */
    public function deserializeResponse(string $rawResponse): AbstractResponse {
        return ResponseDeserializer::deserializeVideosResponse($rawResponse);
    }

    /**
     * {@inheritdoc}
     */
    public function getResourcePath(): string {
        return self::POPULAR_VIDEOS_RESOURCE_PATH;
    }

    /**
     * {@inheritdoc}
     */
    public function serializeRequest(): array {
        return RequestSerializer::serializePopularVideosRequest($this);
    }
}
