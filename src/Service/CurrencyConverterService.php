<?php


namespace Drupal\rir_interface\Service;


use Drupal;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\rir_interface\Form\CurrencyConverterSettingsForm;
use Drupal\rir_interface\Utils\Constants;
use GuzzleHttp\Exception\RequestException;

class CurrencyConverterService
{
    public function getUsdRwfRate() {
        $today = (new DrupalDateTime())->format('dmY');
        $localRate = Drupal::state()->get(Constants::USD_RWF_EXCHANGE_RATE);
        $latestDayRate = Drupal::state()->get(Constants::LATEST_DAY_EXCHANGE_RATE);
        if (isset($localRate) && trim($localRate) !== '' && $latestDayRate === $today) {
            return $localRate;
        }

        $converterUrl = Drupal::config(CurrencyConverterSettingsForm::SETTINGS)->get('currency_converter_url');
        if (isset($converterUrl) and trim($converterUrl) !== '') {
            try {
                $response = Drupal::httpClient()->get($converterUrl);
                if ($response->getStatusCode() === 200) {
                    $rate = Json::decode($response->getBody()->getContents())['USD_RWF'];
                    Drupal::state()->set(Constants::USD_RWF_EXCHANGE_RATE, $rate);
                    Drupal::state()->set(Constants::LATEST_DAY_EXCHANGE_RATE, $today);
                    Drupal::logger('rir_interface')
                        ->info(t('Latest currency rate: 1USD => @rate RWF', ['$rate' => $rate]));
                    return $rate;
                } else {
                    Drupal::logger('rir_interface')
                        ->error(t('Currency Converter Error. Code: @code | Message: @message',
                            [
                                '@code' => $response->getStatusCode(),
                                '@message' => $response->getReasonPhrase()
                            ]));
                }
            } catch (RequestException $e) {
                Drupal::logger('rir_interface')->warning('Currency API not available: ' . $e->getMessage());
            }

        }
        return $localRate;
    }
}