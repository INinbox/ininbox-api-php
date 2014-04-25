<?php

class INinboxFieldOption
{
	public $option;
	public function setOption($option)
	{
	  $this->option = (string)$option;
	}

	public function getOption()
	{
	  return $this->option;
	}
}

?>