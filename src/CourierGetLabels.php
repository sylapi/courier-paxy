<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Exception;
use GuzzleHttp\Exception\ClientException;
use Sylapi\Courier\Contracts\CourierGetLabels as CourierGetLabelsContract;
use Sylapi\Courier\Contracts\Label as LabelContract;
use Sylapi\Courier\Entities\Label;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Helpers\ResponseHelper;

class CourierGetLabels implements CourierGetLabelsContract
{
    const API_PATH = '/labels/print';

    private $session;

    public function __construct(PaxySession $session)
    {
        $this->session = $session;
    }

    public function getLabel(string $trackingId): LabelContract
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

            return new Label((string) $result);
        } catch (ClientException $e) {
            $exception = new TransportException(PaxyResponseErrorHelper::message($e));
            $label = new Label(null);
            ResponseHelper::pushErrorsToResponse($label, [$exception]);

            return $label;
        } catch (Exception $e) {
            $exception = new TransportException($e->getMessage(), $e->getCode());
            $label = new Label(null);
            ResponseHelper::pushErrorsToResponse($label, [$exception]);

            return $label;
        }
    }
}
