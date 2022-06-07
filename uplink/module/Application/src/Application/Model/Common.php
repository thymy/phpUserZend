<?php
namespace Application\Model;

class Common
{	
	/*
	* Function arrayToMessage
	* Input: 1-dimension array
	* Convert an array into message with specific key for json return
	* key_split: ":012345678901234567890123456789:"
	* item_split: "~012345678901234567890123456789~"
	* return: string Message
	*/
	public function arrayToMessage($arrInput)
	{
		$str = ""; 
        if( is_array( $arrInput ) == TRUE )
        {       
            foreach($arrInput as $key => $value)
            {
                if( isset($arrInput[$key])  == TRUE )
                {
                    $str .= $key . ":012345678901234567890123456789:".
                            $value . "~012345678901234567890123456789~";
                }
            }
        }
        return $str;
	}
	
	
	/*
	* Function arrayToMessage
	* Input: 2-dimension array
	* Convert an array into message with specific key for json return
	* key_split: ":012345678901234567890123456789:"
	* item_split: "~012345678901234567890123456789~"
	* return: string Message
	*/
	public function arrayToMessage2($arrInput)
	{
		$str = ""; 
        if( is_array( $arrInput ) == TRUE )
        {       
            foreach($arrInput as $key => $value)
            {
                if( isset($arrInput[$key])  == TRUE )
                {
                    $str .= $key.":ABCDEFGHIJKLMNOPQRSTUVWXYZ:"
							.$this->arrayToMessage($value)
							."~ABCDEFGHIJKLMNOPQRSTUVWXYZ~";
                }
            }
        }
        return $str;
	}
	
	/*
	* Function trim data, from string to 2-dimension array
	*/
	public static function myTrim( $data )
    {
        //-------------------------------------------------------------------//
        // Check input
        //-------------------------------------------------------------------//
        if( empty( $data ) == true )
        {
            return;
        }
        
        //-------------------------------------------------------------------//
        // Data is a string
        //-------------------------------------------------------------------//
        if( is_array( $data ) == false )
        {
            return trim( $data );
        }
        //-------------------------------------------------------------------//
        // Data is a array
        //-------------------------------------------------------------------//
        else
        {
            $result = array();
            foreach( $data as $key => $value )
            {
                if( is_array( $value ) == false )
                {
                   $result[ $key ] = trim( $value );
                }
                else
                {
                    $temp = array();
                    foreach( $value as $key_sub => $value_sub )
                    {
                       $temp[ $key_sub ] = trim( $value_sub );
                    }
                    
                    $result[ $key ] = $temp;
                }
            }
            
            return $result;
        }
    }
	
	public function iniRight($right)
	{
		if(empty($right) == TRUE)
		{
			$right = 0;
		}
		return $right;
	}
}