<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * XMR Price Controller - Monero Price Fetching via Tor
 * =========================================================================
 */

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class XmrPriceController extends Controller
{
    /**
     * Cache duration in seconds (4 minutes)
     */
    private const CACHE_DURATION = 240;

    /**
     * CoinGecko API endpoint
     */
    private const COINGECKO_ENDPOINT = 'https://api.coingecko.com/api/v3/simple/price';

    /**
     * CryptoCompare API endpoint
     */
    private const CRYPTOCOMPARE_ENDPOINT = 'https://min-api.cryptocompare.com/data/price';

    /**
     * Tor SOCKS5 proxy address
     */
    private const TOR_PROXY = 'socks5h://127.0.0.1:9050';

    /**
     * HTTP request timeout in seconds
     */
    private const REQUEST_TIMEOUT = 25;

    /**
     * Connection timeout in seconds
     */
    private const CONNECT_TIMEOUT = 15;

    /**
     * Get XMR price from cached APIs or manual override
     *
     * @return string
     */
    public function getXmrPrice(): string
    {
        try {
            Log::debug('Fetching XMR price', [
                'cache_key' => 'xmr_price',
                'duration' => self::CACHE_DURATION,
            ]);

            // Attempt to get price from cache or fetch fresh
            $price = Cache::remember('xmr_price', self::CACHE_DURATION, function (): string {
                return $this->fetchPriceViaApis();
            });

            Log::debug('XMR price retrieved', [
                'price' => $price,
            ]);

            return $price;

        } catch (\Exception $e) {
            Log::error('Unexpected error in getXmrPrice', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return 'UNAVAILABLE';
        }
    }

    /**
     * Fetch XMR price from API sources via Tor
     *
     * PRIVACY WARNING:
     * Even with Tor routing, API calls create network traffic patterns.
     * For maximum privacy, use manual price override instead.
     *
     * To use manual price: Uncomment and set value below:
     * return number_format(416.00, 2, '.', '');
     *
     * @return string
     */
    private function fetchPriceViaApis(): string
    {
        // MANUAL PRICE OVERRIDE (uncomment to disable API calls)
        // ===================================================================
        // return number_format(416.00, 2, '.', ''); // Set your manual XMR price here
        // ===================================================================

        // Create Tor-routed HTTP client
        $client = $this->createTorClient();

        // Try CoinGecko API first
        $price = $this->fetchFromCoinGecko($client);
        if ($price !== null) {
            Log::info('XMR price fetched from CoinGecko', [
                'price' => $price,
            ]);
            return $price;
        }

        Log::warning('CoinGecko API failed, trying CryptoCompare');

        // Try CryptoCompare API as fallback
        $price = $this->fetchFromCryptoCompare($client);
        if ($price !== null) {
            Log::info('XMR price fetched from CryptoCompare', [
                'price' => $price,
            ]);
            return $price;
        }

        Log::error('All XMR price APIs failed via Tor');
        return 'UNAVAILABLE';
    }

    /**
     * Create Guzzle HTTP client with Tor SOCKS5 proxy
     *
     * @return Client
     */
    private function createTorClient(): Client
    {
        return new Client([
            'proxy' => [
                'http' => self::TOR_PROXY,
                'https' => self::TOR_PROXY,
            ],
            'timeout' => self::REQUEST_TIMEOUT,
            'connect_timeout' => self::CONNECT_TIMEOUT,
            'verify' => true, // SSL verification enabled
            'curl' => [
                CURLOPT_PROXYTYPE => CURLPROXY_SOCKS5_HOSTNAME,
            ],
        ]);
    }

    /**
     * Fetch price from CoinGecko API
     *
     * @param Client $client
     * @return string|null
     */
    private function fetchFromCoinGecko(Client $client): string|null
    {
        try {
            Log::debug('Requesting price from CoinGecko API');

            $response = $client->request('GET', self::COINGECKO_ENDPOINT, [
                'query' => [
                    'ids' => 'monero',
                    'vs_currencies' => 'usd',
                ],
                'timeout' => self::REQUEST_TIMEOUT,
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['monero']['usd']) && is_numeric($data['monero']['usd'])) {
                $price = floatval($data['monero']['usd']);
                return number_format($price, 2, '.', '');
            }

            Log::warning('CoinGecko response missing monero.usd field', [
                'response' => $data,
            ]);

            return null;

        } catch (ConnectException $e) {
            Log::warning('CoinGecko connection error', [
                'message' => $e->getMessage(),
            ]);
            return null;

        } catch (RequestException $e) {
            Log::warning('CoinGecko request error', [
                'message' => $e->getMessage(),
                'status_code' => $e->getResponse()?->getStatusCode(),
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('CoinGecko unexpected error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Fetch price from CryptoCompare API
     *
     * @param Client $client
     * @return string|null
     */
    private function fetchFromCryptoCompare(Client $client): string|null
    {
        try {
            Log::debug('Requesting price from CryptoCompare API');

            $response = $client->request('GET', self::CRYPTOCOMPARE_ENDPOINT, [
                'query' => [
                    'fsym' => 'XMR',
                    'tsyms' => 'USD',
                ],
                'timeout' => self::REQUEST_TIMEOUT,
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['USD']) && is_numeric($data['USD'])) {
                $price = floatval($data['USD']);
                return number_format($price, 2, '.', '');
            }

            Log::warning('CryptoCompare response missing USD field', [
                'response' => $data,
            ]);

            return null;

        } catch (ConnectException $e) {
            Log::warning('CryptoCompare connection error', [
                'message' => $e->getMessage(),
            ]);
            return null;

        } catch (RequestException $e) {
            Log::warning('CryptoCompare request error', [
                'message' => $e->getMessage(),
                'status_code' => $e->getResponse()?->getStatusCode(),
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('CryptoCompare unexpected error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }
}
