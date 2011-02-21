<?php 

/**
* sfNginxPush Class allows for easy access to pushing "stuff" into an HTTP Push 
* module endpoint.
* 
*/
class sfNginxPush
{
  private static $instance;
  
  protected $_config;
  
  /**
   * Private constructor
   *
   * @author Bryan Zarzuela
   */
  private function __construct() { }
  
  /**
   * Gets the singleton instance
   *
   * @return sfNginxPush
   * @author Bryan Zarzuela
   */
  public static function getInstance()
  {
    if (!isset(self::$instance)) {
      $c = __CLASS__;
      self::$instance = new $c;
    }
    
    return self::$instance;
  }
  
  /**
   * Gets a config option
   *
   * @param string $key 
   * @return mixed
   * @author Bryan Zarzuela
   */
  public function getConfig($key)
  {
    if (!isset($this->_config[$key])) {
      throw new Exception("Invalid Config Key $key");
    }
    
    return $this->_config[$key];
  }
  
  /**
   * Sets a config option
   *
   * @param string $key 
   * @param mixed $value 
   * @return $this
   * @author Bryan Zarzuela
   */
  public function setConfig($key, $value)
  {
    $this->_config[$key] = $value;
    
    return $this;
  }
  
  /**
   * Pushes the message into the channel specified.
   * Message is converted to the Teleserv Notification JSON format before POST.
   *
   * @param string $channel 
   * @param mixed $data 
   * @return $this
   * @author Bryan Zarzuela
   */
  public function push($channel, $subject, $data)
  {
    try {
      $endpoint = $this->getConfig('endpoint');
    } catch (Exception $e) {
      throw new Exception('You need to configure the endpoint first');
    }
    
    $ch = curl_init($endpoint . '?id=' . $channel);
    
    $payload = array(
      'subject' => $subject,
      'timestamp' => time(),
      'data' => $data,
    );
    
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    
    $ret = curl_exec($ch);
    curl_close($ch);
    
    if ($ret === false) {
      throw new Exception('Error Pushing: ' . curl_error($ch));
    }
    
    return $this;
  }
}