<?php

namespace mnhcc\epub;
class Epub {
    const EXEPTION_NOT_CORRECT_MIMETYPE = -2;
    const EXEPTION_NO_CONTENT_OPF = -4;
    const EXEPTION_PARSE_CONTENT_OPF = -8;
    public function getCreator() {
	return $this->_creator;
    }

    public function getTitle() {
	return $this->_title;
    }

    public function getPublisher() {
	return $this->_publisher;
    }

    public function getDate() {
	return $this->_date;
    }

    public function getIdentifier() {
	return $this->_identifier;
    }

    public function getLanguage() {
	return $this->_language;
    }

    public function getDescription() {
	return $this->_description;
    }

    public function getRights() {
	return $this->_rights;
    }

    public function getError() {
	return $this->_error;
    }

    public function getFielname() {
	return $this->_fielname;
    }

    
    public function __construct($file) {
	if(!\file_exists($file)) {
	    throw new \Exeption("no $file");
	}
	$this->_fielname = $file;
	$this->fetchMeta();
    }

    protected function fetchMeta() {
	// epub files are zip archives. so we open a zip file.
	$zip = new \ZipArchive();
	if ($zip->open($file, \ZipArchive::CHECKCONS) !== true) {
	    echo 'WARNING: Could not open file ' . $file . '.' . PHP_EOL;
	    $this->error = -1;
	    return false;
	}
	// now we check for the correct mime type
	if ('application/epub+zip' != trim($zip->getFromName('mimetype'))) {
	    $this->error = self::EXEPTION_NOT_CORRECT_MIMETYPE;
	    throw new \Exeption('File ' . $file . ' has not correct mimetype information.', self::EXEPTION_NOT_CORRECT_MIMETYPE);
	}

	// next we try to find the content file
	$content = '';
	for ($i = 0; $i < $zip->numFiles; $i++) {
	    if ($content == '') {
		$stat = $zip->statIndex($i);
		$statExt = \array_pop(explode('.', $stat['name']));
		if ($statExt == 'opf') {
		    $content = $zip->getFromIndex($i);
		}
	    }
	}
	if ($content == '') {
	    $this->error = self::EXEPTION_NO_CONTENT_OPF;
	    throw new \Exeption('Could not find content.opf in file ' . $file . '.', self::EXEPTION_NO_CONTENT_OPF);
	}

	// if we found a content file, we try to parse it as an xml file
	try {
	    $content = \str_replace('<?xml version="1.1"', '<?xml version="1.0"', $content);
	    $content = @new \SimpleXMLElement($content);
	} catch (\Exception $e) {
	    $this->error = EXEPTION_PARSE_CONTENT_OPF;
	    throw new \Exeption('Could not parse content.opf from file ' . $file . '. ', self::EXEPTION_PARSE_CONTENT_OPF);
	}

	// finally, we can read all meta information out of the content file
	$dc = $content->metadata->children('http://purl.org/dc/elements/1.1/');

	$this->creator = (string) $dc->creator;
	$this->title = (string) $dc->title;
	$this->publisher = (string) $dc->publisher;
	$this->date = (string) $dc->date;
	$this->description = (string) $dc->description;
	$this->rights = (string) $dc->rights;
	$this->identifier = (string) $dc->identifier;
	$this->language = (string) $dc->language;

	// and finally close our epub-zip-archive
	$zip->close();
    }

}
