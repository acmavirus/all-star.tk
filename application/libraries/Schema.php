<?php 
class Schema 
{
	private $structure = [];
	private $stack_name = null;
	private $hierarchical = false;
	private $memory = [];

	private static $self;

	public function __construct() {}

	public function stack($stack_name)
	{
		$this->structure[$stack_name] = [];
		$this->stack_name = $stack_name;

		return $this;
	}

	public function _stack() 
	{

		if ($this->hierarchical) 
		{
			$this->structure[$this->stack_name][] = $this->memory;
			$type = $this->memory['@type'];
			// reset var
			$this->memory = [];
			$this->memory['@type'] = $type;
		}
		else
			$this->stack_name = null;

		return $this;
	}

	public function render($echo = false)
	{
		$html = '';
		$html .= '<script type="application/ld+json">'.PHP_EOL;
		$html .= 	@json_encode($this->structure, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).PHP_EOL;
		$html .= '</script>'.PHP_EOL;
		$this->structure = [];

		if ($echo===true)
			echo $html;
		else
			return $html;
	}

	public function add_section($name, $data)
	{
		if (!is_array($data) && !$name) exit('Wrong settings!!');
		
		if ($this->hierarchical) 
		{
			$this->memory[$name] = $data;
		}
		else
		{
			$this->structure[$this->stack_name][$name] = $data;
		}

		
		return $this;
	}

	public function render_data()
	{
		return $this->structure;
	}


	static function init($type, $hierarchical = false) 
	{
		self::$self = new self;
		self::$self->hierarchical = $hierarchical;
		
		if ($type!==false) {
			self::$self->structure['@context'] = "https://schema.org";
			self::$self->structure['@type'] = $type;
		}

		return self::$self;
	}

	public function id($data) 
	{
		if ($this->hierarchical) 
		{
			$this->memory['@id'] = $data;
		}
		else
		{
			if ($this->stack_name === null)
				$this->structure['@id'] = $data;
			else
				$this->structure[$this->stack_name]['@id'] = $data;
		}

		return $this;
	}

	public function type($data) 
	{
		if ($this->hierarchical) 
		{
			$this->memory['@type'] = $data;
		}
		else
		{
			if ($this->stack_name === null)
				$this->structure['@type'] = $data;
			else
				$this->structure[$this->stack_name]['@type'] = $data;
		}

		return $this;
	}

	public function __call($name, $context)
	{

		$data = $context[0];

		if ($this->hierarchical) 
		{
			$this->memory[$name] = $data;
		}
		else
		{
			if ($this->stack_name === null)
				$this->structure[$name] = $data;
			else 
				$this->structure[$this->stack_name][$name] = $data;
		}

		return $this;
	}

	public static function __callStatic($name, $context) 
	{
		if (self::$self===null) exit('Unable to init schema');

		if (self::$self->hierarchical)
			self::$self->memory['@type'] = $name;
		else
			self::$self->structure[self::$self->stack_name]['@type'] = $name;

		return self::$self;
	}

}