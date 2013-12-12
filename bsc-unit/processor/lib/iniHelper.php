<?php
/**
 * basecondition ~ less-framework ~ v3.0
 * 
 * @link       git.io/OJYZgw
 * @copyright  copyright 2013 ~ www.basecondition.com
 * @autor      Joachim Doerr ~ hello@basecondition.com
 * @license    licensed under MIT or GPLv3
 */

class getBscUnitIniHelper
{
    /**
     * methode generateIniSettings
     *
     * @param  array $arrSettings     ini settingarray
     * @return string                 ini filecontent
     */
    public function generateIniSettings($arrSettings)
    {
        $arrIniSet = array();
        
        foreach ($arrSettings as $aKey => $aVal)
        {
            if (is_array($aVal) === true) {
                $arrIniSet[] = "[$aKey]";
                foreach ($aVal as $bKey => $bVal)
                {
                    $arrIniSet[] = "$bKey = " . (is_numeric($bVal) ? $bVal : '"'.$bVal.'"');
                }
            } else {
                $arrIniSet[] = "$aKey = " . (is_numeric($aVal) ? $aVal : '"'.$aVal.'"');
            }
        }
        return implode("\r\n", $arrIniSet);
    }
    
    
    /**
     * methode writeIniFile
     *
     * @param  string $arrSettings     ini setting as string
     * @return bool                    true or false
    */
    public function writeIniFile($strSettings, $strFile)
    {
        $strPath = realpath(dirname( __FILE__ ) . '/..');
        $strFile = $strPath . $strFile;
        
        if (($objOpenFile = fopen($strFile, 'w')) === true) { 
            return false;
        }
        if ((fwrite($objOpenFile, $strSettings)) === true) { 
            return false;
        }
        fclose($objOpenFile);
        return true;
    }
    
    
    /**
     * methode write_ini_settings
     *
     * @param  array  $arrSettings    ini settingarray
     * @param  string $strFile        ini settingfile
     * @param  bool   $boolShow       show ini settingarray, default false
     * @return array                  ini settings as array
     */
    public function write_ini_settings($arrSettings, $strFile, $boolShow = false)
    {
        $this->writeIniFile($this->generateIniSettings($arrSettings), $strFile);
        
        if ($boolShow === true) {
            return $this->read_ini($strFile);
        }
    }
    
    
    /**
     * methode readIni
     *
     * @param  string $strFile    ini settingfile
     * @return array              ini settings as array
    */
    public function readIni($strFile)
    {
        return parse_ini_file(dirname( __FILE__ ) . '/' . $strFile);
    }
}