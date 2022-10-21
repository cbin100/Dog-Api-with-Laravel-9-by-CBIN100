<?php
/**
 @$params form_select
 *
 */
class General_Helper
{
private $_localhost = 'localhost';
 private $_user = 'root';
 private $_password = 'root';
 private $_dbname = 'technologi';

    public function __construct()
    {
        $foo = '';
    }
    public static function form_select($name,$options,$selected = '',$params = '')
    {
        $return = '<select name="'.$name.'" id="'.$name.'"';
        if(is_array($params))
        {
            foreach($params as $key=>$value)
            {
                $return.= ' '.$key.'="'.$value.'"';
            }
        }
        else
        {
            $return.= $params;
        }
        $return.= '>';
        foreach($options as $key=>$value)
        {
            $return.='<option value="'.$value.'"'.($selected != $value ? '' : ' selected="selected"').'>'.$key.'</option>';
        }
        return $return.'</select>';
    }
}
?>
