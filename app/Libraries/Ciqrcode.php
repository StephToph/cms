<?php

declare(strict_types=1);

namespace App\Libraries;

use QRcode;
use QRimage;

class Ciqrcode
{
    public $cacheable = true;
    public $cachedir = WRITEPATH . 'cache/';
    public $errorlog = WRITEPATH . 'logs/';
    public $quality = true;
    public $size = 1024;

    public function __construct(array $config = [])
    {
        // Include all phpqrcode core files
        include_once APPPATH . 'ThirdParty/phpqrcode/qrconst.php';
        include_once APPPATH . 'ThirdParty/phpqrcode/qrtools.php';
        include_once APPPATH . 'ThirdParty/phpqrcode/qrspec.php';
        include_once APPPATH . 'ThirdParty/phpqrcode/qrimage.php';
        include_once APPPATH . 'ThirdParty/phpqrcode/qrinput.php';
        include_once APPPATH . 'ThirdParty/phpqrcode/qrbitstream.php';
        include_once APPPATH . 'ThirdParty/phpqrcode/qrsplit.php';
        include_once APPPATH . 'ThirdParty/phpqrcode/qrrscode.php';
        include_once APPPATH . 'ThirdParty/phpqrcode/qrmask.php';
        include_once APPPATH . 'ThirdParty/phpqrcode/qrencode.php';

        $this->initialize($config);
    }

    public function initialize(array $config = []): void
    {
        $this->cacheable = $config['cacheable'] ?? $this->cacheable;
        $this->cachedir = $config['cachedir'] ?? $this->cachedir;
        $this->errorlog = $config['errorlog'] ?? $this->errorlog;
        $this->quality = $config['quality'] ?? $this->quality;
        $this->size = $config['size'] ?? $this->size;

        defined('QR_CACHEABLE')       || define('QR_CACHEABLE', $this->cacheable);
        defined('QR_CACHE_DIR')       || define('QR_CACHE_DIR', $this->cachedir);
        defined('QR_LOG_DIR')         || define('QR_LOG_DIR', $this->errorlog);
        defined('QR_FIND_FROM_RANDOM')|| define('QR_FIND_FROM_RANDOM', false);
        defined('QR_PNG_MAXIMUM_SIZE')|| define('QR_PNG_MAXIMUM_SIZE', $this->size);

        if (!defined('QR_FIND_BEST_MASK')) {
            define('QR_FIND_BEST_MASK', $this->quality);
        }

        if (!$this->quality && !defined('QR_DEFAULT_MASK')) {
            define('QR_DEFAULT_MASK', 2); // Default fallback mask
        }
    }

    public function generate(array $params = [])
    {
        $params['data'] = $params['data'] ?? 'QR Code Library';

        // Optional color configuration
        if (!empty($params['black']) && is_array($params['black']) && count($params['black']) === 3) {
            QRimage::$black = $params['black'];
        }

        if (!empty($params['white']) && is_array($params['white']) && count($params['white']) === 3) {
            QRimage::$white = $params['white'];
        }

        $level = in_array($params['level'] ?? 'L', ['L', 'M', 'Q', 'H']) ? $params['level'] : 'L';
        $size = min(max((int) ($params['size'] ?? 4), 1), 10);
        $margin = (int) ($params['margin'] ?? 2);

        // If save path provided
        if (!empty($params['savename'])) {
            QRcode::png($params['data'], $params['savename'], $level, $size, $margin);
            return $params['savename'];
        }

        // Output directly
        QRcode::png($params['data'], null, $level, $size, $margin);
        return true;
    }
}
