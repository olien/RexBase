<?php
/**
 * basecondition ~ less-framework ~ v3.0
 * 
 * @link       git.io/OJYZgw
 * @copyright  copyright 2013 ~ www.basecondition.com
 * @autor      Joachim Doerr ~ hello@basecondition.com
 * @license    licensed under MIT or GPLv3
 */

class getBscUnitFile
{
    /**
     * methode isLessFileExists
     *
     * @param  string $strFolder    get the full foldername
     * @param  string $strFile      get the bsc-filename file.cc.css or file.bsc.css
     * @return bool                 true or false
     */
    public function isLessFileExists($strFolder, $strFile)
    {
        $arrFileName = explode('.', $strFile);
        $strLessFileName = str_replace(array($arrFileName[sizeof($arrFileName) - 1], '.min', '.bsc', '.cc'), array('less', '', '', ''), $strFile);
        
        if ($this->isFileExists($strFolder, $strLessFileName, true) === true) {
            return true;
        } else {
            return false;
        }
    }
    
    
    /**
     * methode isCssFileExists
     *
     * @param  string $strFolder    get the full foldername
     * @param  string $strFile      get the bsc-filename file.cc.css or file.bsc.css
     * @return bool                 true or false
     */
    public function isCssFileExists($strFolder, $strFile)
    {
        $arrFileName = explode('.', $strFile);
        $strFile = str_replace(array('.min', '.bsc', '.cc'), '', $strFile);
        
        if ($this->isFileExists($strFolder, $strFile, true) === true) {
            return true;
        } else {
            return false;
        }
    }
    
    
    /**
     * methode isFileExists
     *
     * @param  string $strFolder    get the full foldername
     * @param  string $strFile      get the bsc-filename file.cc.css or file.bsc.css
     * @param  bool   $boolSet404   write 404 header, default false
     * @return bool                 true or false
     */
    public function isFileExists($strFolder, $strFile, $boolSet404 = false)
    {
        if (file_exists($strFolder . $strFile) === true) {
            return true;
        } else {
            if ($boolSet404 === true) {
                header('HTTP/1.0 404 Not Found');
            }
            return false;
        }
    }
    
    
    /**
     * methode getIt
     *
     * @param  string $strFile      get the bsc-filename file.cc.css or file.bsc.css
     */
    public function getIt($strFile)
    {
        $strFolder = '../';
        $strFile = str_replace(array('.bsc', '.cc'), '', $strFile);
        $arrFileName = explode( '/', $strFile);
        
        if (sizeof($arrFileName) > 1) {
            $arrPath = $arrFileName;
            unset($arrPath[sizeof($arrPath) - 1]);
            $strFolder = $strFolder . implode('/', $arrPath) . '/';
        }
        
        $strFile = $arrFileName[sizeof($arrFileName) - 1];
        $arrFileName = explode('.', $strFile);
        
        if ($arrFileName[sizeof($arrFileName) - 1] == 'css') {
            // include core class
            require_once(dirname( __FILE__ ) . "/getcss.php");
            // init css object
            $objCSS = new getBscUnitCss();
            // get output
            if ($this->isLessFileExists($strFolder, $strFile) === true) {
                echo $objCSS->getParsedFile($strFolder, $strFile, true);                
            } else if ($this->isCssFileExists($strFolder, $strFile) === true) {
                echo $objCSS->getParsedFile($strFolder, $strFile, false);
            }
        }
        else {
            header('HTTP/1.0 404 Not Found');
            exit();
        }
    }
}