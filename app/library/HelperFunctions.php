<?php

/*
 * This is a class of helper function that can be used any where in the system
*/
class HelperFunctions {
  public static function s4()
  {
    //
    return  substr(
              base_convert(
                floor((1 +
                  ((float) mt_rand() / (float) mt_getrandmax()) // gets float random between 0 and 1
                ) * 0x10000), 10, 16) ,1);
  }
  // same function as one being used on front end
  public static function UUIDGenerator()
  {
    return  HelperFunctions::s4() .
            HelperFunctions::s4() . '-' .
            HelperFunctions::s4() . '-' .
            HelperFunctions::s4() . '-' .
            HelperFunctions::s4() . '-' .
            HelperFunctions::s4() .
            HelperFunctions::s4() .
            HelperFunctions::s4();
  }
}