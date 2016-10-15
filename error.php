<?php

class InvalidArgumentException2 extends Exception
{
  function __construct($error,$error_message,$type='invalidargument')
  {
    parent::__construct($error,$error_message,$type);
  }
}

class SessionCompromisedException extends Exception
{
  function __construct($error,$error_message,$type='sessioncompromised')
  {
    parent::__construct($error,$error_message,$type);
  }
}

class DatabaseErrorException extends Exception
{
  function __construct($error,$error_message,$type='mysql',$sql=NULL)
  {
    parent::__construct($error_message,$error,$type,$sql);
  }

}

class NoSessionException extends Exception
{
  function __construct($error,$error_message,$type='nosession')
  {
    parent::__construct($error,$error_message,$type);
  }
}

class InternalErrorException extends Exception
{
  function __construct($error,$error_message,$type='internalerror')
  {
    parent::__construct($error,$error_message,$type);
  }
}

class IllegalAccessException extends Exception
{
  function __construct($error,$error_message,$type='illegalaccess')
  {
    parent::__construct($error,$error_message,$type);
  }
}


?>
