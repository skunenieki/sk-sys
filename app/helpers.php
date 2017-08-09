<?php

if ( ! function_exists('elixir'))
{
	/**
	* Get the path to a versioned Elixir file.
	*
	* @param  string  $file
	* @return string
	*/
	function elixir($file)
	{
		$file = '/'.$file;

		static $manifest = null;

		if (is_null($manifest)) {
			$manifest = json_decode(file_get_contents(base_path().'/public/mix-manifest.json'), true);
		}

		if (isset($manifest[$file])) {
			return $manifest[$file];
		}

		throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
	}
}
