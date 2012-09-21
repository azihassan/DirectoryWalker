<?php
class DirectoryWalker 
{
    private $_path;
    private $_callbacks;

    public function __construct($path)
    {
        $this->_path = $path;
        if(!is_readable($this->_path))
        {
            throw new \RuntimeException($this->_path.' is not readable.');
        }
    }

    public function add_rule(\Closure $callback)
    {
        $this->_callbacks[] = $callback;
        return $this;
    }

    public function find()
    {
        return $this->_search($this->_path, $this->_callbacks);
    }

    private function _search($dir, array $callbacks)
    {
        $files = glob($dir.'/*');
        $results = array();

        foreach($files as $f)
        {
            if(is_file($f))
            {
                $invalid = FALSE;
                foreach($callbacks as $c)
                {
                    if($c($f) === FALSE)
                    {
                        $invalid = TRUE;
                        break;
                    }
                }
                $invalid === FALSE && array_push($results, $f);
            }
            if(is_dir($f))
            {
                $results = array_merge($results, $this->_search($f, $callbacks));
            }
        }

        return $results;
    }
}
