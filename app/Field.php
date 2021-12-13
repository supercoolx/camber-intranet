<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'placeholder', 'bottom_text', 'subsection_id', 'type',
        'length', 'options', 'required', 'order', 'default'
    ];

    /**
     * The field
     */
    public function orders()
    {
        return $this->belongsToMany('\App\Order')->withPivot('value', 'status', 'updated_at');
    }

    public function fieldOrder()
    {
        return $this->hasMany('\App\FieldOrder');
    }
    /**
     * Get option's for radio buttons
     *
     * @param  string  $value
     * @return string
     */
    public function getOptionsAttribute($value)
    {
     
        if(!$value) return null;

        $optionList = explode(';', $value);

        $options = [];
        $checked = head($optionList);
        $checked = $this->default ? $this->default : $checked;
        $checked =isset($this->pivot->value) ? $this->pivot->value : $checked;
        foreach($optionList as $option) {
            $optionInfo = ['value' => $option, 'checked' => '','value_with_hints'=>''];
            $optionInfo['value'] = $this->filterHintsOut($option);
            $optionInfo['value_with_hints'] = $option;

            if($checked == $option) {
                $optionInfo['checked'] = 'checked';
            }
            $options[] = $optionInfo;
           
        }

        return $options;
    }
    public static function makeMoreReadable($name){
        $name = str_replace('Enter','',$name);
        $name = trim($name);
        $name = ucfirst($name);
        return $name;
    }
    public static function filterHintsOut($str){
        $str = strip_tags($str);

        $str = str_replace('example','',$str);
        $str = trim($str);
        return $str;
    }
}
