<?php
/**
@$params form_select
 *
 */
class General
{
    /**
     * @$params form_select
     * @param $name
     * @param $options
     * @param $selected
     * @param $params
     * @return string
     * when declared as public static function, this can be called anywhere in blade by ClassName::function
     * but if not declared as static, then need to instantiate new object like $var = new ClassName()
     */
    function form_select($name, $options, $selected = '', $params = '')
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
            //$return.='<option value="'.$value.'"'.($selected != $value ? '' : ' selected="selected"').'>'.$key.'</option>';
            $return.='<option value="'.$key.'"'.($selected != $key ? '' : ' selected="selected"').'>'.$value.'</option>';
        }
        echo $return.'</select>';
    }
    /**
     * Call the function
     */
    // form_select('state', array('Michigan'=>'MI', 'Minnesota'=>'MN', 'Wisconsin'=>'WI', 'Wyoming'=>'WY'),'WY', 'onchange="alert(\'Change\');"');

    /**
     * @param $type
     * @param $name
     * @param $id
     * @param $class
     * @param $placeholder
     * @param $label
     * @param $params
     * @return void
     * {{-- -}}<input type="text" class="form-control form-control-lg" name="email" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1">{{-- --}}
     */
    public function form_input($type, $name, $id = '', $class = '', $placeholder = '', $label = '', $params = [])
    {
        $data = [];
        $arrayType = array('text','password', 'radio');
        if(in_array($type, $arrayType)) {
            echo '<input type="'.$type.'" name="'.$name.'" id="'.$id.'" value="' .old($name).'" class="'.$class. ' ' .($this->hasError($name)).'" placeholder="'.$placeholder.'" aria-label="'.$label.'" '.$params.'>';
            //echo $this->hasErrorMessage($name);
        } elseif ($type == 'checkbox') {
            echo '<input type="'.$type.'" name="'.$name.'" id="'.$id.'" class="'.$class.'" value="'.$params.'">';
        } elseif ($type == 'label') {
            echo '<label for="'.$id.'" id="'.$id.'" class="'.$class.'" '.$params.'> '. $placeholder . '</label>';
            //echo $placeholder. '</label>';
        } elseif ($type == 'submit') {
            //echo '<label for="'.$id.'" id="'.$id.'" class="'.$class.'" '.$params.'>';
            echo '<button type="'.$type.'" class="'.$class.'">'.$label.'</button>';
        }
    }


    /**
     * Check for the existence of an error message and return a class name
     *
     * @param  string  $key
     * @return string
     *
     *
     *
     */
    public function hasError($key)
    {
        //$key = str_replace(['\'', '"'], '', $key);
        $errors = session()->get('errors') ?: new \Illuminate\Support\ViewErrorBag;
        return $errors->has($key) ? 'is-invalid' : '';
    }

    public function hasErrorMessage($key)
    {
        $errors = session()->get('errors') ?: new \Illuminate\Support\ViewErrorBag;
        if ($message = $errors->first($key)) {
            echo '<span class="invalid-feedback" role="alert"><strong>'.$message.'</strong></span>';
        }
    }

    public function apiResponseArray($data = array(), $offset = ''){
        if(isset($data))
        {
            $result = '';
            foreach ($data[$offset] as $keys => $values){
                if (is_array($values)){
                    $result .= $result. '<li class="list-group-item d-flex justify-content-between align-items-start">';
                    $result .= $result. '<div class="ms-2 me-auto">';
                    $result .= $result. '<div class="fw-bold">' . $keys . '</div>';
                    foreach ($values as $key => $sub_values){
                        $result .= $result. '---------------' .$sub_values . '<br/>';
                    }
                    $result .= $result. '</div>';
                    $result .= $result. '</li>';
                }else{
                    $result = '<li class="list-group-item d-flex justify-content-between align-items-start">';
                    $result .= '<div class="ms-2 me-auto">';
                    $result .= '<div class="fw-bold">' . $values . '</div>';
                    $result .= '</div>';
                    $result .= '</li>';
                }
            }
            echo $result ;
        }
        //return null;
    }

    public function apiGetResponse($data = array(), $offset = ''){
        if(isset($data))
        {
            if(isset($offset)){
                $data = $data[$offset];
            }
            if(is_array($data)){
                foreach ($data as $keys => $values){
                    if (is_array($values)){
                        echo '<li class="list-group-item d-flex justify-content-between align-items-start">';
                        echo '<div class="ms-2 me-auto">';
                        echo '<div class="fw-bold">' . $keys . '</div>';
                        foreach ($values as $key => $sub_values){
                            echo '---------------' .$sub_values . '<br/>';
                        }
                        echo '</div>';
                       echo '</li>';
                    }else{
                        echo '<li class="list-group-item d-flex justify-content-between align-items-start">';
                        echo '<div class="ms-2 me-auto">';
                        echo '<div class="fw-bold">' . $values . '</div>';
                        echo '</div>';
                        echo '</li>';
                    }
                }
            }
            //echo $result ;
        }
    }

}
?>
