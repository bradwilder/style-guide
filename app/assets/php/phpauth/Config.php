<?php

namespace PHPAuth;

use Db;

class Config
{
    protected $db;
    protected $config;
    
    public function __construct(Db $db)
    {
        $this->db = $db;
        
        $this->config = array();
        
        $query = "SELECT * FROM config";
        $rows = $this->db->select($query);
        
        foreach ($rows as $row)
        {
            $this->config[$row['setting']] = $row['value'];
        }
    }
    
    public function __get(string $setting)
    {
        return $this->config[$setting];
    }
    
    public function __set(string $setting, string $value)
    {
        $query = "UPDATE config SET value = ? WHERE setting = ?";
        $this->db->query($query, 'ss', array(&$value, &$setting));
        
        $this->config[$setting] = $value;
    }
    
    public function override(string $setting, string $value)
    {
        $this->config[$setting] = $value;
    }
}

?>