<?php
/**
 * basecondition ~ less-framework ~ v3.0
 * 
 * @link       git.io/OJYZgw
 * @copyright  copyright 2013 ~ www.basecondition.com
 * @autor      Joachim Doerr ~ hello@basecondition.com
 * @license    licensed under MIT or GPLv3
 */

class getBscUnitCss
{
    /**
     * @var '' string for global usage
     */
    var $strFolder = '';
    
    /**
     * @var NULL string for global usage
     */
    var $strFileName = NULL;
    
    /**
     * @var NULL string for global usage
     */
    var $strCacheFileName = NULL;
    
    /**
     * @var NULL string for global usage
     */
    var $strLessFileName = NULL;
    
    /**
     * @var NULL int for global usage
     */
    var $intLastModificationTime = NULL;
    
    /**
     * @var false bool for global usage
     */
    var $boolMinimalizer = false;
    
    /**
     * @var false bool for global usage
     */
    var $boolFileIsCss = false;
    
    /**
     * @var false bool for global usage
     */
    var $boolCacheFileGenerate = false;
    
    /**
     * @var NULL array for global usage
     */
    var $arrIni = NULL;
    
    
    /**
     * methode getDefaults
     *
     * @param  string $strFile    optional ini file
     * @return array              ini settingarray
     */
    public function getDefaults($strFile = NULL)
    {
        if ($strFile === NULL) {
            $strFile = "../data/config.ini";
        }
        // include core class
        require_once(dirname( __FILE__ ) . "/iniHelper.php");
        $objIni = new getBscUnitIniHelper();
        return $objIni->readIni($strFile);
    }
    
    
    /**
     * methode setDefaults
     *
     * @param string $strFile    optional ini file
     */
    public function setDefaults($strFile = NULL)
    {
        $this->arrIni = $this->getDefaults();
    }
    
    
    /**
     * methode folderNameGeneration
     *
     * @param string $strFolder    get the full foldername
     */
    public function folderNameGeneration($strFolder)
    {
        $this->strFolder = $strFolder;
    }
    
    
    /**
     * methode fileNameGeneration
     *
     * @param string $strFile     get the bsc-filename file.cc.css or file.bsc.css
     * @param bool   $boolLess    disabel less parser, default enabled
     */
    public function fileNameGeneration($strFile, $boolLess = true)
    {
        $arrFile = explode('/', $strFile);
        $arrFileName = explode('.', $arrFile[sizeof($arrFile) - 1]);
        $this->strCacheFileName = 'cache_' . $strFile;
        $this->strFileName = $strFile;
        
        if($boolLess === true) {
            $this->strLessFileName = str_replace(array( '.min', '.css'), array('', '.less'), $strFile);
        }
        
        foreach ($arrFileName as $strFileNamePart)
        {
            switch ($strFileNamePart) {
                case 'min':
                    $boolFileHasMin = true;
                    $this->boolMinimalizer = true;
                    break;
                    
                case 'css':
                    $this->boolFileIsCss = true;
                    break;
            }
        }
    }
    
    
    /**
     * methode fileCheckModificationTime
     *
     * @param  string $strFile      get the bsc-filename file.cc.css or file.bsc.css
     * @param  int    $intTimeUpTo  the elapsed time, default 3600
     * @return bool or int          the filemtime or 0 or true or false
     */
    public function fileCheckModificationTime($strFile, $intTimeUpTo = 3600)
    {
        if ($intTimeUpTo == 0) {
            if (file_exists($strFile) === true) {
                return filemtime($strFile);
            } else {
                return 0;
            }
        } else {
            return (file_exists($strFile) === false or (time() - filemtime($strFile)) > $intTimeUpTo) ? true : false;
        }
    }
    
    
    /**
     * methode fileCacheChangeDetection
     *
     * @param bool   $boolLess    disabel less parser, default enabled
     */
    public function fileCacheChangeDetection($boolLess = true)
    {
        $strFileName = $this->strFileName;
        if ($boolLess === true) {
            $strFileName = $this->strLessFileName;
        }
        
        $strLessFileModificationTime = $this->fileCheckModificationTime($this->strFolder . $strFileName, 0);
        $strCacheFileModificationTime = $this->fileCheckModificationTime($this->arrIni['strCacheFolder'] . $this->strCacheFileName, 0);
        $this->intLastModificationTime = ($strLessFileModificationTime > mktime(0, 0, 0, 21, 5, 1980)) ? $strLessFileModificationTime : mktime(0, 0, 0, 21, 5, 1980);
        
        if ($strCacheFileModificationTime > 0) {
            if ($strCacheFileModificationTime < $strLessFileModificationTime) {
                $this->boolCacheFileGenerate = true;
                $this->intLastModificationTime = $strCacheFileModificationTime;
            } else {
                $this->boolCacheFileGenerate = false;
            }
        } else {
            $this->boolCacheFileGenerate = true;
        }
    }
      
    
    /**
     * methode processMinimalize
     *
     * @param  $string $strBuffer    string to execute
     * @return $string               executed string
     */
    public function processMinimalize($strBuffer)
    {
        /* remove comments */
        $strBuffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $strBuffer);
        /* remove tabs, spaces, newlines, etc. */
        $strBuffer = str_replace(array("\r\n", "\r", "\n", "\t", '    ', '        ', '        '), '', $strBuffer);
        // get string
        return $strBuffer;
    }
    
    
    /**
     * methode parseFile
     *
     * @param string $strFile     get the bsc-filename file.cc.css or file.bsc.css
     * @param bool   $boolLess    disabel less parser, default enabled
     */
    public function parseFile($strFile, $boolLess = true)
    {
        $this->fileNameGeneration($strFile, $boolLess);
        
        if ($this->boolFileIsCss === true && $boolLess === true) {
            $this->fileCacheChangeDetection ($boolLess);
            
            if ($this->boolCacheFileGenerate === true) {
                // include lessc
                require_once(dirname( __FILE__ ) . '/../vendor/lessc.php');
                // init less object
                $objLess = new lessc($this->strFolder . $this->strLessFileName);
                // check compression name
                if ($this->boolMinimalizer === true) {
                    file_put_contents($this->arrIni['strCacheFolder'] . $this->strCacheFileName, str_replace(array('{csskey}', '{year}'), array(str_replace('cache_', '', $this->strCacheFileName), date("Y")), $this->arrIni['strCompressCopyright']) . $this->processMinimalize($objLess->parse()));
                } else {
                    file_put_contents($this->arrIni['strCacheFolder'] . $this->strCacheFileName, str_replace(array('{csskey}', '{year}'), array(str_replace('cache_', '', $this->strCacheFileName), date("Y")), $this->arrIni['strCopyright']) . $objLess->parse());
                }
            }
        } else if ($this->boolFileIsCss === true) {
            $strFile = str_replace(array('.min', '.cc', '.bsc'), '', $strFile);
            
            if ($this->boolMinimalizer === true) {
                file_put_contents($this->arrIni['strCacheFolder'] . $this->strCacheFileName, str_replace(array('{csskey}', '{year}'), array(str_replace('cache_', '', $this->strCacheFileName), date ("Y")), $this->arrIni['strCompressCopyright']) . $this->processMinimalize(file_get_contents($this->strFolder . $strFile)));
            } else {
                file_put_contents($this->arrIni['strCacheFolder'] . $this->strCacheFileName, str_replace(array('{csskey}', '{year}'), array(str_replace('cache_', '', $this->strCacheFileName), date("Y")), $this->arrIni['strCopyright']) . file_get_contents($this->strFolder . $strFile));
            }
        }
    }
    
    
    /**
     * methode getParsedFile
     *
     * @param  string $strFolder      get the full foldername
     * @param  string $strFile        get the bsc-filename file.cc.css or file.bsc.css
     * @param  bool   $boolLess       disabel less parser, default enabled
     * @return string                 get the full parsing less file or 304 header or 404 header
     */
    public function getParsedFile($strFolder, $strFile, $boolLess = true)
    {
        $this->setDefaults();
        $this->folderNameGeneration($strFolder);
        $this->parseFile($strFile, $boolLess);
        
        // check gzhandler
        if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') === true) {
            ob_start( 'ob_gzhandler' );
        } else {
            ob_start();
        }
        // get file or http 304 not modified
        if ($this->boolCacheFileGenerate === false && isset($_SERVER['If-Modified-Since']) === true && strtotime($_SERVER['If-Modified-Since']) >= $this->intLastModificationTime) {
            header ('HTTP/1.0 304 Not Modified');
        } else {
            header('Content-type: text/css');
            header('Vary: Accept-Encoding');
            header('Last-Modified: ' . gmdate("D, d M Y H:i:s", $this->intLastModificationTime) . " GMT");
            return file_get_contents($this->arrIni['strCacheFolder'] . $this->strCacheFileName);
        }
    }
}