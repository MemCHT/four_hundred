{{isset($length)
    ?( strlen($text) > $length ? substr($text,0 , $length).'...' : $text)
    :( strlen($text) > 35 ? substr($text,0 , 35).'...' : $text)
}}
