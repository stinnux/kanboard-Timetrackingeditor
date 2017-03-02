<?php

namespace Kanboard\Plugin\Timetrackingeditor;

use SplFileObject;

/**
 * HTML Writer
 *
 * Allows exporting Data as HTML file.
 * In contrast to CSV this allows clean interpration of HTML Tags by Excel
 *
 * @author  Thomas Stinner
 */
class Html
{
    /**
     * CSV/SQL columns
     *
     * @access private
     * @var array
     */
    private $columns = array();

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
    }

    /**
     * Check boolean field value
     *
     * @static
     * @access public
     * @param  mixed $value
     * @return int
     */
    public static function getBooleanValue($value)
    {
        if (! empty($value)) {
            $value = trim(strtolower($value));
            return $value === '1' || $value{0} === 't' || $value{0} === 'y' ? 1 : 0;
        }

        return 0;
    }

    /**
     * Output CSV file to standard output
     *
     * @static
     * @access public
     * @param  array  $rows
     */
    public static function output(array $rows)
    {
        $html= new static;
        $html->write('php://output', $rows);
    }

    /**
     * Define column mapping between CSV and SQL columns
     *
     * @access public
     * @param  array $columns
     * @return Csv
     */
    public function setColumnMapping(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * Write HTML file
     *
     * @access public
     * @param  string    $filename
     * @param  array     $rows
     * @return Html
     */
    public function write($filename, array $rows)
    {

        $fp = fopen($filename, 'w');

        if (is_resource($fp)) {
            $types = array_shift($rows);

            $this->writeHeader($fp);
            foreach ($rows as $row) {
                $this->writeRow($fp, $types, $row);
            }
            $this->writeFooter($fp);
            fclose($fp);
        }

        return $this;
    }

  /**
   *  write a HTML header
   *
   * @param $fp filepointer
   */
    private function writeHeader($fp)
    {
      fwrite($fp,"<HTML><HEAD><STYLE>\n");
      fwrite($fp,"b,p {mso-data-placement: same-cell; }\n");
      fwrite($fp,".num { mso-number-format:General; }\n");
      fwrite($fp,".dec { mso-number-format: 0,00; }\n");
      fwrite($fp,".text { mso-number-format:\"\\@\"; }\n");
      fwrite($fp,".date { mso-number-format:\"Short Date\"; }\n");
      fwrite($fp,"</STYLE></HEAD>\n");
      fwrite($fp,"<BODY><TABLE>");

    }

  /**
   * write a single row
   *
   * @param fp filepointer
   * @param $row row
   */
    private function writeRow($fp, array $types, array $row)
    {
        fwrite($fp,"<tr>");
        foreach ($row as $key => $value) {
          fwrite($fp,"<td class='".$types[$key]."'>".$value."</td>");
        }
        fwrite($fp,"</tr>\n");
    }

  /**
   * write a HTML footer
   */
   private function writeFooter($fp)
   {
     fwrite($fp,"</TABLE></BODY></HTML>");
   }

    /**
     * Associate columns header with row values
     *
     * @access private
     * @param  array $row
     * @return array
     */
    private function associateColumns(array $row)
    {
        $line = array();
        $index = 0;

        foreach ($this->columns as $sql_name => $csv_name) {
            if (isset($row[$index])) {
                $line[$sql_name] = $row[$index];
            } else {
                $line[$sql_name] = '';
            }

            $index++;
        }

        return $line;
    }

    /**
     * Filter empty rows
     *
     * @access private
     * @param  array $row
     * @return array
     */
    private function filterRow(array $row)
    {
        return array_filter($row);
    }
}
