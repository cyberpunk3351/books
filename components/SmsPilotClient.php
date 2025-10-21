<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\log\Logger;

class SmsPilotClient extends Component
{
    public string $apiUrl = 'https://smspilot.ru/api.php';
    public string $apiKey = 'EMULATOR';
    public ?Logger $logger = null;
    public bool $simulate = true;
    public string $defaultSender = 'BOOKS';

    public function init(): void
    {
        parent::init();
        if ($this->logger === null) {
            $this->logger = Yii::getLogger();
        }
    }

    public function send(string $phone, string $message): bool
    {
        if ($this->simulate || strtoupper($this->apiKey) === 'EMULATOR') {
            $this->logger->log("SMS to {$phone}: {$message}", \yii\log\Logger::LEVEL_INFO);
            return true;
        }

        $payload = http_build_query([
            'send' => $message,
            'to' => $phone,
            'from' => $this->defaultSender,
            'apikey' => $this->apiKey,
            'format' => 'json',
        ]);

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT => 10,
        ]);

        $responseBody = curl_exec($ch);
        $error = curl_error($ch);
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ($responseBody === false || $status >= 400) {
            $this->logger->log(
                sprintf('SMS send failed: %s (%s)', $error ?: 'HTTP error', $status),
                \yii\log\Logger::LEVEL_ERROR
            );
            return false;
        }

        $data = json_decode($responseBody, true);
        $success = is_array($data) ? (bool) ($data['success'] ?? true) : true;

        if (!$success) {
            $this->logger->log(
                sprintf('SMS send error: %s', $responseBody),
                \yii\log\Logger::LEVEL_ERROR
            );
        }

        return $success;
    }
}
