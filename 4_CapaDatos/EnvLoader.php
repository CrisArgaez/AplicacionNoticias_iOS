<?php

class EnvLoader {
  public static function get($key)
  {
    $envFile = __DIR__ . '/.env';
    if (!file_exists($envFile)) {
      throw new Exception('.env file not found.');
    }

    $envContent = file_get_contents($envFile);
    $lines = explode("\n", $envContent);
    $envVariables = [];

    foreach ($lines as $line) {
      $line = trim($line);
      if (!empty($line) && strpos($line, '=') !== false) {
        list($envKey, $envValue) = explode('=', $line, 2);
        $envVariables[$envKey] = $envValue;
      }
    }

    if (isset($envVariables[$key])) {
      return $envVariables[$key];
    } else {
      throw new Exception('Key not found in .env file.');
    }
  }
}
?>