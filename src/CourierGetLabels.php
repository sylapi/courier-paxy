<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Exception;
use GuzzleHttp\Exception\ClientException;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Paxy\Helpers\ResponseErrorHelper;
use Sylapi\Courier\Paxy\Responses\Label as LabelResponse;
use Sylapi\Courier\Contracts\LabelType as LabelTypeContract;
use Sylapi\Courier\Contracts\CourierGetLabels as CourierGetLabelsContract;
use Sylapi\Courier\Responses\Label as ResponseLabel;

class CourierGetLabels implements CourierGetLabelsContract
{
    const API_PATH = '/labels/print';

    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getLabel(string $trackingId, LabelTypeContract $labelType): ResponseLabel
    {
        try {
            $stream = $this->session
                ->client()
                ->request(
                    'POST',
                    self::API_PATH,
                    ['json' => [
                        'trackingNrs' => [
                            $trackingId
                        ],
                    ]]
                );

            $result = $stream->getBody()->getContents();

            return new LabelResponse((string) $result);

        } catch (ClientException $e) {
            throw new TransportException(ResponseErrorHelper::message($e));
        } catch (Exception $e) {
            throw  new TransportException($e->getMessage(), $e->getCode());
        }
    }
}
